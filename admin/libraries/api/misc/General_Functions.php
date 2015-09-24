<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait General_Functions
{

    /**
     * 
     * @param type $date
     * @param type $format
     * @return type
     */
    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * Function to encrypt and decrypt data
     * @param string $action
     * @param string $string
     * @return string
     */
    function encrypt_decrypt($action = '', $string = '')
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'api01rr23b3aa5w69bwer001';
        $secret_iv = '9052033ASFCzxaq123001441';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt')
        {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if ($action == 'decrypt')
        {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        else
        {
            return $string;
        }

        return $output;
    }

    /**
     * Function to get Output Fields
     * @param string $ssString (id_field)
     * @return string/boolean (IdField)
     */
    function getOutputFields($ssString = '')
    {

        if ($ssString != '')
        {
            return $ssConvertedString = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($ssString))));
        }
        return false;
    }

    /**
     * Function to set Output Fields
     * @param string $ssString (IdField)
     * @return string/boolean (id_field)
     */
    function setOutputFields($ssString = '')
    {
        if ($ssString != '')
        {
            return $ssConvertedString = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($ssString))));
        }
        return false;
    }

}
