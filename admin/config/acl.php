<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @file
 *
 * Configuration for ACL permissions
 *
 */
$config['permission_allowed'] = array('add', 'edit', 'list', 'index', 'delete', 'changestatus');
$config['label_updates'] = array('index' => 'list', 'changestatus' => 'Status update');


$config['authetication_access'] = array(
    //Start Of Credential Module
    'credential' => array(
        'manage' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'site_credential_details' => 'id_site',
            ),
            'alternate_table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'site' => 'id_site',
            ),
        ),
    ),
    //End Of Credential Module
    //Start Of Scan Report Module
    'scan' => array(
        'report' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'scan' => 'id_scan',
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
        ),
        'schedule' => array(
            'value_to_get' => array('post' => array('value' => 'site', 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
            'after_post' => 1,
        ),
        'downloadScanResult' => array(
            'value_to_get' => array('post' => array('value' => 'si', 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'scan' => 'id_scan',
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
        ),
        'reportdetails' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'scan_netsparker_report' => 'id_scan_netsparker_report',
                'scan' => 'id_scan',
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
        ),
    ),
    //End Of Scan Report Module
    //Start Of Support Module
    'support' => array(
        'view' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'support_detail' => 'id_ticket',
            ),
            'alternate_table_to_check' => array(),
        ),
    ),
        //End Of Support Module
);

$config['authetication_access_api'] = array(
    //Start Of Credential Module
    'get_scan_status' => array(
        'get' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'scan' => 'id_scan',
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
        ),
    ),
    'schedule_scan' => array(
        'post' => array(
            'value_to_get' => array('post' => array('value' => 'id_site', 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
            'after_post' => 1,
        ),
    ),
    'delete_scan' => array(
        'delete' => array(
            'value_to_get' => array('segment' => array('value' => 3, 'is_encrypted' => 1)), // Segment OR Post => array(Segment No, Is Encrypted)
            'column_to_check' => 'id_customer', // Ultimate Column to match with session Id
            'table_to_check' => array(// Tables to check .. Multiple array will be cause left join default
                'scan' => 'id_scan',
                'site' => 'id_site',
            ),
            'alternate_table_to_check' => array(),
        ),
    ),
);
/* End of applications/config/acl.php */