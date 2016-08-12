<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/9/11
 * Time: 下午3:37
 */

namespace Db\SSDB;


class SSDB
{
    static private $_con = null;

    static public function getInstance()
    {
        if(is_null(self::$_con))
        {
            $port = \Yaf\Application::app()->getConfig()->ssdb->port;
            self::$_con = new SimpleClient("127.0.0.1", $port);
        }

        return self::$_con;
    }

}