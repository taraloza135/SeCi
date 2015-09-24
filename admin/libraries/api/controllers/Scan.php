<?php

/*
 * List of all API calls used for Scan Purpose 
 * @author Taral Oza(taral@techdefence.com)
 */

trait Scan_Controller {

    /**
     * Function to get scan status
     * @param type $snIdCustomer
     */
    public function list_scans_get($snIdCustomer = 0) {
        $asScans = $this->getScanLists($snIdCustomer);
        if (count($asScans) > 0) {
            $asScansUpdated['total_found'] = count($asScans);
            $asScansUpdated['data'] = $asScans;
            $this->response($asScansUpdated, 200);
        } else {
            $this->response(array('error' => 'no scan results found'), 200);
        }
    }

    /**
     * Function to view scan status
     * @param type $snIdCustomer
     */
    public function get_scan_status_get($snIdCustomer = 0) {

        $ssScanId = $this->uri->segment(3);
        $snScanId = $this->encrypt_decrypt('decrypt', $ssScanId);
        if ((int) $snScanId > 0) {
            $snScanStatus = $this->getScanStatus($snIdCustomer, $snScanId);
            if (count($snScanStatus) > 0) {
                $asScansUpdated['data'] = $snScanStatus['status'];
                $this->response($asScansUpdated, 200);
            } else {
                $asScansUpdated = array('error' => 'no data found');
                $this->response($asScansUpdated, 400);
            }
        } else {
            $this->response(array('error' => 'problem with input data'), 200);
        }
    }

    /**
     * Function to schedule the scan
     * @param type $snIdCustomer
     */
    public function schedule_scan_post($snIdCustomer = 0) {
        $asPostValues = $this->input->post(NULL, TRUE);
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', ',');
        if ($this->form_validation->run('API/scan_schedule')) {
            if (count($asPostValues) > 0 && (isset($asPostValues['id_site']) && isset($asPostValues['name']) && isset($asPostValues['start_time']) && $this->validateDate($asPostValues['start_time']))) {

                $snIdSite = $this->encrypt_decrypt('decrypt', $asPostValues['id_site']);
                if ($snIdSite > 0) {
                    $asDataToInsert = array();
                    $asDataToInsert['start_time'] = $asPostValues['start_time'];
                    $asDataToInsert['scan_name'] = $asPostValues['name'];
                    $asDataToInsert['id_site'] = $snIdSite;
                    $snId = $this->insertScan($asDataToInsert);
                    $snId = $this->encrypt_decrypt('encrypt', $snId);
                    $this->response(array('data' => array('id' => $snId, 'msg' => 'Scan Scheduled Successfully')), 200);
                } else {
                    $this->response(array('error' => 'problem with input data'), 200);
                }
            }
        } else {
            $message = str_replace("\n", "", validation_errors());
            $this->response(array('error' => 'problem with input data', 'detail' => $message), 200);
        }
    }

    /**
     * Delete Scan Entry
     * @param type $snIdCustomer
     */
    public function delete_scan_delete($snIdCustomer = 0) {
        $ssScanId = $this->uri->segment(3);
        $snScanId = $this->encrypt_decrypt('decrypt', $ssScanId);
        if ($snScanId > 0) {
            $bReturn = $this->deleteScan($snScanId);
            if ($bReturn) {
                $this->response(array('data' => 'record deleted successfully'), 200);
            } else {
                $this->response(array('error' => 'unable to delete scan due to under progress status'), 200);
            }
        }
        $this->response(array('error' => 'problem with input data'), 200);
    }

}
