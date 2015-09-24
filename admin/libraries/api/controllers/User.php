<?php

/**
 * User Controller to execute all action related particular user
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
trait User_Controller
{

    /**
     * Function to get user profile details
     * @param integer $snIdCustomer
     */
    public function get_profile_get($snIdCustomer = 0)
    {

        if ($snIdCustomer > 0)
        {

            $asUserDetails = $this->getUserDetails($snIdCustomer);
            if (count($asUserDetails) > 0)
            {
                $asScansUpdated['data'] = $asUserDetails;
                $this->response($asScansUpdated, 200);
            }
            else
            {
                $this->response(array('error' => 'no deteails found'), 200);
            }
        }
        else
        {
            $this->response(array('error' => 'Something went wrong'), 200);
        }
    }

    /**
     * Function to execute edit action for profile 
     * @param int $snIdCustomer
     */
    public function edit_profile_post($snIdCustomer = 0)
    {
        $asFields = array('first_name', 'last_name', 'address1', 'address2', 'city', 'state', 'country', 'zip', 'phone_no');
        if ($snIdCustomer > 0)
        {
            if (isset($_POST))
            {
                $this->load->library('form_validation');
                $this->form_validation->set_error_delimiters('', ',');
                $asPostData = $this->input->post(NULL, TRUE);
                $asPostData = array_filter($asPostData);
                if ($this->form_validation->run('API/profile_edit') == TRUE)
                {
                    $asFielsdData = array();
                    foreach ($asFields as $ssKey)
                    {
                        if (array_key_exists($ssKey, $asPostData))
                        {
                            $asFielsdData[$ssKey] = $asPostData[$ssKey];
                        }
                    }
                    $this->editProfile($snIdCustomer, $asFielsdData);
                    return $this->response(array('data' => 'profile updated successfully'), 200);
                }
                else
                    return $this->response(array('error' =>'problem with input data','details'=> validation_errors()), 200);
            }
        }
        else
        {
            return $this->response(array('error' => 'Something went wrong'), 200);
        }
    }

}
