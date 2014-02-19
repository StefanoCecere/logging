<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Stefano Cecere <stefano.cecere@krur.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class Tx_Logging_Logger
{

    public static function login()
    {
        $site_id = $GLOBALS['TSFE']->rootLine[0]['uid'];

        // Add page stats
        $fields = array(
            'pid' => 0,
            'fe_user' => intval($GLOBALS['TSFE']->fe_user->user['uid']),
            'crdate' => $GLOBALS['SIM_EXEC_TIME'],
            'page_id' => $GLOBALS['TSFE']->id,
            'site_id' => $site_id,
            'ip_address' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REMOTE_ADDR'),
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_logging_domain_model_session', $fields);
        $session_id = $GLOBALS['TYPO3_DB']->sql_insert_id();
        $GLOBALS['TSFE']->fe_user->setKey('ses', 'tx_logging:session', $session_id);
        $GLOBALS['TSFE']->fe_user->storeSessionData();

        self::log_event('login', 'page', $GLOBALS['TSFE']->id, array('site_id' => $site_id, 'session_id' => $session_id));
    }

    public static function logout()
    {
        $vars = array();
        self::log_event('logout', 'page', $GLOBALS['TSFE']->id, $vars);
    }

    public static function viewpage()
    {
        $vars = array();
        self::log_event('view', 'page', $GLOBALS['TSFE']->id, $vars);

        //t3lib_div::devlog('viewpage', 'logging', 0, Array('$GP' => t3lib_div::_GET('tx_solr')));
        $qsolr = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('tx_solr');
        if (isset($qsolr['q'])) {
            self::search('solr', array('q' => $qsolr['q']));
        }
    }

    public static function modify($table, $record_id, $note)
    {
        $vars = array(
            'note' => $note
        );
        self::log_event('modify', $table, $record_id, $vars);
    }

    public static function search($table, array $queries, $note = '')
    {
        // t3lib_div::devlog('search', 'logging', 0, Array('$queries' => $queries));
        $keywords = '';
        foreach ($queries as $key => $value) {
            if ($value !== '') {
                if ($keywords !== '') {
                    $keywords .= ' - ';
                }
                $keywords .= $key . ': ' . $value;
            }
        }
        if ($note !== '') {
            $keywords = $note . ' - ' . $keywords;
        }
        $vars = array(
            'note' => $keywords
        );
        self::log_event('search', $table, 0, $vars);
    }

    public static function damdownload($locationData, $note)
    {
        $table = 'dam';
        $parts = explode(':', $locationData);
        $record_id = intval(end($parts));
        $vars = array(
            'note' => $note
        );
        self::log_event('download', $table, $record_id, $vars);
    }

    public static function export($table, $note)
    {
        $vars = array(
            'note' => $note
        );
        self::log_event('export', $table, 0, $vars);
    }

    private static function log_event($action, $table, $record_id, $vars = array())
    {
        // t3lib_div::devlog('log_event', 'logging', 0, Array('$user' => $GLOBALS['TSFE']->fe_user));
        // get the site id
        if (array_key_exists('site_id', $vars)) {
            $site_id = $vars['site_id'];
        } else {
            $site_id = $GLOBALS['TSFE']->rootLine[0]['uid'];
        }
        // get the session_id
        if (array_key_exists('session_id', $vars)) {
            $session_id = $vars['session_id'];
        } else {
            $session_id = intval($GLOBALS['TSFE']->fe_user->getKey('ses', 'tx_logging:session'));
        }

        $fields = array(
            'action' => $action,
            'tablename' => $table,
            'record_id' => $record_id,
            'pid' => 0,
            'fe_user' => intval($GLOBALS['TSFE']->fe_user->user['uid']),
            'be_user' => $GLOBALS['BE_USER']->user['uid'],
            'crdate' => $GLOBALS['SIM_EXEC_TIME'],
            'site_id' => $site_id,
            'session_id' => $session_id,
            'http_referer' => t3lib_div::getIndpEnv('TYPO3_REQUEST_URL'),
            'params' => serialize(
                array('GET' => \TYPO3\CMS\Core\Utility\GeneralUtility::_GET(),
                    'POST' => \TYPO3\CMS\Core\Utility\GeneralUtility::_POST())
            ),
            'note' => $vars['note'],
            'ip_address' => t3lib_div::getIndpEnv('REMOTE_ADDR'),
        );
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_logging_domain_model_event', $fields);
    }

    /**
     * @param $msg
     * @param $extKey
     * @param int $severity Severity: 0 is info, 1 is notice, 2 is warning, 3 is fatal error, -1 is "OK" message
     * @param array $dataVar
     * @return    void
     */
    public static function devLog($msg, $extKey, $severity = 0, $dataVar = array())
    {
        $extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['logging']);

        $insertFields = array();
        $insertFields['pid'] = 0;
        $insertFields['crdate'] = time();
        $insertFields['be_user'] = $GLOBALS['BE_USER']->user['uid'];
        $insertFields['fe_user'] = intval($GLOBALS['TSFE']->fe_user->user['uid']);
        $insertFields['msg'] = strip_tags($msg);
        $insertFields['extkey'] = strip_tags($extKey);
        $insertFields['severity'] = intval($severity);

        // Try to get information about the place where this method was called from
        if (function_exists('debug_backtrace')) {
            $callPlaceInfo = self::getCallPlaceInfo(debug_backtrace());
            $insertFields['location'] = $callPlaceInfo['basename'];
            $insertFields['line'] = $callPlaceInfo['line'];
        }

        if (!empty($dataVar)) {
            if (is_array($dataVar)) {
                $serializedData = serialize($dataVar);
                $insertFields['data_var'] = $serializedData;
            } else {
                $insertFields['data_var'] = serialize(array('tx_devlog_error' => 'invalid'));
            }
        }

        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_logging_devlog', $insertFields);
    }

    /**
     * Given a backtrace, this method tries to find the place where a "devLog" function was called
     * and return info about the place
     *
     * @param    array $backTrace : function call backtrace, as provided by debug_backtrace()
     *
     * @return    array    information about the call place
     */
    private static function getCallPlaceInfo($backTrace)
    {
        foreach ($backTrace as $entry) {
            // if ($entry['class'] !== 'Tx_Logging_Logger' && $entry['function'] === 'devLog') {
            $pathInfo = pathinfo($entry['file']);
            $pathInfo['line'] = $entry['line'];
            return $pathInfo;
            // }
        }
        return null;
    }
}
