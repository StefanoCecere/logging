<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Logging');
//t3lib_extMgm::addTypoScriptSetup('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:logging/Configuration/TypoScript/setup.txt">');

$TCA['tx_logging_domain_model_session'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:logging/Resources/Private/Language/locallang_db.xml:tx_logging_session',
        'label' => 'uid',
        'tstamp' => 'last_page_hit',
        'crdate' => 'session_login',
        'sortby' => 'last_page_hit',
        'default_sortby' => ' ORDER BY last_page_hit DESC',
        'hideTable' => true,
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Session.php',
    )
);

$TCA['tx_logging_domain_model_event'] = array(
    'ctrl' => array(
        'title' => 'LLL:EXT:logging/Resources/Private/Language/locallang_db.xml:tx_logging_event',
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'crdate',
        'default_sortby' => ' ORDER BY crdate DESC',
        'hideTable' => true,
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Event.php',
    )
);

/* ===========================================================================
	Register BE-Module
=========================================================================== */
if (TYPO3_MODE === 'BE') {
    if (!isset($TBE_MODULES['txloggingM1'])) {
        $temp_TBE_MODULES = array();
        foreach ($TBE_MODULES as $key => $val) {
            if ($key == 'web') {
                $temp_TBE_MODULES[$key] = $val;
                $temp_TBE_MODULES['txloggingM1'] = '';
            } else {
                $temp_TBE_MODULES[$key] = $val;
            }
        }
        $TBE_MODULES = $temp_TBE_MODULES;
        unset($temp_TBE_MODULES);
    }
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        $_EXTKEY,
        'txloggingM1',
        '',
        '',
        array(),
        array(
            'access' => 'user,group',
            'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod-reports.xml:module-group-title',
        )
    );
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        $_EXTKEY,
        'txloggingM1',
        'reports',
        '',
        array(
            'Reports' => 'listEvents,showEvent,listSessions,showSession',
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod-reports.xml',
        )
    );
}
