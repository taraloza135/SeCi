<?php

// Get Codeigniter instance 
$CI = get_instance();

/**
 * Function to print data
 * @param unknown $asData
 * @param string $ssFlag
 */
 
 
 function __pr(){
	 
 }
 
 
function _pr($asData, $ssFlag = '') {
    if ($ssFlag != '' && $ssFlag != '1') {
        echo "<pre>";
        print_r($asData);
        exit();
    } else {
        echo "<pre>";
        print_r($asData);
    }
}

/**
 * 
 * @param type $var
 * @return string
 */
function ifIsSet($var) {
    if (isset($var)) {
        return $var;
    } else {
        return "";
    }
}

/**
 * Function to set Field value
 * @param type $ssField
 * @param type $asData
 * @return type
 */
function field_value($ssField = "", $asData = array(), $ssEncrypted = 0) {
    if (trim($ssField) != "") {
        if (!empty($asData) != '' && sizeof($asData) > 0) {
            if ($ssEncrypted) {
                return (isset($asData[$ssField]) ? encrypt_decrypt('encrypt', $asData[$ssField]) : (isset($_POST[$ssField]) ? $_POST[$ssField] : ''));
            } else {
                return (isset($asData[$ssField]) ? $asData[$ssField] : (isset($_POST[$ssField]) ? $_POST[$ssField] : ''));
            }
        } else {
            return (isset($_POST[$ssField]) ? $_POST[$ssField] : '');
        }
    }
    return false;
}

/**
 * Function to generate array for options
 *
 * @param array $asData        	
 * @param string $snKey (for passing the table name)
 * @param array $fieldToAlter (array(PRIMARY_KEY_FIELD, TITLE_FIELD))
 * @return multitype:NULL unknown |multitype:
 */
function generate_options_array($asData = array(), $snKey = '', $fieldToAlter = array()) {
    $CI = $CI = &get_instance();
    $asOptions = array(
        '' => $CI->lang->line('select')
    );
    if (!empty($asData)) {
        foreach ($asData as $asValues) {
            if (count($fieldToAlter) > 0) {
                $asOptions[$asValues[$fieldToAlter[0]]] = ucfirst($asValues[$fieldToAlter[1]]);
            } else {
                $asOptions[$asValues['id_' . $snKey]] = ucfirst($asValues[$snKey . '_name']);
            }
        }
    }
    return $asOptions;
}

/**
 * Function to set flash messages
 * @param type $ssFlashType
 * @param type $ssMessage
 * @return boolean
 */
function set_flash($ssFlashType = "", $ssMessage = "") {
    $CI = &get_instance();
    if (trim($ssFlashType) != '' && trim($ssMessage) != '') {
        $CI->session->set_flashdata($ssFlashType, $ssMessage);
        return true;
    }
    return false;
}

/**
 * To download the file directly without saving it
 * @param type $fileName
 * @param type $fileExtension
 * @param type $contentToPut [Data we would like to put inside file]
 * @return true
 */
function download_file($fileName = '', $fileExtension = '', $contentToPut = "", $testCase = 0) {
    if (trim($fileName) != "" && trim($fileExtension) != "") {
        $data = $contentToPut;
        $name = $fileName . '.' . $fileExtension;
        if (!$testCase) {
            force_download($name, $data);
        }
        return true;
    }
    return false;
}

/**
 * Show Display Status button 
 * @param type $status
 * @return boolean|string
 */
function display_status($status = '') {
    if (trim($status) != "") {
        $divToDisplay = '<button class="btn btn-danger btn-xs" href="#">Disabled</button>';
        if ($status) {
            $divToDisplay = '<button class="btn btn-success btn-xs" href="#">Enabled</button>';
        }
        return $divToDisplay;
    }
    return false;
}

/**
 * Show Display Verified button 
 * @param type $status
 * @return boolean|string
 */
function display_verified($status = '') {
    if (trim($status) != "") {
        $divToDisplay = '<button class="btn btn-danger btn-xs" href="#">Unverified</button>';
        if ($status) {
            $divToDisplay = '<button class="btn btn-success btn-xs" href="#">Verified</button>';
        }
        return $divToDisplay;
    }
    return false;
}

//--------------------------------------------------------------------
// Module Functions
//--------------------------------------------------------------------

if (!function_exists('module_folders')) {

    /**
     * Returns an array of the folders that modules are allowed to be stored in.
     * These are set in *bonfire/application/third_party/MX/Modules.php*.
     *
     * @return array The folders that modules are allowed to be stored in.
     */
    function module_folders() {
        return array_keys(modules::$locations);
    }

}

//--------------------------------------------------------------------

