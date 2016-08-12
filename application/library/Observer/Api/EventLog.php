<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Observer\Api;

use Core\Observer;
use Help\System;

class EventLog implements Observer
{

    public function update($event_info = NULL)
    {
        $user = \Yaf\Session::getInstance()->get('api');
        $user_id = $user['user_id'];

        //用户组的策略
        $eventModel = new \EventModel();
        $where = array('event_code' => $event_info['event_code']);
        $strategy = $eventModel->gets($where);


        //add user_activity log
        $userActivityModel = new \UserActivityModel();
        $insert = array(
            "event_id" => $strategy[0]['event_id'],
            "user_id" => $user_id,
            "create_time" => time()
        );
        $userActivityModel->insert($insert);
    }
}