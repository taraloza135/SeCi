<?php

class Auth {

    var $_obj;
    var $_user;

    /**
     * Function to call contructor
     * @return unknown_type
     */
    function Auth() {
        $this->_obj = & get_instance();
        $this->_obj->load->library('session');
        $this->_get_session();
    }

    /**
     * Function to create hash
     * @param $password string
     * @param $email string
     * @param $salt Null
     * return array
     */
    function _hash($ssPassword, $ssEmail, $ssSalt = NULL) {
        $ssString = $ssPassword . $ssEmail;
        //$ssString = $ssPassword;
        //$encryption_key = $this->_obj->config->item('encryption_key');


        if ($ssSalt === NULL) {
            $ssSalt = sha1(uniqid(rand(), TRUE));
        }

        $ssString = $ssString . $ssSalt;

        $ssHashed = sha1($ssString);

        return array('hashed' => $ssHashed, 'salt' => $ssSalt);
    }

    /**
     * Function to set url in session
     * @param $url string
     */
    function _set_session_url($ssUrl) {
        $data['url'] = $ssUrl;

        $this->_obj->session->set_userdata('url', $asData);
    }

    /**
     * Function to set session for system
     * @param $asUser Array
     * @param $snIdInstance Integer
     * @param $otheruser string
     * return session
     */
    function _set_session_user($asUser, $snIdInstance = '', $otheruser = '') {

        $asData['id'] = encryptData($asUser['id_user']);
        $asData['email'] = $asUser['email'];
        $asData['logged_in'] = encryptData(1);
        $asData['type'] = encryptData('admin');

        if ($otheruser != '') {
            $this->_obj->session->set_userdata('admin', $asData);
        } else {
            $this->_obj->session->set_userdata('admin_auth', $asData);
        }

        $this->_get_session();
    }

    /**
     * Function to get session object
     * return boolean
     */
    function _get_session() {
        $asUser = $this->_obj->session->userdata('customer_auth');

        if ($asUser === FALSE || (isset($asUser['logged_in']) && trim(decryptData($asUser['logged_in'])) !== '1')) {
            $asUser = array(
                'id' => 0,
                'logged_in' => FALSE
            );

            $this->_user = $asUser;

            return FALSE;
        } else {
            $this->_user = $asUser;

            return TRUE;
        }
    }

    /**
     *
     * Function to clear all session
     * @return unknown_type
     */
    function _clear_session() {
        $this->_obj->session->unset_userdata('customer_auth');
        /* $this->_obj->session->unset_userdata('otherauth'); */
        $this->_get_session();

        $this->_obj->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        $this->_obj->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->_obj->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->_obj->output->set_header("Pragma: no-cache");
    }

    /**
     *
     * Function to get logged in user.
     * @return boolean
     */
    function logged_in() {
        return $this->_user['logged_in'];
    }

    /**
     *
     * Function to get user data
     * @return object
     */
    function get_user() {
        return $this->_user;
    }