if (!function_exists('module_list')) {

    /**
     * Returns a list of all modules in the system.
     *
     * @param bool $exclude_core Whether to exclude the Bonfire core modules or not
     *
     * @return array A list of all modules in the system.
     */
    function module_list($exclude_core = false) {
        if (!function_exists('directory_map')) {
            $ci = & get_instance();
            $ci->load->helper('directory');
        }

        $map = array();

        foreach (module_folders() as $folder) {
            // If we're excluding core modules and this module
            // is in the core modules folder... ignore it.
            if ($exclude_core && strpos($folder, 'bonfire/modules') !== false) {
                continue;
            }

            $dirs = directory_map($folder, 1);
            if (!is_array($dirs)) {
                $dirs = array();
            }

            $map = array_merge($map, $dirs);
        }

        // Clean out any html or php files
        if ($count = count($map)) {
            for ($i = 0; $i < $count; $i++) {
                if (strpos($map[$i], '.html') !== false || strpos($map[$i], '.php') !== false) {
                    unset($map[$i]);
                }
            }
        }

        return $map;
    }

}

//--------------------------------------------------------------------

if (!function_exists('module_controller_exists')) {

    /**
     * Determines whether a controller exists for a module.
     *
     * @param $controller string The name of the controller to look for (without the .php)
     * @param $module string The name of module to look in.
     *
     * @return boolean
     */
    function module_controller_exists($controller = null, $module = null) {
        if (empty($controller) || empty($module)) {
            return false;
        }

        // Look in all module paths
        foreach (module_folders() as $folder) {
            if (is_file($folder . $module . '/controllers/' . $controller . '.php')) {
                return true;
            }
        }

        return false;
    }

}

//--------------------------------------------------------------------

if (!function_exists('module_file_path')) {

    /**
     * Finds the path to a module's file.
     *
     * @param $module string The name of the module to find.
     * @param $folder string The folder within the module to search for the file (ie. controllers).
     * @param $file string The name of the file to search for.
     *
     * @return string The full path to the file.
     */
    function module_file_path($module = null, $folder = null, $file = null) {
        if (empty($module) || empty($folder) || empty($file)) {
            return false;
        }

        foreach (module_folders() as $module_folder) {
            $test_file = $module_folder . $module . '/' . $folder . '/' . $file;

            if (is_file($test_file)) {
                return $test_file;
            }
        }
    }

}

//--------------------------------------------------------------------

if (!function_exists('module_path')) {

    /**
     * Returns the path to the module and it's specified folder.
     *
     * @param $module string The name of the module (must match the folder name)
     * @param $folder string The folder name to search for. (Optional)
     *
     * @return string The path, relative to the front controller.
     */
    function module_path($module = null, $folder = null) {
        foreach (module_folders() as $module_folder) {
            if (is_dir($module_folder . $module)) {
                if (!empty($folder) && is_dir($module_folder . $module . '/' . $folder)) {
                    return $module_folder . $module . '/' . $folder;
                } else {
                    return $module_folder . $module . '/';
                }
            }
        }
    }

}

//--------------------------------------------------------------------

if (!function_exists('module_files')) {

    /**
     * Returns an associative array of files within one or more modules.
     *
     * @param $module_name string If not NULL, will return only files from that module.
     * @param $module_folder string If not NULL, will return only files within that folder of each module (ie 'views')
     * @param $exclude_core boolean Whether we should ignore all core modules.
     *
     * @return array An associative array, like: array('module_name' => array('folder' => array('file1', 'file2')))
     */
    function module_files($module_name = null, $module_folder = null, $exclude_core = false) {
        if (!function_exists('directory_map')) {
            $ci = & get_instance();
            $ci->load->helper('directory');
        }

        $files = array();

        foreach (module_folders() as $path) {
            // If we're ignoring core modules and we find the core module folder... skip it.
            if ($exclude_core === true && strpos($path, 'bonfire/modules') !== false) {
                continue;
            }

            if (!empty($module_name) && is_dir($path . $module_name)) {
                $path = $path . $module_name;
                $modules[$module_name] = directory_map($path);
            } else {
                $modules = directory_map($path);
            }

            // If the element is not an array, we know that it's a file,
            // so we ignore it, otherwise it is assumbed to be a module.
            if (!is_array($modules) || !count($modules)) {
                continue;
            }

            foreach ($modules as $mod_name => $values) {
                if (is_array($values)) {
                    // Add just the specified folder for this module
                    if (!empty($module_folder) && isset($values[$module_folder]) && count($values[$module_folder])) {
                        $files[$mod_name] = array(
                            $module_folder => $values[$module_folder],
                        );
                    }
                    // Add the entire module
                    elseif (empty($module_folder)) {
                        $files[$mod_name] = $values;
                    }
                }
            }
        }

        return count($files) ? $files : false;
    }

}

/**
 * 
 */
