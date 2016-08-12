<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/9/11
 * Time: 下午2:59
 */

namespace Cache;


interface ICache
{
    function set($key, $value, $expire = 0);

    function get($key);

    function delete($key);

    public function hset($name, $key, $value);

    public function hget($name, $key);

    public function hdel($name, $key);

    public function hclear($name);
}