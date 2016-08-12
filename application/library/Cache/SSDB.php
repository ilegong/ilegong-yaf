<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/9/11
 * Time: 下午3:06
 */

namespace Cache;


class SSDB implements ICache
{
    protected $_ssdb;

    function __construct()
    {
        $this->_ssdb = \Db\SSDB\SSDB::getInstance();
    }

    function set($key, $value, $expire = 0)
    {
        return $this->_ssdb->setx($key, $value, $expire);
    }

    function get($key)
    {
        return $this->_ssdb->get($key);
    }

    function delete($key)
    {
        return $this->_ssdb->del($key);
    }

    public function hset($name, $key, $value)
    {
        return $this->_ssdb->hset($name, $key, $value);
    }

    public function hget($name, $key)
    {
        return $this->_ssdb->hget($name, $key);
    }

    public function hdel($name, $key)
    {
        return $this->_ssdb->hdel($name, $key);
    }

    public function hclear($name)
    {
        return $this->_ssdb->hclear($name);
    }
}