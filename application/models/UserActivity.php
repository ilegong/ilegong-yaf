<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserActivityModel extends \Core\M
{

    protected $_table = "user_activity";

    public function listsWithUserName($page, $limit, $where)
    {
        $result = array();
        $join = array(
            '[>]' . $this->_prefix . 'user' => ['user_id' => 'user_id']
        );
        $params = array(
            $this->_table . ".id",
            $this->_table . ".event_id",
            $this->_table . ".user_id",
            $this->_table . ".create_time",
            $this->_prefix . "user.nickname"
        );
        //总条数
        $result["count"] = $this->_db->count($this->_table, $where);
        //列表
        $where['LIMIT'] = array($limit * ($page - 1), $limit);
        $result["rows"] = $this->_db->select($this->_table, $join, $params, $where);
        //echo $this->_db->last_query();die;
        return $result;
    }
}