function get_module_permission_list() {
    $CI = get_instance();
    $asModuleList = module_list();

    $asClass = array();
    $asClassMethods = array();

    $asAllowPermission = $CI->load->config('acl', TRUE);


    foreach ($asModuleList as $ssModule) {
        $asModuleFiles = module_files($ssModule, 'controllers');
        $ssModulePath = module_path() . $ssModule;

        if (is_dir(module_path() . $ssModule)) {
            // Get name of directory
            $dirname = basename($ssModulePath, EXT);
            // Loop through the subdirectory
            foreach (glob(APPPATH . 'modules/' . $dirname . '/controllers/*') as $subdircontroller) {
                // Get the name of the subdir
                $subdircontrollername = basename($subdircontroller, EXT);

                $asClass[$subdircontrollername] = $subdircontrollername;
                // Load the controller file in memory if it's not load already
                if (!class_exists($subdircontrollername)) {
                    $CI->load->file($subdircontroller);
                }
                // Add the controllername to the array with its methods
                $aMethods = get_class_methods($subdircontrollername);

                $aUserMethods = array();
                $i = 0;
                foreach ($aMethods as $method) {
                    if (in_array($method, $asAllowPermission['permission_allowed'])) {
                        $aUserMethods[$i]['prev'] = $method;
                        if (array_key_exists($method, $asAllowPermission['label_updates'])) {
                            $aUserMethods[$i]['label'] = $asAllowPermission['label_updates'][$method];
                        } else {
                            $aUserMethods[$i]['label'] = $method;
                        }
                        $i++;
                    }
                }
                $asClassMethods[$subdircontrollername] = $aUserMethods;
            }
        }
    }
    return $asClassMethods;
}

/**
 * Function to verify the site exists with given file name
 * @param string Site Url
 * @param string key [xyzabc.html]
 * @return boolean [If site verified then TRUE else FALSE]
 */
if (!function_exists('verify_site_using_file')) {

    /* function verify_site_using_file($site_url, $key = "")
      {
      $exists = false;
      if (trim($site_url) != "" && check_if_entered_site_is_valid($site_url) && trim($key) != "")
      {
      $file = 'http://' . $site_url . '/' . $key . "." . DOWNLOAD_SITE_FILENAME_EXTENSION;
      if (check_if_site_exist($file))
      {
      return array('status' => true, 'reason' => 'File Verified and Authenticated');
      }
      else
      {
      $file = 'https://' . $site_url . '/' . $key . "." . DOWNLOAD_SITE_FILENAME_EXTENSION;
      if (check_if_site_exist($file))
      {
      return array('status' => true, 'reason' => 'File Verified and Authenticated');
      }
      return array('status' => false, 'reason' => 'Unable to locate site OR file !!');
      }
      }
      return array('status' => false, 'reason' => 'Unable To locate the file !!');
      } */

    function verify_site_using_file($site_url = '', $key = "") {
        $exists = false;
        if (trim($site_url) != "" && check_if_entered_site_is_valid($site_url) && trim($key) != "") {
            $file = 'http://' . $site_url . '/' . $key . ".html";
            $file_headers = get_headers($file);
            $exists = false;
            if ($file_headers) {
                if (in_array('HTTP/1.1 200 OK', $file_headers)) {
                    $html = file_get_contents($file);
                    if (trim($html) == 'AppDefenceweb file verification')
                        return array('status' => true, 'reason' => 'File Verified and Authenticated');
                }
            } else {
                $file = 'https://' . $site_url . '/' . $key . ".html";
                $file_headers = get_headers($file);
                if ($file_headers) {
                    if (in_array('HTTP/1.1 200 OK', $file_headers)) {
                        $html = file_get_contents($file);
                        if (trim($html) == 'AppDefenceweb file verification')
                            return array('status' => true, 'reason' => 'File Verified and Authenticated');
                    }
                }
            }
            return array('status' => false, 'reason' => 'Unable To locate the file !!');
        }
        return array('status' => false, 'reason' => 'Unable To locate the file !!');
    }

}

if (!function_exists('check_if_site_exist')) {

    function check_if_site_exist($url = "") {
        if ($url == NULL)
            return false;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }

}


if (!function_exists('verify_site_whois')) {

    /**
     * Function to verify site via whois email address
     * @param type $site_url
     * @param type $email
     * @return type
     */
    function verify_site_whois($site_url = '', $email = "") {
        if (trim($site_url) != "") {
            $CI = get_instance();

            $CI->load->library('domain', $site_url);
            $whois = new Domain($site_url);
            // Get the pattern with Admin Email : <Anything will be listed here>
            $stringToSearch = "Admin Email:";
            if (isset($whois->data)) {
                preg_match('/(?<=' . $stringToSearch . ').*/i', $whois->data, $matches);
                if (count($matches) > 0)
                    $whoisAdminEmail = $matches[0];
                else
                    $whoisAdminEmail = "";

                if (trim($whoisAdminEmail) != "") {
                    $privacyProtectedPattern = "privacyprotect";
                    preg_match('/' . $privacyProtectedPattern . '.*/i', $whoisAdminEmail, $matches);
                    if (count($matches) > 0) {
                        return array('status' => false, 'reason' => "Privacy Policy detected !! Please use another method to validate it !!");
                    } else {
                        if (trim($email) != "") {
                            if (trim($whoisAdminEmail) != trim($email)) {
                                return array('status' => false, 'reason' => "Email is not verified with WHO.IS one !!");
                            }
                            return array('status' => true, 'reason' => 'Verified Your Email');
                        } else {
                            return array('status' => false, 'reason' => "Email is not verified with WHO.IS one !!");
                        }
                    }
                    return array('status' => true, 'reason' => 'Verified Your Email');
                } else {
                    return array('status' => false, 'reason' => 'Unable to Match Your Email address using whois');
                }
            }
        }
    }

}

