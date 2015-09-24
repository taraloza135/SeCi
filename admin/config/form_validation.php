<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$CI = &get_instance();

$config = array(
    /* Admin login page validation check server side */

    // Initial First step general login
    'admin_login_step1' => array(
        array('field' => 'username', 'label' => 'User Name', 'rules' => 'required|trim|callback_validate_xss_clean|callback_validate_tags'),
        array('field' => 'password', 'label' => 'Password ', 'rules' => 'required|trim|callback_validate_xss_clean|callback_validate_tags')
    ),
    // Second step validate OTP one
    'admin_login_step2' => array(
        array('field' => 'otp', 'label' => $CI->lang->line('otp'), 'rules' => 'required|trim|callback_validate_xss_clean|callback_validate_tags'),
    ),
);
