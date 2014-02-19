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

/**
 * @package tx_logging
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Logging_Controller_ReportsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var Tx_Logging_Domain_Repository_EventRepository
     * @inject
     */
    protected $eventRepository;

    /**
     * @var Tx_Logging_Domain_Repository_SessionRepository
     * @inject
     */
    protected $sessionRepository;

    /**
     * @param Tx_Logging_Domain_Model_Dto_ReportsDemand $demand
     * @dontvalidate  $demand
     * @dontverifyrequesthash
     * @return void
     */
    public function listEventsAction(Tx_Logging_Domain_Model_Dto_ReportsDemand $demand = null)
    {
        $args = $this->request->getArguments();

        if (is_null($demand)) {
            $demand = $this->objectManager->get('Tx_Logging_Domain_Model_Dto_ReportsDemand');
        }

        $events = $this->eventRepository->findDemanded($demand);

        $this->view->assignMultiple(
            array(
                'demand' => $demand,
                'events' => $events
            )
        );

        if (!empty($args['download'])) {
            $this->exportCSVEvents($events);
        }
    }

    /**
     * @param Tx_Logging_Domain_Model_Event $event
     * @param Tx_Logging_Domain_Model_Dto_ReportsDemand $demand
     * @dontvalidate  $demand
     */
    public function showEventAction(Tx_Logging_Domain_Model_Event $event, Tx_Logging_Domain_Model_Dto_ReportsDemand $demand = null)
    {
        if (is_null($demand)) {
            $demand = $this->objectManager->get('Tx_Logging_Domain_Model_Dto_ReportsDemand');
        }

        $this->view->assignMultiple(
            array(
                'demand' => $demand,
                'event' => $event
            )
        );
    }

    /**
     * @param Tx_Logging_Domain_Model_Dto_ReportsDemand $demand
     * @dontvalidate  $demand
     * @dontverifyrequesthash
     * @return void
     */
    public function listSessionsAction(Tx_Logging_Domain_Model_Dto_ReportsDemand $demand = null)
    {
        $args = $this->request->getArguments();
        // \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('listSessionsAction', 'logging', 0, array('args' => $args));

        if (is_null($demand)) {
            $demand = $this->objectManager->get('Tx_Logging_Domain_Model_Dto_ReportsDemand');
        }

        $sessions = $this->sessionRepository->findDemanded($demand);

        $this->view->assignMultiple(
            array(
                'demand' => $demand,
                'sessions' => $sessions
            )
        );

        if (!empty($args['download'])) {
            $this->exportCSVSessions($sessions);
        }
    }

    /**
     * @param Tx_Logging_Domain_Model_Session $session
     * @return void
     */
    public function showSessionAction(Tx_Logging_Domain_Model_Session $session)
    {
        $args = $this->request->getArguments();

        $this->view->assign('session', $session);

        $events = $this->eventRepository->findBySession($session->getUid());
        $this->view->assign('events', $events);

        if (!empty($args['download'])) {
            $this->exportCSVEvents($events);
        }
    }

    /**
     * @param Tx_Logging_Domain_Model_Event[] $events
     * @return void
     */
    public function exportCSVEvents($events)
    {
        $csv_headers = array(
            'Date',
            'Hour',
            'Site',
            'User',
            'Username',
            'Activity',
            'Object',
            'Id',
            'Note',
            'Url',
        );
        $csv = array();
        foreach ($events as $event) {
            $csv[] = array(
                date("d-m-Y", $event->getCrdate()),
                date("H:i", $event->getCrdate()),
                $event->getSiteId()->getTitle(),
                ($event->getFeUser()) ? $event->getFeUser()->getFirstName() . ' ' . $event->getFeUser()->getLastName() : '',
                ($event->getFeUser()) ? $event->getFeUser()->getUsername() : '',
                $event->getAction(),
                $event->getTablename(),
                $event->getRecordId(),
                $event->getNote(),
                $event->getHttpReferer()
            );
        }
        $rowArr = array();
        $rows = array_merge(array($csv_headers), $csv);

        foreach ($rows as $row) {
            $rowArr[] = self::cleanString(t3lib_div::csvValues($row, ';'));
        }

        if (count($rowArr)) {
            $filename = 'logging_events_'
                . '_' . date("d-m-Y", time())
                . '_' . date("Hi", time())
                . '.csv';
            $mimeType = 'application/octet-stream';
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename=' . $filename);
            echo(implode(CRLF, $rowArr));
            exit;
        }
    }

    /**
     * @param Tx_Logging_Domain_Model_Session[] $sessions
     * @return void
     */
    public function exportCSVSessions($sessions)
    {
        $csv_headers = array(
            'Date',
            'Hour',
            'Site',
            'User',
            'Username'
        );
        $csv = array();
        foreach ($sessions as $session) {
            $csv[] = array(
                date("d-m-Y", $session->getCrdate()),
                date("H:i", $session->getCrdate()),
                $session->getSiteId()->getTitle(),
                ($session->getFeUser()) ? $session->getFeUser()->getFirstName() . ' ' . $session->getFeUser()->getLastName() : '',
                ($session->getFeUser()) ? $session->getFeUser()->getUsername() : '',
            );
        }
        $rowArr = array();
        $rows = array_merge(array($csv_headers), $csv);

        foreach ($rows as $row) {
            $rowArr[] = self::cleanString(t3lib_div::csvValues($row, ';'));
        }

        if (count($rowArr)) {
            $filename = 'logging_sessions'
                . '_' . date("d-m-Y", time())
                . '_' . date("Hi", time())
                . '.csv';
            $mimeType = 'application/octet-stream';
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename=' . $filename);
            echo(implode(CRLF, $rowArr));
            exit;
        }
    }

    /**
     * Clean a string
     *
     * @param $string
     * @return string
     */
    public function cleanString($string)
    {
        $quotes = array(
            "\xe2\x82\xac" => "\xc2\x80", /* EURO SIGN */
            "\xe2\x80\x9a" => "\xc2\x82", /* SINGLE LOW-9 QUOTATION MARK */
            "\xc6\x92" => "\xc2\x83", /* LATIN SMALL LETTER F WITH HOOK */
            "\xe2\x80\x9e" => "\xc2\x84", /* DOUBLE LOW-9 QUOTATION MARK */
            "\xe2\x80\xa6" => "\xc2\x85", /* HORIZONTAL ELLIPSIS */
            "\xe2\x80\xa0" => "\xc2\x86", /* DAGGER */
            "\xe2\x80\xa1" => "\xc2\x87", /* DOUBLE DAGGER */
            "\xcb\x86" => "\xc2\x88", /* MODIFIER LETTER CIRCUMFLEX ACCENT */
            "\xe2\x80\xb0" => "\xc2\x89", /* PER MILLE SIGN */
            "\xc5\xa0" => "\xc2\x8a", /* LATIN CAPITAL LETTER S WITH CARON */
            "\xe2\x80\xb9" => "\xc2\x8b", /* SINGLE LEFT-POINTING ANGLE QUOTATION */
            "\xc5\x92" => "\xc2\x8c", /* LATIN CAPITAL LIGATURE OE */
            "\xc5\xbd" => "\xc2\x8e", /* LATIN CAPITAL LETTER Z WITH CARON */
            "\xe2\x80\x98" => "\xc2\x91", /* LEFT SINGLE QUOTATION MARK */
            "\xe2\x80\x99" => "\xc2\x92", /* RIGHT SINGLE QUOTATION MARK */
            "\xe2\x80\x9c" => "\xc2\x93", /* LEFT DOUBLE QUOTATION MARK */
            "\xe2\x80\x9d" => "\xc2\x94", /* RIGHT DOUBLE QUOTATION MARK */
            "\xe2\x80\xa2" => "\xc2\x95", /* BULLET */
            "\xe2\x80\x93" => "\xc2\x96", /* EN DASH */
            "\xe2\x80\x94" => "\xc2\x97", /* EM DASH */
            "\xcb\x9c" => "\xc2\x98", /* SMALL TILDE */
            "\xe2\x84\xa2" => "\xc2\x99", /* TRADE MARK SIGN */
            "\xc5\xa1" => "\xc2\x9a", /* LATIN SMALL LETTER S WITH CARON */
            "\xe2\x80\xba" => "\xc2\x9b", /* SINGLE RIGHT-POINTING ANGLE QUOTATION */
            "\xc5\x93" => "\xc2\x9c", /* LATIN SMALL LIGATURE OE */
            "\xc5\xbe" => "\xc2\x9e", /* LATIN SMALL LETTER Z WITH CARON */
            "\xc5\xb8" => "\xc2\x9f" /* LATIN CAPITAL LETTER Y WITH DIAERESIS */
        );
        $string = strtr($string, $quotes);
        $string = utf8_decode($string);
        return $string;
    }

    /**
     * @param $value
     * @return void
     */
    public function cleanCSVfield($value)
    {
        return preg_replace('~[[:cntrl:]]~', '', utf8_decode($value));
    }

}