if (!function_exists('get_admin_email_whois')) {

    function get_admin_email_whois($site_url = '') {
        if (trim($site_url) != "") {
            $CI = get_instance();
            if ($CI->load->library('domain', $site_url)) {

                $whois = new Domain();
                $asContent = $whois->getWhoisContent($site_url);
                // Get the pattern with Admin Email : <Anything will be listed here>
                if (trim($asContent) != "") {
                    $stringToSearch = "Admin Email:";
                    preg_match('/(?<=' . $stringToSearch . ').*/i', $asContent, $matches);
                    if (count($matches) > 0)
                        $whoisAdminEmail = $matches[0];
                    else
                        $whoisAdminEmail = "";

                    return $whoisAdminEmail;
                }else {
                    return false;
                }
            }
            return false;
        }
        return false;
    }

}

/**
 * Verify the string of the site
 */
function check_if_entered_site_is_valid($site_url) {
    return true;
}

if (!function_exists('send_Email')) {

    /**
     * Function to send an email 
     * @param type $ssTo
     * @param type $ssSubject
     * @param type $asMessage
     * @return boolean
     */
    function send_Email($ssTo = '', $ssSubject = '', $asMessage = '') {
        if ($ssTo != '' && $ssSubject != '' && $asMessage != '') {
            $CI = get_instance();
            $CI->email->from(COMPANY_EMAIL, COMPANY_NAME);
            $CI->email->to($ssTo);
            $CI->email->reply_to('support@techdefence.com', 'Support Team');
            $CI->email->subject($ssSubject);
            $CI->email->message($CI->load->view('templates/email/base_template', $asMessage, TRUE));
            $CI->email->send();
            return true;
            //echo $CI->email->print_debugger();exit();
        }
        return false;
    }

}


if (!function_exists('generate_email_content')) {


    /**
     * Function to generate email content
     * @param type $ssEmailTemplate
     * @param type $asParseData
     * @return type
     */
    function generate_email_content($ssEmailTemplate = '', $asParseData = array()) {
        $CI = get_instance();
        $CI->load->library('parser');
        $asData = array();
        if ($ssEmailTemplate != '' && !empty($asParseData) && sizeof($asParseData) > 0) {
            return $asData = array('CONTENT' => $CI->parser->parse(EMAIL_TEMPLATES_PATH . $ssEmailTemplate, $asParseData, TRUE));
        }
        return $asData;
    }

}

function generateKey() {
    return "t3C5D4fencel48s";
}

/**
 * 
 * @param type $string
 * @return type
 */
function encryptData($string = '') {
    $key = generateKey();
    $iv = md5($key);
    $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
    return base64_encode($output);
}

/**
 * 
 * @param type $string
 * @return type
 */
function decryptData($string = '') {
    $key = generateKey();
    $iv = md5($key);
    $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
    return rtrim($output, "");
}

/**
 * To update the special character with defined one
 * @param type $string
 */
function updateSpecialCharactersWithAnother($string = "", $isReverse = 0) {
    $findArray = array('/', '+');
    $replaceArray = array('^', '~');
    if (trim($string) != "") {
        if ($isReverse)
            return str_replace($replaceArray, $findArray, $string);
        else
            return str_replace($findArray, $replaceArray, $string);
    }
    return false;
}

/**
 * Function that will return the current customer session data
 * @param type $param (which element want to retrive)
 * @return int
 */
function get_current_customer_session_data($param = 'id', $isEncrypted = 0) {
    $CI = &get_instance();
    $authValues = $CI->session->userdata(SESSION_VARIABLE);
    if (count($authValues) > 0 && isset($authValues[$param])) {
        if ($isEncrypted)
            return trim(decryptData($authValues[$param]));
        else
            return trim($authValues[$param]);
    }else {
        return false;
    }
}

/**
 * Get all available timezones
 */
function getAllAvailableTimeZones() {
    $asTzlist = DateTimeZone::listIdentifiers();

    print "<pre>";
    print_r($asTzlist);
    exit();
}

/**
 * Function to get status Label
 * @param type $status
 * @return type
 */
function status_label($status = '0') {
    if (trim($status) != "") {
        $CI = get_instance();
        return $ssStatusLabel = (($status == '1') ? $CI->lang->line('enable') : $CI->lang->line('disable'));
    }
    return false;
}

/**
 * Function to get status Label
 * @param type $status
 * @return type
 */
function status_support_label($status = 'closed') {
    if (trim($status) != "") {
        $CI = get_instance();
        return $ssStatusLabel = (($status == 'closed') ? $CI->lang->line('closed') : $CI->lang->line('open'));
    }
    return false;
}

/**
 * Function to get status class 
 * @param type $status
 * @return type
 */
function status_class($status = '0') {
    if (trim($status) != "") {
        return $ssStatusClass = (($status == '0') ? "btn-danger" : "btn-success");
    }
    return false;
}

