<?php

/**
 * Enhanced Security Methods
 * 
 * Included
 * 1) Click Jacking Avoidence
 *
 * @author Taral Oza <taral@techdefence.com>
 */
class enhancedSecurity {

    /**
     * CI instance
     * @var type 
     */
    private $CI;
    protected $db_accesslog = "";

    public function __construct() {
        $this->CI = & get_instance();
    }

    /**
     * To Avoid Click Jacking
     */
    public function clickJacking() {
        // Avoid Click Jacking Headers
        header("Content-Security-Policy: frame-ancestors 'self'");
        header("X-FRAME-OPTIONS: SAMEORIGIN");
    }

    /**
     * 
     */
    public function denyWrongPersonAccess() {

        $asAuthenticationCheckList = $this->CI->load->config('acl', TRUE);
        $asAuthenticationCheckList = $asAuthenticationCheckList['authetication_access'];
        $ssController = $this->CI->router->fetch_class();
        $ssMethod = $this->CI->router->fetch_method();
        if (isset($asAuthenticationCheckList[$ssController][$ssMethod])) {

            $accessControlList = $asAuthenticationCheckList[$ssController][$ssMethod];
            $ssValueGot = $this->extractValueBasedOnParams($accessControlList['value_to_get']);

            $ssQuery = "";
            $snId = '';
            if (isset($accessControlList['after_post']) && $accessControlList['after_post']) {
                if ($_POST) {
                    if (count($accessControlList['table_to_check']) > 0) {
                        $snI = 0;
                        $asReverseArray = $accessControlList['table_to_check'];
                        $lastTableName = "";
                        $this->CI->db->select($accessControlList['column_to_check']);
                        $ssQuery = "";
                        $asProcessedTables = array();
                        foreach ($asReverseArray as $ssTable => $ssColumn) {
                            if ($snI == 0) {
                                $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                $this->CI->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                if (isset($accessControlList['extra_where_condition'])) {
                                    $asExtraWhere = $accessControlList['extra_where_condition'];
                                    if (count($asExtraWhere) > 0) {
                                        foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                            if ($ssCondition == 'AND') {
                                                $this->CI->db->where($asConditionToAttach);
                                            } elseif ($ssCondition == 'OR') {
                                                $this->CI->db->or_where($asConditionToAttach);
                                            }
                                        }
                                    }
                                }
                                $tableToGet = $ssTable;
                            } else {
                                $arrayReverseFromLast = array_reverse($asProcessedTables);
                                $asLastTable = $arrayReverseFromLast[0];
                                $lastTable = $asLastTable['table'];
                                $lastColumn = $asLastTable['column'];
                                $this->CI->db->join($ssTable, "$lastTable.$ssColumn = $ssTable.$ssColumn");
                                $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                            }
                            $snI++;
                        }
                        $ssQuery = $this->CI->db->get($tableToGet);

                        /* print "<pre>";
                          print_r($ssQuery);
                          exit(); */
                    }

                    $asResult = $ssQuery->result_array();


                    /* print_r($asResult);
                      exit(); */
                    $snId = 0;
                    if (count($asResult) > 0) {
                        if (count($asResult) == 1) {
                            $snId = $asResult[0][$accessControlList['column_to_check']];
                        } else {
                            foreach ($asResult as $asCustomerIds) {
                                $snId[] = $asCustomerIds[$accessControlList['column_to_check']];
                            }
                        }
                    } else {
                        if (count($accessControlList['alternate_table_to_check']) > 0) {
                            $snI = 0;
                            $asReverseArray = $accessControlList['alternate_table_to_check'];
                            $lastTableName = "";
                            $this->CI->db->select($accessControlList['column_to_check']);
                            $ssQuery = "";
                            $asProcessedTables = array();
                            foreach ($asReverseArray as $ssTable => $ssColumn) {
                                if ($snI == 0) {
                                    $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                    $this->CI->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                    if (isset($accessControlList['extra_where_condition'])) {
                                        $asExtraWhere = $accessControlList['extra_where_condition'];
                                        if (count($asExtraWhere) > 0) {
                                            foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                                if ($ssCondition == 'AND') {
                                                    $this->CI->db->where($asConditionToAttach);
                                                } elseif ($ssCondition == 'OR') {
                                                    $this->CI->db->or_where($asConditionToAttach);
                                                }
                                            }
                                        }
                                    }
                                    $tableToGet = $ssTable;
                                } else {
                                    $arrayReverseFromLast = array_reverse($asProcessedTables);
                                    $asLastTable = $arrayReverseFromLast[0];
                                    $lastTable = $asLastTable['table'];
                                    $lastColumn = $asLastTable['column'];
                                    $this->CI->db->join($ssTable, "$lastTable.$lastColumn = $ssTable.$ssColumn");
                                }
                                $snI++;
                            }
                            $ssQuery = $this->CI->db->get($tableToGet);
                            $asResult = $ssQuery->result_array();

                            if (count($asResult) > 0) {
                                if (count($asResult) == 1) {
                                    $snId = $asResult[0][$accessControlList['column_to_check']];
                                } else {
                                    foreach ($asResult as $asCustomerIds) {
                                        $snId[] = $asCustomerIds[$accessControlList['column_to_check']];
                                    }
                                }

                                /* print "<pre>";
                                  print_r($ssQuery);
                                  exit(); */
                            }
                        }
                    }
                }
            } else {
                if (count($accessControlList['table_to_check']) > 0) {
                    $snI = 0;
                    $asReverseArray = $accessControlList['table_to_check'];
                    $lastTableName = "";
                    $this->CI->db->select($accessControlList['column_to_check']);
                    $ssQuery = "";
                    $asProcessedTables = array();
                    foreach ($asReverseArray as $ssTable => $ssColumn) {
                        if ($snI == 0) {
                            $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                            $this->CI->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                            if (isset($accessControlList['extra_where_condition'])) {
                                $asExtraWhere = $accessControlList['extra_where_condition'];
                                if (count($asExtraWhere) > 0) {
                                    foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                        if ($ssCondition == 'AND') {
                                            $this->CI->db->where($asConditionToAttach);
                                        } elseif ($ssCondition == 'OR') {
                                            $this->CI->db->or_where($asConditionToAttach);
                                        }
                                    }
                                }
                            }
                            $tableToGet = $ssTable;
                        } else {
                            $arrayReverseFromLast = array_reverse($asProcessedTables);
                            $asLastTable = $arrayReverseFromLast[0];
                            $lastTable = $asLastTable['table'];
                            $lastColumn = $asLastTable['column'];
                            $this->CI->db->join($ssTable, "$lastTable.$ssColumn = $ssTable.$ssColumn");
                            $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                        }
                        $snI++;
                    }
                    $ssQuery = $this->CI->db->get($tableToGet);

                    /* print "<pre>";
                      print_r($ssQuery);
                      exit(); */
                }

