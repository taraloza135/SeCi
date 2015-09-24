<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Trait for Authentication and Authorization
 */
trait ApiAuthentication {

    /**
     * Get Allowed methods based on methods
     * @return boolean
     */
    private function fromHeadersCheckedAllowedMethods() {
        $asAllPostRequest = getallheaders();
        if (isset($asAllPostRequest['X-Appdefence-Uid']) && isset($asAllPostRequest['X-Appdefence-AccessToken']) && (trim($asAllPostRequest['X-Appdefence-Uid']) != "" && trim($asAllPostRequest['X-Appdefence-AccessToken']) != "")) {
            return array('status' => true, 'allowed_methods' => array());
        } elseif (isset($asAllPostRequest['X-Appdefence-AuthToken'])) {
            return array('status' => true, 'allowed_methods' => array('auth_post'));
        } else {
            return false;
        }
    }

    protected function validateAuthorization() {
        $asAllPostRequest = getallheaders();
        if (isset($asAllPostRequest['X-Appdefence-Uid']) && isset($asAllPostRequest['X-Appdefence-AccessToken']) && (trim($asAllPostRequest['X-Appdefence-Uid']) != "" && trim($asAllPostRequest['X-Appdefence-AccessToken']) != "")) {
            $snIdCustomer = $this->checkIfUserAccessTokenAndUidIsValid($asAllPostRequest['X-Appdefence-AccessToken'], $asAllPostRequest['X-Appdefence-Uid']);
            $this->denyWrongPersonAccess($snIdCustomer);
            return $snIdCustomer;
        } else {
            $this->response(array('error' => 'invalid authorization detail'), 400);
        }
    }

    // Check If access token and user Id exist in dabase table.
    private function checkIfUserAccessTokenAndUidIsValid($ssAccessToken = "", $ssUid = "") {
        $asCustomerApiUserData = array();

        $validTimeDifference = 1800;
        $this->db->select('ca.id_customer_api_uids,ca.id_customer,ca.datetime');
        $this->db->where('ca.accesstoken', $ssAccessToken);
        $this->db->where('ca.uid', $ssUid);
        $ssQuery = $this->db->get('customer_api_uids as ca');
        $asCustomerApiUserData = $ssQuery->row_array();
        if (count($asCustomerApiUserData) > 0) {
            $currentGMT = gmdate('Y-m-d H:i:s');
            $currentGMTTimestamp = strtotime($currentGMT);
            $datetimeGMTTimestamp = strtotime($asCustomerApiUserData['datetime']);
            if (($currentGMTTimestamp - $datetimeGMTTimestamp) <= $validTimeDifference) {
                $asCustomerApiUserDataToUpdate = array('datetime' => $currentGMT);
                $this->db->where('id_customer', $asCustomerApiUserData['id_customer']);
                $this->db->update('customer_api_uids', $asCustomerApiUserDataToUpdate);
                return $asCustomerApiUserData['id_customer'];
            } else {
                $this->response(array('error' => 'login required OR session expired'), 400);
            }
        } else {
            $this->response(array('error' => 'login required OR session expired'), 400);
        }
    }

    /**
     * Authentication method
     * Header Required X-Appdefence-AuthToken : $ssAuthToken
     * @param User Name
     * @param Password
     */
    public function auth_post() {
        $asAllPostRequest = getallheaders();
        $this->load->library('auth');
        if (isset($asAllPostRequest['X-Appdefence-Uid']) || isset($asAllPostRequest['X-Appdefence-AccessToken']) && (trim($asAllPostRequest['X-Appdefence-Uid']) != "" || trim($asAllPostRequest['X-Appdefence-AccessToken']) != "")) {
            $this->response(array('error' => 'invalid parameters'), 400);
        } else {
            $asPostValues = $this->input->post(NULL, TRUE);
            if (isset($asPostValues['username']) && isset($asPostValues['password'])) {
                $asAllPostRequest = getallheaders();
                if (isset($asAllPostRequest['X-Appdefence-AuthToken'])) {
                    $ssEmail = $asPostValues['username'];
                    $ssPassword = $asPostValues['password'];
                    $ssAuthToken = $asAllPostRequest['X-Appdefence-AuthToken'];
                    $ssPassword = $asPostValues['password'];
                    $this->db->select('customer.*,customer.id_customer as id,cd.timezone,cd.user_image');
                    $this->db->join('customer_detail as cd', 'cd.id_customer = customer.id_customer', 'LEFT');
                    $this->db->where('customer.email', trim($ssEmail));
                    $this->db->where('customer.api_token_key', trim($ssAuthToken));
                    $this->db->where('customer.status', '1');
                    $this->db->where('customer.is_demo', '0');
                    $this->db->where('customer.customer_type', 'customer');
                    $ssQuery = $this->db->get('customer as customer', 1);

                    if ($ssQuery->num_rows() == 0) {
                        $this->response(array('error' => 'invalid authentication detail'), 400);
                    }
                    $asUser = $ssQuery->row_array();

                    $ssHashed = $this->auth->_hash($ssPassword, $ssEmail, $asUser['salt']);
                    if ($asUser['password'] == $ssHashed['hashed']) {
                        $snIdCustomer = $asUser['id_customer'];
                        $ssAccessToken = substr(str_shuffle(str_shuffle(md5($this->encrypt_decrypt("encrypt", rand(0, rand(0, 1500)) . "#" . $ssEmail)))), 0, 20);
                        $ssUserId = substr(str_shuffle(str_shuffle(md5($this->encrypt_decrypt("encrypt", rand(0, rand(1501, 2500)) . "#" . $snIdCustomer)))), 0, 10);
                        $this->db->select('id_customer_api_uids');
                        $this->db->where('id_customer', $snIdCustomer);
                        $ssQuery = $this->db->get('customer_api_uids');
                        $customerEntry = $ssQuery->num_rows();
                        $asCustomerApiData = array(
                            'id_customer' => $snIdCustomer,
                            'uid' => $ssUserId,
                            'accesstoken' => $ssAccessToken,
                            'datetime' => gmdate('Y-m-d H:i:s'),
                        );
                        //print_r($asCustomerApiData);exit();
                        if ($customerEntry == 0) {
                            $this->db->insert('customer_api_uids', $asCustomerApiData);
                        } else {
                            $this->db->where('id_customer', $snIdCustomer);
                            $this->db->update('customer_api_uids', $asCustomerApiData);
                        }
                        $this->response(array('data' => array('Uid' => $ssUserId, 'AccessToken' => $ssAccessToken)), 200);
                    }
                }
            }

            $this->response(array('error' => 'invalid authentication detail'), 400);
        }
    }

    // End of post auth
}
