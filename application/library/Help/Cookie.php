<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Help;

class Cookie
{

    static public function del($key)
    {
        self::set($key, '', time() - 3600);
    }

    static public function set($key, $value, $expire = 3600)
    {
        setcookie($key, $value, time() + $expire);
    }

    static public function get($key)
    {
        return trim($_COOKIE[$key]);
    }
}