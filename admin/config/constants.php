<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

define('PUBLIC_PATH', '../public/');
define('ASSETS_PATH', './assets/');
define('IMAGE_PATH', ASSETS_PATH . 'images/');
define('JS_PATH', ASSETS_PATH . 'js/');

define('CSS_PATH', ASSETS_PATH . 'css/');
define('PLUGINS_PATH', ASSETS_PATH . 'plugins/');
define('LIBRARY_PATH', ASSETS_PATH . 'library/');
define('COMPONENTS_PATH', ASSETS_PATH . 'components/');
define('COMPANY_NAME', 'TAILORWALA');

define('BASE_URL', "http://" . $_SERVER['HTTP_HOST'] . '/secure-ci-3.0/');


define('SESSION_VARIABLE','administrator');

define('TEMPLATES_PATH', 'templates/');
define('EMAIL_TEMPLATES_PATH', TEMPLATES_PATH . 'email/');
define('EMAIL_TEMPLATES_INCLUDES_PATH', EMAIL_TEMPLATES_PATH . 'includes/');
define('EMAIL_TEMPLATES_FILE', 'base_template');


define('COMPANY_EMAIL', 'sales@techdefencelabs.com');
define('COMPANY_NO', '+91-9712122122');
define('EMAIL_SUBJECT_MODERATER_ADD', 'Tailorwala Login Details');

define('RECORDS_PER_PAGE', 5);
define('EVENT_IMAGE_ALLOWED_TYPES', 'jpg|jpeg|png|gif');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');
define('EVENT_IMAGE_PATH', UPLOAD_PATH . 'event/');
define('EVENT_THUMB_IMAGE_PATH', EVENT_IMAGE_PATH . 'thumb/');
define('DEFAULT_THUMB_HEIGHT', 250);
define('DEFAULT_THUMB_WIDTH', 250);
define('EVENT_IMAGE_64_PATH', EVENT_IMAGE_PATH . '64x64/');
define('EVENT_IMAGE_128_PATH', EVENT_IMAGE_PATH . '128x128/');
define('EVENT_IMAGE_256_PATH', EVENT_IMAGE_PATH . '256x256/');
define('IMAGE_64_PATH', '64x64/');
define('IMAGE_128_PATH', '128x128/');
define('IMAGE_256_PATH', '256x256/');
define('MAIN_COMPANY_NAME', 'Tailorwala');
define('MAIN_COMPANY_URL', 'http://techdefencelabs.com');



define('DISPLAY_DATE_FORMAT', "d / m / Y   h:i:A");
define('WORKSHOP_TYPES', '3D,MAIN');
define('BARCODE_TABLE_COLS', '7');


/**
 * Custom Constants
 */
define('ENABLE_PROFILER', FALSE);

define('CONTENT_IMAGE_PATH', UPLOAD_PATH . 'content/');
define('CONTENT_ALLOWED_TYPES', 'doc|docx|pdf|txt|ppt|pptx');

//Queries System Config
define('SUPPORT_SYSTEM_URL', 'http://192.168.1.12/hacktrack/support/');
define('SUPPORT_SYSTEM_API_URL', SUPPORT_SYSTEM_URL . 'api/tickets.json');
//define('SUPPORT_SYSTEM_API_KEY', '2A3AD5C29886B566542C2A2B3663B1B1');
//define('SUPPORT_SYSTEM_API_KEY', '16165DDC9305A2F0B71FEFE738CD3329'); SERVER ONE
define('SUPPORT_SYSTEM_API_KEY', '0A5E2DED9EA1A74AB0D840051722553F');
define('SUPPORT_ALERT_FLAG', true);
define('SUPPORT_AUTORESPOND_FLAG', true);
define('SUPPORT_SOURCE_FLAG', "API");

/**
 * Result Constants
 */
define('RESULT_DOWNLOAD_PATH', PUBLIC_PATH . "uploads/");
define('ALLOWED_REPORT_FORMAT', 'pdf');



/** New Constants * */
define('AUTHKEY_TOSEND', 'taral@techdefence.com,hardik@techdefence.com');

define('CSRF_TOKEN_IGNORE_PATH', 'payment/success,cron/generatereport,scan/downloadScanResult,site/download');
define('CSRF_TOKEN_GENERATE_IGNORE_PATH', 'scan/downloadScanResult,site/site_save,site/download');
define('CSRF_TOKEN_API_IGNORE_PATH', 'api/*');

define('LOGIN_IGNORE_PATH', 'report/view');
define('CHECKUSERSESSION_IGNORE_PATH', 'cron/generatereport,error/error_404,forgotpassword/index');
define('CHECKUSERSESSION_API_IGNORE_PATH', 'api/*');

define('UNDER_MAINTAINCE', 0);
define('TWO_STEP_VARIFICATION', 1);

define('SEND_OTP_EMAIL', 1);
define('USER_PIN_EMAILS', 'taraloza.135@gmail.com');

define('SEND_OTP_SMS', 0);
/* End of file constants.php */
/* Location: ./application/config/constants.php */