/**
 * Function to get status class 
 * @param type $status
 * @return type
 */
function status_support_class($status) {
    return $ssStatusClass = (($status == 'closed') ? "btn-danger" : "btn-success");
}

/**
 * 
 * @param type $ssDate
 * @return boolean
 */
function display_date_format($ssDate = '', $ssFormat = '') {

    $date = date_parse($ssDate);
    if ($date["error_count"] == 0 && checkdate($date["month"], $date["day"], $date["year"])) {
        if ($ssFormat != '' && $ssDate != '')
            return date($ssFormat, strtotime($ssDate));
        if ($ssDate != '')
            return date(DISPLAY_DATE_FORMAT, strtotime($ssDate));
        else
            return false;
    } else
        return false;
}

if (!function_exists('validateTokenURL')) {

    /**
     * 
     * @param type $segmentNumber
     * @param type $redirectUrl
     * @return type
     */
    function validateTokenURL($segmentNumber = 4, $redirectUrl = "") {
        $CI = get_instance();

        $currentLocation = current_url();
        $locationArray = explode("/", $currentLocation);
        array_pop($locationArray); //removes last
        $currentUrl = implode("/", $locationArray);

        $referelLocation = $CI->agent->referrer();
        $referrerArray = explode("/", $referelLocation);
        array_pop($referrerArray); //removes last
        $referrerUrl = implode("/", $referrerArray);
        $flashToken = FALSE;

        //If current and refferal URL are equal then Do not check token.
        if ($referrerUrl == $currentUrl) {
            return;
        }
        if ($CI->uri->segment($segmentNumber) != $CI->session->userdata('token')) {
            set_flash('error', $CI->lang->line('error_something_wrong'));
            redirect($redirectUrl);
        }
        return;
    }

}


if (!function_exists('convertCurrentTimeToGmt')) {

    /**
     * Converting given local time to GMT based on given timestampString parameter
     * @param type $segmentNumber
     * @param type $redirectUrl
     * @return type
     */
    function convertCurrentTimeToGmt($currentTime = "", $timeString = "") {
        if ($timeString != "UTC") {
            $mode = substr($timeString, 1, 1);
            $timeDifferenceString = substr($timeString, 2, strlen($timeString));
            $hours = substr($timeDifferenceString, 0, 1);
            //$hours = $hours + 1;
            $minutesHours = substr($timeDifferenceString, 1, strlen($timeDifferenceString));
            switch ($minutesHours) {
                case 75:
                    $minutes = 45;
                    break;
                case 5:
                    $minutes = 30;
                    break;
                case 25:
                    $minutes = 15;
                    break;
                default:
                    $minutes = 00;
                    break;
            }
            $sign = ($mode == 'P') ? "-" : "+";
            return date("Y-m-d H:i:s", strtotime("$currentTime $sign $hours hour $sign $minutes minute"));
            //strtotime("$currentTime $sign ");
        } else {
            return date("Y-m-d H:i:s", strtotime("$currentTime"));
        }
    }

}

/**
 * 
 * @param type $idCustomer
 */
function checkPaymentDue($idCustomer) {
    return true;
}

/**
 * Function to call payment method of icici payseal
 * @param array $asOrderDetails
 * @param array $asCustomerDetails
 */
function iciciPaySealPayment($asOrderDetails = array(), $asCustomerDetails = array()) {
    $CI = & get_instance();
    $asOrder = array();
    $asBillingDetails = array();
    $asShippingDetails = array();

    if (is_array($asOrderDetails) && sizeof($asCustomerDetails) > 0 && is_array($asCustomerDetails) && sizeof($asCustomerDetails) > 0) {
        // Order details array
        $asOrder['amount'] = $asOrderDetails['package_price'];
        $asOrder['details'] = $asOrderDetails['package_name'];

        // Billing details

        $asBillingDetails = array(
            'CID' => $asCustomerDetails['id_customer'],
            'customer_name' => $asCustomerDetails['first_name'] . " " . $asCustomerDetails['last_name'],
            'address_1' => $asCustomerDetails['address1'],
            'address_2' => $asCustomerDetails['address2'],
            'address_3' => '',
            'city' => $asCustomerDetails['city'],
            'state' => $asCustomerDetails['state'],
            'zipcode' => $asCustomerDetails['zip'],
            'country' => $asCustomerDetails['country'],
            'email' => $asCustomerDetails['email']
        );

        $asShippingDetails = array(
            'address_1' => $asCustomerDetails['address1'],
            'address_2' => $asCustomerDetails['address2'],
            'address_3' => '',
            'city' => $asCustomerDetails['city'],
            'state' => $asCustomerDetails['state'],
            'zipcode' => $asCustomerDetails['zip'],
            'country' => $asCustomerDetails['country'],
            'email' => $asCustomerDetails['email']
        );

        $CI->load->library('payseal');
        $CI->payseal->payseal_ssl($asOrder, $asBillingDetails, $asShippingDetails);
    } else {
        set_flash('error', $CI->lang->line('error_incorrect_information_provided'));
        redirect(site_url('upgrade'));
    }
}

