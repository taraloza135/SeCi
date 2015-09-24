<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

trait checkAccessForUser {

    /**
     * 
     */
    public function denyWrongPersonAccess($snIdCustomer = 0) {


        if ($snIdCustomer > 0) {

            $asAuthenticationCheckList = $this->load->config('acl', TRUE);
            $asAuthenticationCheckList = $asAuthenticationCheckList['authetication_access_api'];

            $ssController = $this->router->fetch_method();
            $ssMethod = $this->request->method;
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
                            $this->db->select($accessControlList['column_to_check']);
                            $ssQuery = "";
                            $asProcessedTables = array();
                            foreach ($asReverseArray as $ssTable => $ssColumn) {
                                if ($snI == 0) {
                                    $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                    $this->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                    if (isset($accessControlList['extra_where_condition'])) {
                                        $asExtraWhere = $accessControlList['extra_where_condition'];
                                        if (count($asExtraWhere) > 0) {
                                            foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                                if ($ssCondition == 'AND') {
                                                    $this->db->where($asConditionToAttach);
                                                } elseif ($ssCondition == 'OR') {
                                                    $this->db->or_where($asConditionToAttach);
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
                                    $this->db->join($ssTable, "$lastTable.$ssColumn = $ssTable.$ssColumn");
                                    $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                }
                                $snI++;
                            }
                            $ssQuery = $this->db->get($tableToGet);

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
                                $this->db->select($accessControlList['column_to_check']);
                                $ssQuery = "";
                                $asProcessedTables = array();
                                foreach ($asReverseArray as $ssTable => $ssColumn) {
                                    if ($snI == 0) {
                                        $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                        $this->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                        if (isset($accessControlList['extra_where_condition'])) {
                                            $asExtraWhere = $accessControlList['extra_where_condition'];
                                            if (count($asExtraWhere) > 0) {
                                                foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                                    if ($ssCondition == 'AND') {
                                                        $this->db->where($asConditionToAttach);
                                                    } elseif ($ssCondition == 'OR') {
                                                        $this->db->or_where($asConditionToAttach);
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
                                        $this->db->join($ssTable, "$lastTable.$lastColumn = $ssTable.$ssColumn");
                                    }
                                    $snI++;
                                }
                                $ssQuery = $this->db->get($tableToGet);
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
                        $this->db->select($accessControlList['column_to_check']);
                        $ssQuery = "";
                        $asProcessedTables = array();
                        foreach ($asReverseArray as $ssTable => $ssColumn) {
                            if ($snI == 0) {
                                $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                $this->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                if (isset($accessControlList['extra_where_condition'])) {
                                    $asExtraWhere = $accessControlList['extra_where_condition'];
                                    if (count($asExtraWhere) > 0) {
                                        foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                            if ($ssCondition == 'AND') {
                                                $this->db->where($asConditionToAttach);
                                            } elseif ($ssCondition == 'OR') {
                                                $this->db->or_where($asConditionToAttach);
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
                                $this->db->join($ssTable, "$lastTable.$ssColumn = $ssTable.$ssColumn");
                                $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                            }
                            $snI++;
                        }
                        $ssQuery = $this->db->get($tableToGet);

                        /* print "<pre>";
                          print_r($ssQuery);
                          exit(); */
                    }

                    $asResult = $ssQuery->result_array();
                    //print_r($asResult);exit();

                    if (count($asResult) > 0) {
                        if (count($asResult) == 1) {
                            //echo $accessControlList['column_to_check'];exit();
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
                            $this->db->select($accessControlList['column_to_check']);
                            $ssQuery = "";
                            $asProcessedTables = array();
                            foreach ($asReverseArray as $ssTable => $ssColumn) {
                                if ($snI == 0) {
                                    $asProcessedTables[] = array('table' => $ssTable, 'column' => $ssColumn);
                                    $this->db->where($ssTable . "." . $ssColumn, $ssValueGot);
                                    if (isset($accessControlList['extra_where_condition'])) {
                                        $asExtraWhere = $accessControlList['extra_where_condition'];
                                        if (count($asExtraWhere) > 0) {
                                            foreach ($asExtraWhere as $ssCondition => $asConditionToAttach) {
                                                if ($ssCondition == 'AND') {
                                                    $this->db->where($asConditionToAttach);
                                                } elseif ($ssCondition == 'OR') {
                                                    $this->db->or_where($asConditionToAttach);
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
                                    $this->db->join($ssTable, "$lastTable.$lastColumn = $ssTable.$ssColumn");
                                }
                                $snI++;
                            }
                            $ssQuery = $this->db->get($tableToGet);
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
                $actualUserId = $snIdCustomer;
                //$actualUserId = "";
                //$actualUserId = 2;
                //echo $snId;exit();
                if (is_array($snId)) {
                    if (!in_array($actualUserId, $snId)) {
                        $this->response(array('error' => 'You do not have authorization to access other user\'s information.'), 403);
                    }
                } else {
                    if (trim($snId) != 0 && $snId != $actualUserId) {
                        $this->response(array('error' => 'You do not have authorization to access other user\'s information.'), 403);
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
    function extractValueBasedOnParams($asValueToCheck = array()) {
        $ssValue = 0;
        if (count($asValueToCheck) > 0) {
            $asFirstValue = reset($asValueToCheck); // First Element's Value
            $ssFirstKey = key($asValueToCheck); // First Element's Key
            switch ($ssFirstKey) {
                case 'segment':
                    $ssValue = trim($this->uri->segment($asFirstValue['value']));
                    if ($asFirstValue['is_encrypted']):
                        $ssValue = $this->encrypt_decrypt('decrypt', $ssValue);
                    endif;
                    break;
                case 'post':
                    //echo "Comes here";exit();
                    $ssValue = trim($this->input->post($asFirstValue['value']));
                    if ($asFirstValue['is_encrypted']):
                        $ssValue = $this->encrypt_decrypt('decrypt', $ssValue);
                    endif;
                    break;
            }
        }
        return $ssValue;
    }

}
