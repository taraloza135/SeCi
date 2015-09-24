<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class checkUserSession {

    var $CI;

    function __construct() {
        $this->CI = & get_instance();
    }

    function validateUserSession() {
        /* Added this to prevent back navigation after logout */

        $getCurrentPath = $this->CI->router->fetch_class() . "/" . $this->CI->router->fetch_method();
        $asCheckUserSessionIgnorePath = explode(",", CHECKUSERSESSION_IGNORE_PATH);

        if ($this->CI->input->is_ajax_request()) {
            return false;
        }
        
        // Session ignore for API calls
        if (defined('CHECKUSERSESSION_API_IGNORE_PATH')) {
            $asCheckUserAPISessionIgnorePath = explode(",", CHECKUSERSESSION_API_IGNORE_PATH);
            foreach ($asCheckUserAPISessionIgnorePath as $asAPISession) {
                $asAPISession = str_replace(array('/'), array('\/'), $asAPISession);
                preg_match("/$asAPISession/i", $getCurrentPath, $matches);
                if (count($matches) > 0) {
                    return true;
                }
            }
        }


        $this->CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->CI->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->CI->output->set_header("Pragma: no-cache");
        $this->CI->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        if (!in_array($getCurrentPath, $asCheckUserSessionIgnorePath)) {
            $asUser = $this->CI->session->userdata('admin_auth');

            
            $currentURL = current_url();
            $currentURL = str_replace(base_url(), '', $currentURL);
            $currentURL = str_replace('index.php/', '', $currentURL);
            
            if (isset($asUser['logged_in']) && trim(decryptData($asUser['logged_in'])) == 1) {
                // User is validated
                if($this->CI->session->userdata('verified_two_step_auth') && !$this->CI->session->userdata('verified_auth') && $currentURL != "user/verification"){
                    redirect('user/verification');
                }
                return true;
            } else {
                // Redirect that user to login
                
                preg_match('/login/', $currentURL, $matches);
                if (count($matches) == 0) {
                    preg_match('/logout/', $currentURL, $asMatchesLogout);
                    preg_match('/index.php/', $currentURL, $asMatchesIndex);
                    if (count($matches) == 0 && count($asMatchesIndex) == 0 && count($asMatchesLogout) == 0) {
                        $this->CI->session->set_userdata('last_url', $currentURL);
                    }
                    //redirect('admin/login');
                    $this->CI->session->set_userdata('page_visit_count', 0);
                    //set_flash('msg','Please login First');
                    redirect('login');
                }
            }
        }
    }

    function validateUserSession1() {
        if ($this->CI->input->is_ajax_request()) {
            return false;
        }
        /* Added this to prevent back navigation after logout */
        $this->CI->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->CI->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->CI->output->set_header("Pragma: no-cache");
        $this->CI->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        $getCurrentPath = $this->CI->router->fetch_class() . "/" . $this->CI->router->fetch_method();
        $asCheckUserSessionIgnorePath = explode(",", CHECKUSERSESSION_IGNORE_PATH);
        if (!in_array($getCurrentPath, $asCheckUserSessionIgnorePath)) {
            $asUser = $this->CI->session->userdata('enterprise');
            $currentURL = current_url();
            if (isset($asUser['logged_in']) && trim(decryptData($asUser['logged_in'])) == 1 && trim(decryptData($asUser['type'])) === 'customer') {
                // User is validated
                return true;
            }
            if (isset($asUser['logged_in']) && trim(decryptData($asUser['logged_in'])) == 1 && in_array(trim(decryptData($asUser['type'])), array('customer', 'manager', 'sales'))) {
                // User is validated
                return true;
            } else {
                // Redirect that user to login

                $loginIgnoreArray = explode(",", LOGIN_IGNORE_PATH);
                if (count($loginIgnoreArray) > 0) {
                    foreach ($loginIgnoreArray as $loginIgnorePath) {
                        $parrent = "/" . preg_quote($loginIgnorePath, "/") . "/i";
                        preg_match($parrent, $currentURL, $matches);
                        if (count($matches) > 0) {
                            return true;
                        }
                    }
                }

                preg_match('/login/', $currentURL, $matches);
                if (count($matches) == 0) {
                    //redirect('admin/login/');
                    redirect('login/');
                }
            }
        }
    }

}