if (!function_exists('calculate_percentage')) {

    /**
     * Function to calculate percentage
     * @param numeric $snTotal
     * @param numeric $snVal
     * @return boolean / numeric
     */
    function calculate_percentage($snTotal = '', $snVal = 0) {
        $snPercentage = 0;
        if ($snTotal != '' && $snVal != '' && is_numeric($snTotal) && is_numeric($snVal)) {
            $snPercentage = round(($snVal * 100) / $snTotal);
            return $snPercentage;
        }
        return $snPercentage;
    }

}


if (!function_exists('check_payment_response')) {

    /**
     * 
     * @param type $ssPaymentMethod
     * @param type $asResponse
     * @return boolean
     */
    function check_payment_response($ssPaymentMethod, $asResponse = array()) {
        $CI = & get_instance();
        $asResult = array();
        if ($ssPaymentMethod != '' && is_array($asResponse) && sizeof($asResponse)) {
            switch ($ssPaymentMethod) {
                case 'payseal':
                    $asResult = payseal_response($asResponse);
                    break;
                case 'paypal':
                    $asResult = paypal_response($asResponse);
                    break;
                default :
                    $asResult = payseal_response($asResponse);
                    break;
            }


            if (is_array($asResult) && sizeof($asResult) > 0) {
                if (isset($asResult['ssFlag']) && $asResult['ssFlag'] == TRUE) {
                    set_flash('success', $asResult['message']);
                    return TRUE;
                } else {
                    set_flash('error', $asResult['message']);
                    return FALSE;
                }
            }
        }
    }

}

if (!function_exists('payseal_response')) {

    /**
     * Function to set message for payment response
     * @param array $asResponse
     * @return array
     */
    function payseal_response($asResponse = array()) {
        $CI = & get_instance();
        $ssFlag = FALSE;
        $asResult = array();
        if (is_array($asResponse) && sizeof($asResponse) > 0) {
            if (isset($asResponse['response_code'])) {
                // $asResponse['response_code'] = '0';
                if ($asResponse['response_code'] == '0') {
                    $asResult['ssFlag'] = TRUE;
                    $asResult['message'] = 'Your payment has successfull, Package has been updated';
                    return $asResult;
                }
                if ($asResponse['response_code'] == '1') {
                    $asResult['ssFlag'] = FALSE;
                    $asResult['message'] = 'Your transaction is unsuccessfull, rejected by bank';
                }
                if ($asResponse['response_code'] == '2') {
                    $asResult['ssFlag'] = FALSE;
                    $asResult['message'] = 'Your transaction is unsuccessfull, Invalide details or card number';
                }

                return $asResult;
            }
        }
        return $asResult;
    }

}


if (!function_exists('json_to_html')) {

    /**
     * Function convert json to html
     * @param string $ssJsonData
     * @return string
     */
    function json_to_html($ssJsonData = '') {
        if (trim($ssJsonData) != "") {
            $CI = & get_instance();
            $ssFlag = FALSE;
            $ssHtml = '';
            if (isJSON($ssJsonData)) {
                $ssJsonData = json_decode($ssJsonData);
                $ssHtml.= '<table>';
                foreach ($ssJsonData as $ssKey => $ssVal) {
                    $ssHtml .= '<tr>';
                    $ssHtml .= '<td style="text-align:right; background:#FFFFFF;font-weight:bold;vertical-align:top;">' . $CI->lang->line($ssKey) . ' </td>';
                    $ssHtml .= '<td style="text-align:left; background:#FFFFFF;">' . $ssVal . ' </td>';
                    $ssHtml .= '</tr>';
                }
                $ssHtml.= '</table>';
            }
            return $ssHtml;
        }
        return false;
    }

}

function isJSON($string = '') {
    return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

/**
 * Function to encrypt and decrypt data
 * @param string $action
 * @param string $string
 * @return string
 */
function encrypt_decrypt($action = '', $string = '') {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = '0123456789';
    $secret_iv = '9876543210';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    } else {
        return $string;
    }

    return $output;
}

/**
 * Function to get Color
 * @param string $ssSeverity
 * @return string
 */
function getSeverityColor($ssSeverity = '') {
    if ($ssSeverity != '') {
        $ssColor = 'gray';
        switch ($ssSeverity) {
            case 'critical':
                $ssColor = 'severity_critical';
                break;
            case 'important':
                $ssColor = 'severity_important';
                break;
            case 'medium':
                $ssColor = 'severity_medium';
                break;
            case 'low':
                $ssColor = 'severity_low';
                break;
            case 'information':
                $ssColor = 'severity_information';
                break;
        }
        return $ssColor;
    }
    return false;
}

/**
 * Function to get Color
 * @param string $ssSeverity
 * @return string
 */
function getSeverityColorUpdated($ssSeverity = '') {
    if ($ssSeverity != '') {
        $ssColor = 'gray';
        switch ($ssSeverity) {
            case 'critical':
                $ssColor = '#ff0000';
                break;
            case 'important':
                $ssColor = '#ff491c';
                break;
            case 'medium':
                $ssColor = '#f90';
                break;
            case 'low':
                $ssColor = '#f3d70c';
                break;
            case 'information':
                $ssColor = '#449D44';
                break;
        }
        return $ssColor;
    }
}

