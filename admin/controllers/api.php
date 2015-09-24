<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Taral Oza
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions

require_once APPPATH . '/libraries/api/misc/General_Functions.php';
require_once APPPATH . '/libraries/api/Model.php';
require_once APPPATH . '/libraries/api/Controller.php';
require_once APPPATH . '/libraries/api/misc/Authentiaction.php';
require_once APPPATH . '/libraries/api/misc/Authorization.php';
require_once APPPATH . '/libraries/api/REST_Controller.php';

class Api extends REST_Controller {

    use General_Functions,
        Controller,
        Model,
        checkAccessForUser,
        ApiAuthentication;

    function __construct() {
        parent::__construct();

        // Check If user authentication valid or not
        $asReturnStatus = $this->fromHeadersCheckedAllowedMethods();
        if (!$asReturnStatus) {
            $this->response(array('error' => 'invalid authentication detail'), 400);
        }

        $asAllowedMethods = $asReturnStatus['allowed_methods'];
        $ssRequestMethod = strtolower($this->input->server('REQUEST_METHOD'));
        $moduleToCall = $this->uri->segment(2);
        $functionToCall = $moduleToCall . "_" . $ssRequestMethod;
        if (method_exists($this, $functionToCall)) {
            if (count($asAllowedMethods) > 0 && in_array($functionToCall, $asAllowedMethods)) {
                
            } else {
                if (count($asAllowedMethods) == 0) {
                    $snIdCustomer = $this->validateAuthorization(); //Validate Authorization
                    if (is_numeric($snIdCustomer) && $snIdCustomer > 0) {
                        call_user_func_array(array($this, $functionToCall), array($snIdCustomer));
                    }
                } else {
                    $this->response(array('error' => 'invalid method'), 404);
                }
            }
        } else {
            $this->response(array('error' => 'invalid method'), 404);
        }
    }

}
