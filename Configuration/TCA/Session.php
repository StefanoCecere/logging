<?php
$ll = 'LLL:EXT:logging/Resources/Private/Language/locallang_db.xml:';

$TCA['tx_logging_domain_model_session'] = array(
    'ctrl' => $TCA['tx_logging_domain_model_session']['ctrl'],
    'interface' => Array(
        'maxDBListItems' => 60,
    ),
    'types' => array(
        0 => array('showitem' => 'fe_user;;;;1,session_login,last_page_hit,session_hit_counter'),
    ),
    'columns' => array(
        'crdate' => array(
            'label' => $ll . 'tx_logging_session.crdate',
            'config' => array(
                'type' => 'input',
                'eval' => 'int,required',
            ),
        ),
        'fe_user' => array(
            'label' => $ll . 'tx_logging_session.fe_user',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'fe_users',
                'minsize' => 1,
                'maxsize' => 1,
                'size' => 1,
            ),
        ),
        'site_id' => array(
            'label' => $ll . 'tx_logging_session.site_id',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'minsize' => 1,
                'maxsize' => 1,
                'size' => 1,
            ),
        ),
        'page_id' => array(
            'label' => $ll . 'tx_logging_session.page_id',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'minsize' => 1,
                'maxsize' => 1,
                'size' => 1,
            ),
        ),
        'ip_address' => array(
            'label' => $ll . 'tx_logging_session.ip_address',
            'config' => array(
                'type' => 'input',
                'max' => 15,
            ),
        ),
    ),
);
