<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Help;

class Security
{

    static public function salt()
    {
        return substr(uniqid(rand()), -9);
    }

    static public function strtosafe($str)
    {
        return strtr($str, array('\\' => '＼', "'" => '＇', '"' => '＂', '<' => '＜', '>' => '＞'));
    }

}