    /**
     * Function to login in system
     * @param $ssEmail String
     * @param $ssPassword string
     * return boolean
     */
    function login($ssEmail, $ssPassword) {
        $snIdInstance = '';
        $this->_obj->db->where('username', $ssEmail);
        $this->_obj->db->where('status', 1);

        $ssQuery = $this->_obj->db->get('4dm1n', 1);
        

        if ($ssQuery->num_rows() == 0) {
            return FALSE;
        }


        $asUser = $ssQuery->row_array();

        $ssHashed = $this->_hash($ssPassword, $ssEmail, $asUser['salt']);

        if ($asUser['password'] == $ssHashed['hashed']) {
            $this->_set_session_user($asUser);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Fcuntion to create new user
     * @param $asUser array
     * return integer
     */
    function create($asUser = NULL) {
        if (empty($asUser))
            return FALSE;

        $asUser_main = $asUser;

        $encrypted = $this->_hash($asUser['password'], $asUser['email']);

        $clear_password = $asUser['password'];

        $asUser['password'] = $encrypted['hashed'];
        $asUser['salt'] = $encrypted['salt'];

        $this->_obj->db->insert('user', $asUser);
        $snIdUser = $this->_obj->db->insert_id();

        $this->sendMail($asUser_main);

        return $snIdUser;
    }

    /**
     *
     * Function to logout from system
     * @return unknown_type
     */
    function logout() {

        $this->_clear_session();
        unset($_SESSION);
        session_unset();
        session_destroy();
        unset($_COOKIE);
        //$this->delFiles("tmp/associations/");
        //$this->delFiles("tmp/nonces/");
    }

    /**
     * Function to delete files from directory
     * @param $dir string
     */
    function delFiles($dir) {
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (substr($file, -1) == '/')
                delTree($file);
            else
                unlink($file);
        }
        if (is_dir($dir))
            rmdir($dir);
    }

    /**
     * Function to reset password
     * @param $ssEmail string
     * return boolean
     */
    function reset_password($ssEmail) {
        $this->_obj->load->library('email');

        $this->_obj->db->where('email', $ssEmail);
        $ssQuery = $this->_obj->db->get('user', 1);

        if ($ssQuery->num_rows() == 0) {
            return FALSE;
        }

        $asUser = $ssQuery->row_array();

        $hash = substr(sha1($asUser['email'] . rand()), 0, 10);

        $asData = array(
            'reset_password_hash' => $hash
        );

        $this->_obj->db->where('id', $asUser['id']);
        $ssQuery = $this->_obj->db->update('user', $asData);

        return TRUE;
    }

    /**
     * Function to set password
     * @param $snId integer
     * @param $ssPassword string
     * return boolean
     */
    function set_password($snId, $ssPassword) {
        $this->_obj->db->where('id', $snId);
        $ssQuery = $this->_obj->db->get('user', 1);


        if ($ssQuery->num_rows() == 0) {
            return FALSE;
        }

        $asUser = $ssQuery->row_array();


        $new_password = $this->_hash($ssPassword, $asUser['email']);

        $asData = array(
            'password' => $new_password['hashed'],
            'salt' => $new_password['salt']
        );

        $this->_obj->db->where('id', $asUser['id']);
        $this->_obj->db->update('user', $asData);
        //$this->sendMail($asUser,true);

        return TRUE;
    }

    /**
     * Function for edit profile
     *
     * @param $snId = id of user
     * @param $asData = array()
     * @return true or false
     */
    function edit_profile($snId, $asData = array()) {

        $asData = array('first_name' => $asData['first_name'],
            'last_name' => $asData['last_name'],
            'email' => $asData['email'],
            'send_email' => $asData['send_email'],
            'open_id' => $asData['open_id']
        );
        if ($asData['password'] != '') {
            $new_password = $this->_hash($asData['password'], $asData['email']);
            $asData['password'] = $new_password['hashed'];
            $asData['salt'] = $new_password['salt'];
        } else {
            $asData['password'] = $asData['old_password'];
            $asData['salt'] = $asData['old_salt'];
        }

        $this->_obj->db->where('id', $snId);
        $this->_obj->db->update('user', $asData);
        return true;
    }

    /**
     * Function to login by oepn id
     * @param $asUser array
     * return boolean
     */
    function loginByOpenId($asUser) {
        if (sizeof($asUser) > 0) {
            $this->_obj->db->where('id', $asUser['id']);
            $iuquery = $this->_obj->db->get('instance_user');
            $cnt = $iuquery->num_rows();
            $snIdInstance = '';
            if ($cnt > 0) {
                $iresult = $iuquery->row_array();
                $snIdInstance = $iresult['id_instance'];
            }

            $this->_set_session_user($asUser, $snIdInstance);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Fcuntion to generate Password and salt
     * @param $asUser array
     * return integer
     */
    public function generatePassSalt($asUser = NULL) {
        if (empty($asUser))
            return FALSE;

        $ssSalt = NULL;
        $asUser_main = $asUser;
        if (isset($asUser['salt']) && $asUser['salt'] != '')
            $ssSalt = $asUser['salt'];
        $encrypted = $this->_hash($asUser['password'], $asUser['email'], $ssSalt);

        $clear_password = $asUser['password'];

        $asUser['password'] = $encrypted['hashed'];
        $asUser['salt'] = $encrypted['salt'];

        return $asUser;
    }

    function token() {
        $token = md5(uniqid(rand(), true));
        $this->_obj->session->set_userdata('token', $token);
        return $token;
    }

    /**
     * Function to generate Random password
     * @param type $l
     * @param type $c
     * @param type $n
     * @param type $s
     * @return boolean
     */
    function generateRandomPassword($l = 8, $c = 0, $n = 0, $s = 0) {
        $out = "";
        // get count of all required minimum special chars
        $count = $c + $n + $s;

        // sanitize inputs; should be self-explanatory
        if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } elseif ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        // all inputs clean, proceed to build password
        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$";

        // build the base password of all lower-case letters
        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if ($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = array();

            // add required special character(s) to second array
            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    /**
     * Function to insert custom value in current defined session
     */
    public function insertCustomValueInSession($ssKey = "", $ssValue = "") {
        $this->_obj->session->set_userdata($ssKey, $ssValue);
    }

    /**
     * Function to delete custom defined value
     */
    public function deleteCustomValueInSession($ssKey = "", $ssValue = "") {
        $this->_obj->session->unset_userdata($ssKey);
    }

}
