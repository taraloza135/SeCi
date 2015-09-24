<?php

defined('BASEPATH') OR exit('No direct script access allowed');

trait Support_Model
{

    /**
     * Function to get Support details
     * @param integer $snIdCustomer
     * @return array
     */
    function getSupportDetails($snIdCustomer = 0)
    {
        if ($snIdCustomer > 0)
        {
            $this->db->select('sd.id_support_detail,sd.id_ticket,sd.ticket_number,sd.ticket_subject,sd.created,sd.status');
            $this->db->where("sd.id_customer", $snIdCustomer);
            $this->db->order_by('sd.id_support_detail', 'DESC');
            $ssQuery = $this->db->get("support_detail as sd");
            if ($ssQuery->num_rows() > 0)
            {
                $asResultScan = $ssQuery->result_array();
                foreach ($asResultScan as $ssKey => $asScan)
                {
                    $snIdSupportDetail = $this->encrypt_decrypt('encrypt', $asScan['id_support_detail']);
                    $snIdTicket = $this->encrypt_decrypt('encrypt', $asScan['id_ticket']);
                    $asResultScan[$ssKey]['id_support_detail'] = $snIdSupportDetail;
                    $asResultScan[$ssKey]['id_ticket'] = $snIdTicket;
                    /* $asResultScan[$ssKey][$this->getOutputFields('id_support_detail')] = $snIdSupportDetail;
                      $asResultScan[$ssKey][$this->getOutputFields('id_ticket')] = $snIdTicket; */
                }
                return $asResultScan;
            }
            return array();
        }
    }

}
