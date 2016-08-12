<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/9/17
 * Time: 上午10:41
 */
class FeedbackModel extends \Core\M
{
    protected $_table = 'feedback';

    public function listsWithUserName($page, $limit, $where, $params = "*")
    {
        $result = array();
        $join = array(
            '[>]' . $this->_prefix . 'user' => ['uid' => 'user_id']
        );
        $params = array(
            $this->_table . ".id",
            $this->_table . ".uid",
            $this->_table . ".content",
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