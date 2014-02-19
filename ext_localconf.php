<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// TRACK FE users logins and page views
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['checkDataSubmission'][$_EXTKEY] = 'EXT:logging/Classes/Hooks/Tx_Logging_Hooks_Tsfe.php:Tx_Logging_Hooks_Tsfe';
