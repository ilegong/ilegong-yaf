<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UserSpellModel extends Core\M
{

    protected $_table = 'user_spell';
    protected $_primaryKey = 'us_id';


    public function gets($where, $params = "*")
    {
        $join = array(
            '[>]' . $this->_prefix . 'spell' => ['spell_id' => 'spell_id']
        );
        if ($params == "*") {
            $params = array(
                "fm_user_spell.us_id",
                "fm_user_spell.spell_id",
                "fm_user_spell.user_id",
                "fm_user_spell.description_",
                "fm_user_spell.defined_name",
                "fm_user_spell.begin_number",
                "fm_user_spell.read_total",
                "fm_user_spell.read_last",
                "fm_user_spell.read_lasttime",
                "fm_user_spell.schedule_total",
                "fm_user_spell.schedule_day",
                "fm_user_spell.remind_open",
                "fm_user_spell.remind_time",
                "fm_user_spell.create_time",
                "fm_user_spell.hold_status",
                "fm_user_spell.hold_days",
                "fm_user_spell.display_order",
                "fm_spell.spell_name",
                "fm_spell.spell_status",
                "fm_spell.spell_type"
            );
        }

        return $this->_db->select($this->_table, $join, $params, $where);
    }

    public function getss($where, $params = "*")
    {
        return parent::gets($where, $params);
    }


    public function listsWithName($page, $limit, $where, $params = "*")
    {
        $result = array();
        $join = array(
            '[>]' . $this->_prefix . 'spell' => ['spell_id' => 'spell_id']
        );
        $where['ORDER'] = isset($where['ORDER']) ? $where['ORDER'] : $this->_primaryKey . " DESC";
        //总条数
        $result["count"] = $this->_db->count($this->_table, $where);
        //列表
        $where['LIMIT'] = array($limit * ($page - 1), $limit);
        $result["rows"] = $this->_db->select($this->_table, $join, $params, $where);
        return $result;
    }

}