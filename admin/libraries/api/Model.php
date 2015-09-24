<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . '/libraries/api/models/scan.php';
require_once APPPATH . '/libraries/api/models/site.php';
require_once APPPATH . '/libraries/api/models/user.php';
require_once APPPATH . '/libraries/api/models/support.php';

trait Model
{

    use Scan_Model,
        Site_Model,
        User_Model,
        Support_Model;
}
