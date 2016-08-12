<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/4/5
 * Time: 下午10:22
 */
class IndexModel
{

    protected $_table = NULL;
    protected $_primaryKey = NULL;
    protected $_db = NULL;

    public function __construct()
    {
        $this->_db = \Db\Medoo\Db::getInstance();
    }

    public function getById($id, $params = "*")
    {
        $where = array($this->_primaryKey => $id);
        $result = $this->_db->select($this->_table, $params, $where);
        return $result == null ? false : $result;
    }

    public function gets($where, $params = "*")
    {
        return $this->_db->select($this->_table, $params, $where);
    }

    /**
     * @param $where
     * @return int 行数
     */
    public function count($where)
    {
        return $this->_db->count($this->_table, $where);
    }

    /**
     * @param $where
     * @return int 被删除的行数
     */
    public function del($where)
    {
        return $this->_db->delete($this->_table, $where);
    }

    /**
     * @param $data
     * @param $where
     * @return int 受影响的行数
     */
    public function update($data, $where)
    {
        return $this->_db->update($this->_table, $data, $where);
    }

    public function lastQuery()
    {
        return $this->_db->last_query();
    }

}
