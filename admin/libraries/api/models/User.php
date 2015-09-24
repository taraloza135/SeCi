<?php

//defined('BASEPATH') OR exit('No direct script access allowed');

trait User_Model
{

    /**
     * Function to get User details
     * @param integer $snIdCustomer
     * @return array
     */
    function getUserDetails($snIdCustomer = 0)
    {
        if ($snIdCustomer > 0)
        {
            $this->db->select('cd.first_name,cd.last_name,c.id_customer, c.email,cd.address1,cd.address2,cd.city,cd.state,cd.country,cd.zip,cd.phone_no');
            $this->db->where("c.id_customer", $snIdCustomer);
            $this->db->where("c.customer_type", 'customer');
            $this->db->where("c.is_demo", '0');
            $this->db->join('customer_detail as cd', ' c.id_customer = cd.id_customer', 'LEFT');
            $this->db->limit('1');
            $ssQuery = $this->db->get("customer as c");


            if ($ssQuery->num_rows() > 0)
            {
                $asResultScan = $ssQuery->result_array();
                foreach ($asResultScan as $ssKey => $asScan)
                {
                    $snIdScan = encrypt_decrypt('encrypt', $asScan['id_customer']);
                    $asResultScan[$ssKey]['id_customer'] = $snIdScan;
                }
                return $asResultScan;
            }
            return array();
        }
    }

    /**
     * Function to update customer profile 
     * @param integer $snIdCustomer
     * @param array  $asData
     * @return boolean
     */
    function editProfile($snIdCustomer = 0, $asData = array())
    {
        if ($snIdCustomer > 0 && !empty($asData))
        {
            $this->db->where('cd.id_customer', $snIdCustomer);
            $this->db->update('customer_detail as cd', $asData);
            return true;
        }
        return false;
    }

}
