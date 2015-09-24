<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of BASE_model
 *
 * @author Hardik Patel <hardik@techdefence.com>
 */
class BASE_model extends CI_Model
{

    protected $tableName = '';
    protected $alias = "";
    protected $primaryKey = '';
    protected $distinct;
    protected $group_by;
    protected $select = array();
    protected $joins = array();
    protected $columns = array();
    protected $where = array();

    function __construct($tableName = "", $alias = "", $primaryKey = "")
    {
        $this->tableName = $tableName;
        $this->alias = $alias;
        $this->primaryKey = $primaryKey;
        parent :: __construct();
    }

    /**
     * Function to get Table name
     * @return string
     */
    function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Function to insert data in table
     * 
     * @param array $asData            
     * @return boolean / int
     */
    function insertData($asData)
    {
        if (!empty($asData))
        {
            $snId = $this->db->insert($this->tableName, $asData);
            if ($snId)
            {
                return $this->db->insert_id($this->tableName, $asData);
            }
            return false;
        }
    }

    /**
     * Function to get all active data
     * @return boolean / array
     */
    function getAllData($activeColumn = "")
    {
        $column = "status";
        $asResult = array();
        if (isset($activeColumn) && $activeColumn != "")
        {
            $column = $activeColumn;
            $ssWhere .= ' WHERE ' . $this->alias . '.' . $column . ' = "1"';
        }
        $asResult = array();
        $ssQuery = $this->db->query('SELECT ' . $this->alias . '.* FROM ' . $this->tableName . ' as ' . $this->alias . ' WHERE ' . $this->alias . '.' . $column . ' = "1"');
        if ($ssQuery->num_rows() > 0)
        {

            $asResult = $ssQuery->result_array();
            return $asResult;
        }
        return $asResult;
    }

    /**
     * Function to get active data by id
     * @return boolean / array
     */
    function getActiveDataById($snId, $activeColumn = "")
    {
        $column = "status";
        if (isset($activeColumn) && $activeColumn != "")
        {
            $column = $activeColumn;
        }
        $asResult = array();

        if ($snId != '' && is_numeric($snId))
        {
            $ssQuery = $this->db->query('SELECT ' . $this->alias . '.* FROM ' . $this->tableName . ' as ' . $this->alias . ' WHERE ' . $this->alias . '.' . $column . ' = "1" AND ' . $this->alias . '.' . $this->primaryKey . ' = ' . $snId . ' LIMIT 1');
            if ($ssQuery->num_rows() > 0)
            {
                $asResult = $ssQuery->row_array();
                return $asResult;
            }
        }
        return false;
    }

    /**
     * Function to update data
     * @param array $asData
     * @param int $snId
     * @return boolean
     */
    function updateData($asData, $snId, $ssField = '')
    {
        if (!empty($asData) && $snId != '' && is_numeric($snId))
        {
            if ($ssField != '')
                $this->db->where($ssField, $snId);
            else
                $this->db->where($this->primaryKey, $snId);
            $this->db->update($this->tableName, $asData);
            return TRUE;
        }
        return false;
    }

    /**
     * Function to fetch data by the query
     * @param type $ssQuery
     * @param type $ssResult
     * @return boolean
     */
    function getResult($ssQuery, $ssResult = TRUE)
    {
        $asResult = array();
        if ($ssQuery != '')
        {
            $ssQuery = $this->db->query($ssQuery);
            if ($ssQuery->num_rows() > 0)
            {
                if ($ssResult == TRUE)
                    $asResult = $ssQuery->result_array();
                else
                    $asResult = $ssQuery->row_array();
                return $asResult;
            }
        }
        return array();
    }

    /**
     * Ruturn total count result of given table.
     * @return resultset
     */
    function getTotal()
    {
        return $this->db->count_all_results($this->tableName);
    }

