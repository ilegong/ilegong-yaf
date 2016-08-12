<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/5/16
 * Time: 上午12:50
 */

namespace LaneWeChat\Core;

class Test
{

    public static function index()
    {
        $adminModel = new \AdminModel();
        return $adminModel->getById(1);
    }

}
