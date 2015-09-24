<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait Scan_Model {

    /**
     * Get scan list for customers
     * @param type $snIdCustomer
     * @return type
     */
    function getScanLists($snIdCustomer = 0) {
        if ($snIdCustomer > 0) {
            $this->db->select('sc.id_scan,sc.scan_name,sc.start_time,sc.updated_time,s.site_url as site_url,s.site_name');
            //$this->db->select('sc.*,s.*,c.id_customer');
            $this->db->join('site as s', 's.id_site = sc.id_site', 'LEFT');
            $this->db->join('customer as c', 'c.id_customer = s.id_customer', 'INNER');
            $this->db->join('customer_package as cp', 'cp.id_customer = c.id_customer', 'LEFT');
            $this->db->join('package as p', 'p.id_package = cp.id_package', 'LEFT');
            $this->db->where('c.id_customer', $snIdCustomer);
            $this->db->order_by('sc.id_scan', 'DESC');
            $this->db->group_by('sc.id_scan');
            $ssQuery = $this->db->get('scan as sc');
            if ($ssQuery->num_rows() > 0) {
                $asResultScan = $ssQuery->result_array();
                foreach ($asResultScan as $ssKey => $asScan) {
                    $snIdScan = $this->encrypt_decrypt('encrypt', $asScan['id_scan']);
                    $asResultScan[$ssKey]['id_scan'] = $snIdScan;
                }
                return $asResultScan;
            }
            return array();
        }
    }

    /**
     * Get Scan Status
     * @param type $snIdCustomer
     * @param type $snIdScan
     * @return type
     */
    function getScanStatus($snIdCustomer = 0, $snIdScan = 0) {
        if ($snIdCustomer > 0 && $snIdScan > 0) {

            $this->db->select('sc.status');
            $this->db->where('sc.id_scan', $snIdScan);
            $ssQuery = $this->db->get('scan as sc');
            return $ssQuery->row_array();
        }
        return array();
    }

    /**
     * Insert the scan data
     * @param type $asScan
     * @return type
     */
    function insertScan($asScan = array()) {
        if (count($asScan) > 0) {
            $this->db->insert('scan', $asScan);
            return $snId = $this->db->insert_id();
        }
    }

    /**
     * 
     * @param type $snScanId
     * @return boolean
     */
    function deleteScan($snScanId = 0) {
        if ($snScanId > 0) {
            $this->db->select('status');
            $this->db->where('id_scan', $snScanId);
            $ssQuery = $this->db->get('scan');
            $asScanData = $ssQuery->row_array();
            if (!isset($asScanData['status'])) {
                $this->response(array('error' => 'problem with delete data'), 200);
            }
            if (($asScanData['status'] == 'scheduled' || $asScanData['status'] == 'completed')) {
                $this->db->where('id_scan', $snScanId);
                $this->db->delete('scan');
                return true;
            }
            return false;
        }
    }

}
