<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Help;

class Out
{

    static public function ajaxReturn($arr)
    {
        header('Content-type: application/json');

        exit(json_encode($arr));
    }

}