<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Observer\Api;

use Core\Observer;

class Spell implements Observer
{

    /**
     *
     * @param type $event_info
     * @return boolean
     */
    public function update($event_info = null)
    {

        $user = \Yaf\Session::getInstance()->get('api');
        $user_id = $user['user_id'];
        $maxspell = $user['ugroup']['maxspell'];
        $userSpellModel = new \UserSpellModel();
        $where = array(
            "user_id" => $user_id
        );
        $count = $userSpellModel->count($where);

        if (($count + $event_info['number']) > $maxspell) {
            return false;
        }
        return true;
    }

}