    /**
     * Function to delete record from table
     * 
     * @param unknown $snId        	
     * @return boolean
     */
    function deleteRecord($snId)
    {
        if ($snId != "" && is_numeric($snId))
        {
            $this->db->where($this->primaryKey, $snId);
            $this->db->delete($this->tableName);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Generates the SELECT portion of the query
     *
     * @param string $columns
     * @param bool $backtick_protect
     * @return mixed
     */
    public function select($columns, $backtick_protect = TRUE)
    {

        foreach ($this->explode(',', $columns) as $val)
        {
            $column = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$2', $val));
            $this->columns[] = $column;
            $this->select[$column] = trim(preg_replace('/(.*)\s+as\s+(\w*)/i', '$1', $val));
        }

        $this->db->select($columns, $backtick_protect);
        return $this;
    }

    /**
     * Generates the DISTINCT portion of the query
     *
     * @param string $column
     * @return mixed
     */
    public function distinct($column)
    {
        $this->distinct = $column;
        $this->db->distinct($column);
        return $this;
    }

    /**
     * Generates the GROUP_BY portion of the query
     *
     * @param string $column
     * @return mixed
     */
    public function group_by($column)
    {
        $this->group_by = $column;
        $this->db->group_by($column);
        return $this;
    }

    /**
     * Genereates the orderby query
     * @param type $column
     * @return \Datatables
     */
    public function order_by($column)
    {
        $this->order_by = $column;
        $this->db->order_by($column);
        return $this;
    }

    /**
     * 
     * @param type $snLimit
     * @return \BASE_model
     */
    public function limit($snLimit)
    {

        $this->db->limit($snLimit);
        return $this;
    }

    /**
     * Generates the FROM portion of the query
     *
     * @param string $table
     * @return mixed
     */
    public function from($table)
    {
        $this->tableName = $table;
        $this->db->from($table);
        return $this;
    }

    /**
     * Generates the JOIN portion of the query
     *
     * @param string $table
     * @param string $fk
     * @param string $type
     * @return mixed
     */
    public function join($table, $fk, $type = NULL)
    {
        $this->joins[] = array($table, $fk, $type);
        $this->db->join($table, $fk, $type);
        return $this;
    }

    /**
     * Generates the WHERE portion of the query
     *
     * @param mixed $key_condition
     * @param string $val
     * @param bool $backtick_protect
     * @return mixed
     */
    public function where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->db->where($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Generates the WHERE portion of the query
     *
     * @param mixed $key_condition
     * @param string $val
     * @param bool $backtick_protect
     * @return mixed
     */
    public function or_where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->db->or_where($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Generates the WHERE portion of the query
     *
     * @param mixed $key_condition
     * @param string $val
     * @param bool $backtick_protect
     * @return mixed
     */
    public function like($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->db->like($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Explode, but ignore delimiter until closing characters are found
     *
     * @param string $delimiter
     * @param string $str
     * @param string $open
     * @param string $close
     * @return mixed $retval
     */
    protected function explode($delimiter, $str, $open = '(', $close = ')')
    {
        $retval = array();
        $hold = array();
        $balance = 0;
        $parts = explode($delimiter, $str);

        foreach ($parts as $part)
        {
            $hold[] = $part;
            $balance += $this->balanceChars($part, $open, $close);

            if ($balance < 1)
            {
                $retval[] = implode($delimiter, $hold);
                $hold = array();
                $balance = 0;
            }
        }

        if (count($hold) > 0)
            $retval[] = implode($delimiter, $hold);

        return $retval;
    }

    /**
     * Return the difference of open and close characters
     *
     * @param string $str
     * @param string $open
     * @param string $close
     * @return string $retval
     */
    protected function balanceChars($str, $open, $close)
    {
        $openCount = substr_count($str, $open);
        $closeCount = substr_count($str, $close);
        $retval = $openCount - $closeCount;
        return $retval;
    }

    /**
     * 
     * @return type
     */
    protected function get_display_result()
    {
        $data = $this->db->get($this->tableName);
        return $data;
    }

    /**
     * 
     * @return type array
     */
    public function get_result_array()
    {
        $data = $this->db->get($this->tableName);
        return $data->result_array();
    }

    /**
     * 
     * @return type array
     */
    public function get_row_array()
    {
        $data = $this->db->get();
        return $data->row_array();
    }

}
