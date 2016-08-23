<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/5/17
 * Time: 下午10:24
 */

namespace Db\Redis;

class Redis
{

    private $_handle = NULL;
    private $_host = NULL;
    private $_port = NULL;
    private static $conn = NULL;

    private function __construct($option = array("host" => '127.0.0.1', "port" => 6379))
    {
        $this->_host = $option['host'];
        $this->_port = $option['port'];
        $this->_handle = new \Redis();
        $this->_handle->connect($this->_host, $this->_port);
    }

    public static function getInstance()
    {
        if (is_null(self::$conn)) {
            $config = \Yaf\Application::app()->getConfig()->redis->toArray();
            self::$conn = new self($config);
        }
        return self::$conn->_handle;
    }

}
