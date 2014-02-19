<?php
$ll = 'LLL:EXT:logging/Resources/Private/Language/locallang_db.xml:';

$TCA['tx_logging_domain_model_event'] = array(
    'ctrl' => $TCA['tx_logging_domain_model_event']['ctrl'],
    'types' => array(
        0 => array('showitem' => 'fe_user, session_id, site_id, action'),
    ),
    'columns' => array(
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
        'be_user' => array(
            'label' => $ll . 'tx_logging_event.be_user',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'session_id' => array(
            'label' => $ll . 'tx_logging_event.session_id',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'crdate' => array(
            'label' => $ll . 'tx_logging_session.crdate',
            'config' => array(
                'type' => 'input',
                'eval' => 'int,required',
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
        'action' => array(
            'label' => $ll . 'tx_logging_event.action',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'tablename' => array(
            'label' => $ll . 'tx_logging_event.tablename',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'record_id' => array(
            'label' => $ll . 'tx_logging_event.record_id',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'note' => array(
            'label' => $ll . 'tx_logging_event.note',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'http_referer' => array(
            'label' => $ll . 'tx_logging_event.http_referer',
            'config' => array(
                'type' => 'input',
            ),
        ),
        'params' => array(
            'label' => $ll . 'tx_logging_event.params',
            'config' => array(
                'type' => 'input',
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
