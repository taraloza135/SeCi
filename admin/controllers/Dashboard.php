<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'controllers/BASE_Controller.php';

/**
 * Description of dashboard
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
class Dashboard extends BASE_Controller {

    /**
     * Constructor of dashboard controller
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('auth');
        //$this->load->model('feedback_model');
        
    }

    /**
     * Index Page for this controller.
     * map to /index.php/dashboard/index
     */
    public function index() {
        $data = array();
        $meta = array(
            'meta_title' => $this->lang->line('dashboard'),
            'meta_keywords' => $this->lang->line('meta_keywords'),
            'meta_description' => $this->lang->line('meta_keywords'),
            'meta_robots' => 'all',
            'extra_headers' => ''
        );
        $data['ssToken'] = $this->auth->token();
        // merge meta and data
        $data = array_merge($data, $meta);
        $data['bodyClass'] = "";
        $data['pageTitle'] = $this->lang->line('dashboard');
        $data['pageView'] = 'dashboard/index';
        $data['role'] = 'admin';



        //$data['feedbacks'] = $this->feedback_model->dashboardCount();
        $this->load->view('layout', $data);
    }

}
