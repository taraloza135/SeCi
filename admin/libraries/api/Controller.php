<?php

/*
 * Include all controllers that are needed from controller directory
 * @author Taral Oza(taral@techdefence.com)
 */
require_once APPPATH . '/libraries/api/controllers/scan.php';
require_once APPPATH . '/libraries/api/controllers/site.php';
require_once APPPATH . '/libraries/api/controllers/user.php';
require_once APPPATH . '/libraries/api/controllers/support.php';

trait Controller {

    use Scan_Controller,
        Site_Controller,
        User_Controller,
        Support_Controller;
}
