<?php

$username = "admin";
$password = "Test@123";

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
    
    $asPasswordHashes = _hash($password, $username);
    
    print "<pre>";
    print_r($asPasswordHashes);exit();
?>