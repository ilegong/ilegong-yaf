<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Observer\Api;

use Core\Observer;
use Help\System;

class Event implements Observer
{

    public function update($event_info = NULL)
    {
        $user = \Yaf\Session::getInstance()->get('api');
        $user_id = $user['user_id'];
        $group_id = $user['ugroup']['usergroup_id'];

        //用户组的策略
        $eventModel = new \EventModel();
        $where = array('event_code' => $event_info['event_code']);
        $strategy = $eventModel->gets($where);
        //event_log
        $where = array(
            'AND' => array(
                'event_id' => $strategy[0]['event_id'],
                'user_id' => $user_id
            )
        );
        $eventLogModel = new \EventLogModel();
        $userModel = new \UserModel();
        $log = $eventLogModel->gets($where);
        if (empty($log)) {
            //没用词条记录,新建
            $insert = array(
                "event_id" => $strategy[0]['event_id'],
                "user_id" => $user_id,
                "total" => 1,
                "cyclenum" => 1,
                "ext_credits_1" => $strategy[0]['ext_credits_1'],
                "ext_credits_2" => $strategy[0]['ext_credits_2'],
                "ext_credits_3" => $strategy[0]['ext_credits_3'],
                "is_read" => 0,
                "starttime" => time(),
                "dateline" => time()
            );
            $eventLogModel->insert($insert);

            //update user credits
            $where = array(
                'user_id' => $user_id
            );
            $data = array(
                "credits[+]" => System::Credits($strategy[0]['ext_credits_1'], $strategy[0]['ext_credits_2'], $strategy[0]['ext_credits_3']),
                "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
            );
            $userModel->update($data, $where);
        } else {
            if ($strategy[0]['cycletype'] == "everyday") {
                if ($log[0]['dateline'] < strtotime(date("Y-m-d"))) {
                    //已经存在,修改
                    $data = array(
                        "total[+]" => 1,
                        "cyclenum" => 1,
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                        "dateline" => time()
                    );
                    $where = array(
                        "event_log_id" => $log[0]['event_log_id']
                    );
                    $eventLogModel->update($data, $where);

                    //update user credits
                    $where = array(
                        'user_id' => $user_id
                    );
                    $data = array(
                        "credits[+]" => System::Credits($strategy[0]['ext_credits_1'], $strategy[0]['ext_credits_2'], $strategy[0]['ext_credits_3']),
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                    );
                    $userModel->update($data, $where);
                } elseif ($log[0]['cyclenum'] < $strategy[0]['rewardnum']) {
                    //已经存在,修改
                    $data = array(
                        "total[+]" => 1,
                        "cyclenum[+]" => 1,
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                        "dateline" => time()
                    );
                    $where = array(
                        "event_log_id" => $log[0]['event_log_id']
                    );
                    $eventLogModel->update($data, $where);

                    //update user credits
                    $where = array(
                        'user_id' => $user_id
                    );
                    $data = array(
                        "credits[+]" => System::Credits($strategy[0]['ext_credits_1'], $strategy[0]['ext_credits_2'], $strategy[0]['ext_credits_3']),
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                    );
                    $userModel->update($data, $where);
                }
            } else {
                if ($log[0]['cyclenum'] < $strategy[0]['rewardnum']) {
                    //已经存在,修改
                    $data = array(
                        "total[+]" => 1,
                        "cyclenum[+]" => 1,
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                        "dateline" => time()
                    );
                    $where = array(
                        "event_log_id" => $log[0]['event_log_id']
                    );
                    $eventLogModel->update($data, $where);

                    //update user credits
                    $where = array(
                        'user_id' => $user_id
                    );
                    $data = array(
                        "credits[+]" => System::Credits($strategy[0]['ext_credits_1'], $strategy[0]['ext_credits_2'], $strategy[0]['ext_credits_3']),
                        "ext_credits_1[+]" => $strategy[0]['ext_credits_1'],
                        "ext_credits_2[+]" => $strategy[0]['ext_credits_2'],
                        "ext_credits_3[+]" => $strategy[0]['ext_credits_3'],
                    );
                    $userModel->update($data, $where);
                }
            }
        }
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