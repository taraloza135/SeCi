<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait Site_Model {

    function getSiteList($snIdCustomer = 0) {
        if ($snIdCustomer > 0) {
            $this->db->select('s.id_site,'
                    . 's.site_name,'
                    . 's.site_url,'
                    . 's.site_verified,'
                    . 'c.id_customer,'
                    . 'p.sites,p.scans,'
                    . 'cp.id_customer_package,'
                    . 'cp.id_package,'
                    . ' ( SELECT COUNT(sc.id_scan) FROM scan as sc WHERE sc.id_site = s.id_site GROUP BY sc.id_site) AS TOTAL_SCAN_USED  ,');
            //. ' (p.scans - (SELECT COUNT(sc.id_scan) FROM scan as sc WHERE sc.id_site = s.id_site GROUP BY sc.id_site)) as REMAINING_SCANS ');
            $this->db->join('customer as c', 'c.id_customer = s.id_customer', 'LEFT');
            $this->db->join('customer_package as cp', ' cp.id_customer = c.id_customer  AND cp.status = ' . "'1'", 'LEFT');
            $this->db->join('package as p', 'p.id_package = cp.id_package', 'LEFT');
            //  $this->db->join('scan as sc', 'sc.id_site = s.id_site', 'LEFT');
            $this->db->where('c.id_customer', $snIdCustomer);
            $this->db->where('s.site_verified', '1');
            $this->db->where('p.status', '1');
            $ssQuery = $this->db->get('site as s');
            $asResult = $ssQuery->result_array();
            if ($ssQuery->num_rows() > 0) {
                $asResultScan = $ssQuery->result_array();
                foreach ($asResultScan as $ssKey => $asScan) {
                    $snIdScan = $this->encrypt_decrypt('encrypt', $asScan['id_site']);
                    $asResultScan[$ssKey]['id_site'] = $snIdScan;
                }
                return $asResultScan;
            }
            return array();
        }
    }

}
