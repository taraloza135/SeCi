<?php

/*
 * List of all API calls used for Scan Purpose 
 * @author Taral Oza(taral@techdefence.com)
 */

trait Site_Controller {

    /**
     * Function to get scan status
     */
    public function get_sites_get($snIdCustomer = 0) {
        $asScans = $this->getSiteList($snIdCustomer);
        if (count($asScans) > 0) {
            $asScansUpdated['total_found'] = count($asScans);
            $asScansUpdated['data'] = $asScans;
            $this->response($asScansUpdated, 200);
        } else {
            $this->response(array('error' => 'no sites found'), 200);
        }
    }

}