if (!function_exists('updateSpecialCharactersWithAnother')) {

    /**
     * To update the special character with defined one
     * @param type $string
     * @param boleen $isReverse 
     */
    function updateSpecialCharactersWithAnother($string = "", $isReverse = 0) {
        $findArray = array('/', '+');
        $replaceArray = array('-', '~');
        if (trim($string) != "") {
            if ($isReverse)
                return str_replace($replaceArray, $findArray, $string);
            else
                return str_replace($findArray, $replaceArray, $string);
        }
    }

}

/**
 * Function to encrypt the given data for security reason
 * @param string $ssDataToEncrypt
 * @param int $snSupportToUrl  If this is true then encrypted data will going to be compatible for URL or parameter
 * @return type
 */
function encryptDataV2($ssDataToEncrypt = "", $snSupportToUrl = 0) {
    $ssReturnString = encryptData($ssDataToEncrypt);
    if ($snSupportToUrl) {
        $ssReturnString = updateSpecialCharactersWithAnother($ssReturnString);
    }
    return $ssReturnString;
}

/**
 * Function to decrypt the given data for security reason
 * @param string $ssDataToDecrypt
 * @param int $snSupportToUrl  If this is true then encrypted data will going to be compatible for URL or parameter
 * @return type
 */
function decryptDataV2($ssDataToDecrypt = "", $snSupportToUrl = 0) {
    $ssReturnString = $ssDataToDecrypt;
    if ($snSupportToUrl) {
        $ssReturnString = updateSpecialCharactersWithAnother($ssReturnString, 1);
        $ssReturnString = rawurldecode($ssReturnString);
    }
    //echo $ssReturnString;exit();
    $ssReturnString = decryptData($ssReturnString);
    return trim($ssReturnString);
}

function getIconByStatus($ssStatus = '') {
    if (trim($ssStatus) != "") {

        switch ($ssStatus) {
            case 'scheduled':
                echo "<a href='#' class='pattern tipB' title='Scheduled' data-hasqtip='true' aria-describedby='qtip-6'><span class='icomoon-icon-clock-3 icon32 red'></span></a>";
                break;
            case 'start':
                echo "<a href='#' class='pattern tipB' title='Started' data-hasqtip='true' aria-describedby='qtip-6'><span class='icomoon-icon-clock-3 icon32 green'></span></a>";
                break;
            case 'in_progress':
                echo "<a href='#' class='pattern tipB' title='In Process' data-hasqtip='true' aria-describedby='qtip-6'><span class='icomoon-icon-spinner-7 icon32 blue'></span></a>";
                break;
            case 'finish':
                echo "<a href='#' class='pattern tipB' title='Finished' data-hasqtip='true' aria-describedby='qtip-6'><span class='icomoon-icon-flag-2 icon32 green'></span></a>";
                break;
            default:
                echo "<a href='#' class='pattern tipB' title='Scheduled' data-hasqtip='true' aria-describedby='qtip-6'><span class='icomoon-icon-clock-3 icon32 red'></span></a>";
                break;
        }
        return true;
    }
    return false;
}

/**
 * 
 * @param type $ssString
 * @return type
 */
function replaceNetsparkerWordsByAppdefence($ssString = '') {
    if (trim($ssString) != "") {
        $ssString = str_replace(array('NETSPARKER', 'Netsparker Cloud', 'netsparker cloud', 'Netsparker', 'netsparker'), array('Appdefence', 'Appdefence Web', 'Appdefence Web', 'Appdefence', 'appdefence'), $ssString);
        return $ssString;
    }
    return false;
}

/**
 * Function to check subscription status
 */
