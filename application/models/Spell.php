<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SpellModel extends Core\M
{

    protected $_table = 'spell';
    protected $_primaryKey = 'spell_id';

    public function getList()
    {
        $where = array(
            'AND' => array(
                'spell_status' => 1,
                'spell_type' => [1, 2],
            )
        );
        $params = array("spell_id", "spell_name", "spell_type");
        return $this->gets($where, $params);
    }
}