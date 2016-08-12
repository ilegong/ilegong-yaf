<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserLogModel extends Core\M
{

    protected $_table = 'user_activity';
    protected $_primaryKey = 'id';

    public function lists($page, $limit, $where, $params = "*")
    {
        $result = array();
        $join = array(
            '[>]' . $this->_prefix . 'event' => ['event_id' => 'event_id']
        );
        //总条数
        $result["count"] = $this->_db->count($this->_table, $where);
        //列表
        $where['LIMIT'] = array($limit * ($page - 1), $limit);
        $result["rows"] = $this->_db->select($this->_table, $join, $params, $where);

        return $result;
    }

}