<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of baseController
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
class BASE_Controller extends CI_Controller {

    /**
     * Array for menu
     * @var type 
     */
    public $asMenu = array();
    public $checkSuperAdmin;
    var $key = "t@t4chT0OK";

    public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('auth');
        //$this->load->library('acunetixAPI');

        $request = "index";
        if (trim($this->uri->segment(2)) != "") {
            $request = $this->uri->segment(2);
        }
        if (!method_exists($this, $request)) {
            redirect('error/error_404');
        }


        $this->form_validation->set_error_delimiters('<label for="admin_email" generated="false" class="error">', '</label>');
    }

    public function addMetaTags() {
        
    }

    public function addMetaDescriptions() {
        
    }

    /**
     *  Custom error message just to validate
     * @param type $password
     * @return boolean
     */
    function validate_password($password) {
        if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%]).*$/', $password)) {
            $this->form_validation->set_message('validate_password', $this->lang->line("error_check_password_strength"));
            return false;
        }
        return true;
    }

    /**
     * Function to validate the domain name entered by user
     */
    function validate_domain($domain) {
        /* if (preg_match('/(ww[a-z]+\.)?[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+\.[a-zA-Z]{2,5}/', $domain)) {
          return true;
          } */

        if (preg_match('/((ww[a-z]+\.?)?[a-zA-Z0-9\-_]+\.?)[a-zA-Z0-9\-_]+\.([a-zA-Z]{2,5}?)/i', $domain)) {
            return true;
        }
        if (preg_match('/^(?:(?:2[0-4]\d|25[0-5]|1\d{2}|[1-9]?\d)\.){3}(?:2[0-4]\d|25[0-5]|1\d{2}|[1-9]?\d)(?:\:(?:\d|[1-9]\d{1,3}|[1-5]\d{4}|6[0-4]\d{3}|65[0-4]\d{2}|655[0-2]\d|6553[0-5]))?$/i', $domain)) {
            return true;
        }
        $this->form_validation->set_message('validate_domain', $this->lang->line("error_website_invalid"));
        return false;
    }

    function validate_price($price) {
        if (!preg_match('/^\d{0,4}(\.\d{0,2})?$/i', $price)) {
            $this->form_validation->set_message('package_price', $this->lang->line("error_valid_price"));
            return false;
        }
        return true;
    }

    /**
     * Function to check exist in edit time 
     * @param type $ssValue
     * @param type $ssStr
     * @return boolean
     */
    function is_exist($ssValue, $ssStr) {
        $parts = explode('.', $ssStr);

        if ($this->uri->segment(3)) {
            $this->db->where("id_" . $parts[0] . " != ", $this->uri->segment(3));
        }


        $this->db->from($parts[0]);
        $this->db->where($parts[1], $ssValue);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $this->form_validation->set_message('is_exist', ucfirst($parts[1]) . ' already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 
     * @param type $string
     * @return type
     */
    public function encryptData($string) {
        $iv = md5($this->key);
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->key), $string, MCRYPT_MODE_CBC, $iv);
        return base64_encode($output);
    }

    /**
     * 
     * @param type $string
     * @return type
     */
    public function decryptData($string) {
        $iv = md5($this->key);
        $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
        return rtrim($output, "");
    }

    /**
     * Function to check valid old password
     * @param type $ssPassword
     * @return boolean
     */
    function validate_old_password($ssPassword = '') {
        $this->load->model('user_model');
        $snIdUser = trim(decryptData($this->asUser['id']));
        $asUsers = $this->user_model->getUserDetailsById($snIdUser);

        $asUserData = array();
        $asUserData['password'] = $ssPassword;
        $asUserData['email'] = $asUsers['email'];
        $asUserData['salt'] = $asUsers['salt'];

        $asCredentials = $this->auth->generatePassSalt($asUserData);

        if ($asUsers['password'] != $asCredentials['password']) {
            $this->form_validation->set_message('validate_old_password', $this->lang->line('error_invalid_password'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Strips HTML tags from string
     * @param type $str
     * @return boolean
     */
    public function validate_tags($str) {
        $str = trim(htmlspecialchars(strip_tags($str)));
        if ($str != "") {
            return $str;
        }
        return FALSE;
    }

    public function validate_xss_clean($str) {
        return $this->security->xss_clean($str);
    }

}
