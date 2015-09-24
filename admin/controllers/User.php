<?php

require 'BASE_Controller.php';
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of baseController
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
class User extends BASE_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('auth');

        $this->checkSuperAdmin = 1;
        $this->output->enable_profiler(ENABLE_PROFILER);
        // $this->form_validation->set_error_delimiters('<label for="admin_email" generated="false" class="error">', '</label>');
    }

    public function index() {
        redirect(site_url('user/login'));
    }

    public function login() {
        $meta = array(
            'meta_title' => $this->lang->line('login'),
            'meta_keywords' => $this->lang->line('meta_keywords'),
            'meta_description' => $this->lang->line('meta_keywords'),
            'meta_robots' => 'all',
            'extra_headers' => ''
        );

        $data = array();
        // merge meta and data
        $data = array_merge($data, $meta);

        $data['pageView'] = 'user/login';
        $data['bodyClass'] = 'user-login';
        $this->session->set_userdata('verified_auth', FALSE);
        if ($this->input->post()) {

            $asConfigValues = load_config('form_validation');

            if ($this->form_validation->run('admin_login_step1') == TRUE):
                $ssUserName = $this->input->post('username');
                $ssPassword = $this->input->post('password');
                $ibLoginStatus = $this->auth->login($ssUserName, $ssPassword, 1);
                if ($ibLoginStatus) {
                    $this->session->set_userdata('verified_two_step_auth', FALSE);
                    if (TWO_STEP_VARIFICATION) {
                        $length = 12;
                        $this->session->set_userdata('verified_two_step_auth', TRUE);
                        $keyBeingSendToUser = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                        $this->auth->insertCustomValueInSession('pin', $keyBeingSendToUser);
                        $asSessionData = $this->session->all_userdata(SESSION_VARIABLE);

                        if (SEND_OTP_EMAIL) {
                            $asEmailToSent = explode(",", USER_PIN_EMAILS);
                            $asEmailData = array('EMAIL' => $asEmailToSent, 'VERIFICATION_CODE' => $keyBeingSendToUser);
                            //print_r($asEmailData);exit;
                            $this->sendingOtpEmail($asEmailData);
                        }
                        if (SEND_OTP_SMS) {
                            echo "SMS CODE WILL SEND";
                            exit();
                        }

                        redirect('user/verification');
                        /* echo "Auth Send";
                          exit(); */
                    }
                    set_flash('success', $this->lang->line('user_login_success'));
                    redirect('dashboard'); //$this->auth->deleteCustomValueInSession('pin');
                    // First step completed now It's time for another step for verification 
                    //   $this->session->userdata('verified_auth', FALSE);
                } else {
                    set_flash('error', $this->lang->line('user_login_fail'));
                    redirect('login');
                }
            else:
                set_flash('error', 'Something went wrong !!');
                redirect('user/login');
            endif;
        }

        $this->load->view('login_layout', $data);
    }

    /**
     * Function for Second step for verification 
     */
    public function verification() {

        if (TWO_STEP_VARIFICATION) {
            $this->session->set_userdata('verified_auth', FALSE);
            $this->session->set_userdata('verified_two_step_auth', TRUE);
            $data['pageView'] = 'user/otp';
            $data['bodyClass'] = 'user-login';
            $asAllUserData = $this->session->all_userdata();
            //print_r($asAllUserData);exit();
            if (isset($asAllUserData ['admin_auth']) && trim($asAllUserData ['admin_auth']['id']) != "") {
                if ($this->input->post()) {
                    if ($this->form_validation->run('admin_login_step2') == TRUE):
                        $snOtp = $this->input->post('otp');
                        if ($snOtp === $asAllUserData['pin']) {
                            $this->auth->deleteCustomValueInSession('pin');
                            $this->session->set_userdata('verified_auth', TRUE);
                            set_flash('success', 'Success verified');
                            redirect('dashboard');
                        } else {
                            $this->session->set_userdata('verified_auth', FALSE);
                            set_flash('error', 'Error while verifing OTP');
                            redirect('user/verification');
                        }
                    endif;
                }
                $this->load->view('login_layout', $data);
            } else {

                set_flash('error', 'First Login via Username / Password');
                redirect('login');
            }
        } else {
            redirect('error/error_404');
        }
    }

    /**
     * Function to send registration email
     * @param type $asData
     * @return boolean
     */
    protected function sendingOtpEmail($asData = array()) {
        if (!empty($asData)) {
            $asMessage = generate_email_content('login_pincode_verification', $asData);
            send_Email($asData['EMAIL'], EMAIL_SUBJECT_MODERATER_ADD, $asMessage);
        } else
            return true;
    }

    /**
     * Function to execute logout action
     */
    public function logout() {
        $this->load->library('auth');
        $this->auth->logout();
        $this->session->set_flashdata('success', 'Successfully Logout from System !!');
        redirect('login');
    }

}
