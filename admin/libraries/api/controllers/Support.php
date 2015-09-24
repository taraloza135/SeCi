<?php

/**
 * Support Controller to execute all action related particular support
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
trait Support_Controller
{

    /**
     * Function to get user profile details
     * @param integer $snIdCustomer
     */
    public function get_support_tickets_get($snIdCustomer = 0)
    {

        if ($snIdCustomer > 0)
        {

            $asSupportDetails = $this->getSupportDetails($snIdCustomer);
            if (count($asSupportDetails) > 0)
            {
                $asScansUpdated['data'] = $asSupportDetails;
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

}