                $asResult = $ssQuery->result_array();

                if (count($asResult) > 0) {
                    if (count($asResult) == 1) {
                        $snId = $asResult[0][$accessControlList['column_to_check']];
                    } else {
                        foreach ($asResult as $asCustomerIds) {
                            $snId[] = $asCustomerIds[$accessControlList['column_to_check']];
                        }
                    }
                } else {
                    if (count($accessControlList['alternate_table_to_check']) > 0) {
                        $snI = 0;
                        $asReverseArray = $accessControlList['alternate_table_to_check'];
                        $lastTableName = "";
                        $this->CI->db->select($accessControlList['column_to_check']);
                        $ssQuery = "";
                        $asProcessedTables = array();
                        foreach ($asReverseArray as $ssTable => $ssColumn) {
                            if ($snI == 0) {
                                $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                $this->CI->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                if (isset($accessControlList['extra_where_condition'])) {
                                    $asExtraWhere = $accessControlList['extra_where_condition'];
                                    if (count($asExtraWhere) > 0) {
                                        foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                            if ($ssCondition == 'AND') {
                                                $this->CI->db->where($asConditionToAttach);
                                            } elseif ($ssCondition == 'OR') {
                                                $this->CI->db->or_where($asConditionToAttach);
                                            }
                                        }
                                    }
                                }
                                $tableToGet = $ssTable;
                            } else {
                                $arrayReverseFromLast = array_reverse($asProcessedTables);
                                $asLastTable = $arrayReverseFromLast[0];
                                $lastTable = $asLastTable['table'];
                                $lastColumn = $asLastTable['column'];
                                $this->CI->db->join($ssTable, "$lastTable.$lastColumn = $ssTable.$ssColumn");
                            }
                            $snI++;
                        }
                        $ssQuery = $this->CI->db->get($tableToGet);
                        $asResult = $ssQuery->result_array();

                        if (count($asResult) > 0) {
                            if (count($asResult) == 1) {
                                $snId = $asResult[0][$accessControlList['column_to_check']];
                            } else {
                                foreach ($asResult as $asCustomerIds) {
                                    $snId[] = $asCustomerIds[$accessControlList['column_to_check']];
                                }
                            }
                            /* print "<pre>";
                              print_r($ssQuery);
                              exit(); */
                        }
                    }
                }


                $actualUserId = 0;
                $actualUserId = get_current_customer_session_data('id', 1);
                //$actualUserId = "";
                //$actualUserId = 2;
                if (is_array($snId)) {
                    if (!in_array($actualUserId, $snId)) {
                        show_error('You do not have authorization to access other user\'s information.', 403);
                    }
                } else {
                    if (trim($snId) != 0 && $snId != $actualUserId) {
                        show_error('You do not have authorization to access other user\'s information.', 403);
                    }
                }

                return;
            }
        }
    }

    /**
     * 
     * @param type $asValueToCheck
     */
    private function extractValueBasedOnParams($asValueToCheck = array()) {
        $ssValue = 0;
        if (count($asValueToCheck) > 0) {
            $asFirstValue = reset($asValueToCheck); // First Element's Value
            $ssFirstKey = key($asValueToCheck); // First Element's Key
            switch ($ssFirstKey) {
                case 'segment':
                    //echo "Comes here";exit();
                    $ssValue = trim($this->CI->uri->segment($asFirstValue['value']));
                    if ($asFirstValue['is_encrypted']):
                        $ssValue = encrypt_decrypt('decrypt', $ssValue);
                    endif;
                    break;
                case 'post':
                    //echo "Comes here";exit();
                    $ssValue = trim($this->CI->input->post($asFirstValue['value']));
                    if ($asFirstValue['is_encrypted']):
                        $ssValue = encrypt_decrypt('decrypt', $ssValue);
                    endif;
                    break;
            }
        }
        return $ssValue;
    }

    /**
     * Get post variables and check If any link href exist then replace it with our landing page.
     */
    public function showRedirectMessageInAnotherPopup() {
        if ($_POST) {
            $asAllPostParams = $this->CI->input->post(NULL, TRUE);
            foreach ($asAllPostParams as $ssKey => $ssValue) {
                $newValue = $this->updateLinkWithCustomRedirect($ssValue);
                $_POST[$ssKey] = $newValue;
            }
        }
    }

    private function updateLinkWithCustomRedirect($text) {
        //return preg_replace('/\<a href="(.*)">(.*)<\/a>/i', '<a href="{BASE_URL_SITE}?$1">$2</a>', $text);
        return preg_replace('/<\s*a[^>]href="(.*?)"\s?(.*?)\s*>(.*?)<\/a>/i', '<a href="{BASE_URL_SITE}?$1">$3</a>', $text);
    }

    public function changeBaseUrlSiteToActuallOne() {
        $output = $this->CI->output->get_output();
        $output = str_replace("{BASE_URL_SITE}", base_url(), $output);
        $this->CI->output->_display($output);
    }

    public function checkUnderMaintainance() {
        //echo "Helllo";exit();
        if (UNDER_MAINTAINCE) {
            
            $objOutput = $this->CI->load->view('under_mantainance',NULL,TRUE);
            $this->CI->output->_display($objOutput);
            exit();
        }
        return;
    }

}