function checkSubscription() {
    $asCustomerTableRecord = array();
    $CI = & get_instance();
    $snIdCustomer = get_current_customer_session_data('id', 1);
    $CI->db->select('created_at');
    $CI->db->where('id_customer', $snIdCustomer);
    $CI->db->order_by('id_customer_transaction', 'DESC');
    $CI->db->limit(1);
    $ssQuery = $CI->db->get('customer_transaction');
    // If customer transaction record found then pick that one
    if ($ssQuery->num_rows() > 0) {
        $asCustomerTableRecord = $ssQuery->row_array();
    } else {
        // Check in customer table for created date and compare it with current date
        $CI->db->select('created_at');
        $CI->db->where('id_customer', $snIdCustomer);
        $CI->db->limit(1);
        $ssQuery = $CI->db->get('customer');
        $asCustomerTableRecord = $ssQuery->row_array();
    }
    if (count($asCustomerTableRecord) > 0) {
        $ssCurrentDate = date('Y-m-d H:i:s');
        $ssCustomerExpirationDate = date("Y-m-d H:i:s", strtotime("+" . RENEW_PERIOD, strtotime($asCustomerTableRecord['created_at'])));
        if ($ssCurrentDate <= $ssCustomerExpirationDate) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    return false;
}

/**
 * 
 * @param type $ssSiteUrlToRedirect
 */
function subscription_checker($snSubscriptionStatus = 0, $ssSiteUrlToRedirect = '') {
    $CI = & get_instance();
    if (!$snSubscriptionStatus) {
        set_flash('error', $CI->lang->line('subscription_expired'));
        redirect($ssSiteUrlToRedirect);
    }
    return;
}

/**
 * Function to get Color
 * @param string $ssStatus
 * @return string
 */
function getStatusColor($ssStatus) {
    if ($ssStatus != '') {
        $ssColor = '';
        switch ($ssStatus) {
            case 'scheduled':
                $ssColor = 'text-info';
                break;
            case 'in_progress':
                $ssColor = 'text-warning';
                break;
            case 'start':
                $ssColor = 'text-danger';
                break;
            case 'finish':
                $ssColor = 'text-success';
                break;
        }
        return $ssColor;
    }
}

/**
 * Function to get config values
 * @param string $ssConfigName
 * @return boolean|array
 */
function loadConfig($ssConfigName) {
    $CI = get_instance();
    if ($ssConfigName != '') {
        $CI->config->load($ssConfigName, TRUE);
        $config = $CI->config->config[$ssConfigName];
        $CI->config->load($ssConfigName, FALSE);
        return $config;
    }
    return false;
}

/**
 * Function to convert seconds in hours minuts and seconds
 * @param int $seconds
 * @return array
 */
function secondsToTime($seconds = 0) {
    // extract hours
    $hours = floor($seconds / (60 * 60));

    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);

    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);

    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
    return $obj;
}

/**
 * Function to short string if length is too long
 * @param string $ssText
 * @param integer $snMaxchar
 * @param string $ssEnd
 * @return string
 */
function substrwords($ssText = '', $snMaxchar = 30, $ssEnd = '...') {
    if (strlen($ssText) > $snMaxchar || $ssText == '') {
        //$ssWords = preg_split('/\s/', $ssText);
        $ssWords = $ssText;
        $ssOutput = '';
        $i = 0;
        while (1) {
            $length = strlen($ssOutput) + strlen($ssWords[$i]);
            if ($length > $snMaxchar) {
                break;
            } else {
                $ssOutput .= $ssWords[$i];
                ++$i;
            }
        }
        $ssOutput .= $ssEnd;
    } else {
        $ssOutput = $ssText;
    }
    return $ssOutput;
}

/**
 * Function to get Color
 * @param string $ssSeverity
 * @return string
 */
function getProgressBarColor($ssSeverity) {
    if ($ssSeverity != '') {
        $ssColor = '';
        switch (strtolower($ssSeverity)) {
            case 'critical':
                $ssColor = 'danger';
                break;
            case 'important':
                $ssColor = 'warning';
                break;
            case 'medium':
                $ssColor = 'text-purple';
                break;
            case 'low':
                $ssColor = 'info';
                break;
            case 'information':
                $ssColor = 'success';
                break;
        }
        return $ssColor;
    }
}

if (!function_exists('paypal_response')) {

    /**
     * Function to set message for payment response
     * @param array $asResponse
     * @return array
     */
    function paypal_response($asResponse = array()) {
        $CI = & get_instance();
        $ssFlag = FALSE;
        $asResult = array();
        if (is_array($asResponse) && sizeof($asResponse) > 0) {
            if (isset($asResponse['ACK'])) {
                if (strtolower($asResponse['ACK']) == 'success') {
                    $asResult['ssFlag'] = TRUE;
                    $asResult['message'] = 'Your payment has successfull, Package has been updated';
                    return $asResult;
                } else {
                    $asResult['ssFlag'] = FALSE;
                    $asResult['message'] = 'Your transaction is unsuccessfull, rejected by bank';
                }
                return $asResult;
            }
        }
        return $asResult;
    }

}

if (!function_exists('addhttp')) {

    /**
     * Function to set message for payment response
     * @param array $asResponse
     * @return array
     */
    function addhttp($url = '') {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

}

if (!function_exists('payu_payment_success')) {

    /**
     * If payment get succeed via payu then this function will called
     */
    function payu_payment_success() {
        echo "PayU Payment Success Called";
        exit();
    }

}


if (!function_exists('payu_payment_failure')) {

    /**
     * If payment get succeed via payu then this function will called
     */
    function payu_payment_failure() {
        echo "PayU Payment Failure Called";
        exit();
    }

}

if (!function_exists('load_config')) {

    function load_config($ssConfigValueToGet = "") {
        $CI = & get_instance();
        if ($ssConfigValueToGet) {
            try {
                $objConfig = $CI->load->config($ssConfigValueToGet, TRUE);
                $objConfig = $CI->config;
                if (is_array($objConfig->config[$ssConfigValueToGet])) {
                    return $objConfig->config[$ssConfigValueToGet];
                } else {
                    return false;
                }
            } catch (Exception $ex) {
                return false;
            }
        }
        return false;
    }

}
