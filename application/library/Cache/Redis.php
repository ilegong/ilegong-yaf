<?php

/**
 * 使用Redis作为Cache
 * Class Redis
 *
 */
namespace Cache;

class Redis implements ICache
{
    protected $redis;

    function __construct()
    {
        $this->redis = \Db\Redis\Redis::getInstance();
    }

    /**
     * 设置缓存
     * @param $key
     * @param $value
     * @param $expire
     * @return bool
     */
    function set($key, $value, $expire = 0)
    {
        return $this->redis->setex($key, $expire, $value);
    }

    /**
     * 获取缓存值
     * @param $key
     * @return mixed
     */
    function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 删除缓存值
     * @param $key
     * @return bool
     */
    function delete($key)
    {
        return $this->redis->del($key);
    }

    public function hset($name, $key, $value)
    {
        return $this->redis->hset($name, $key, $value);
    }

    public function hget($name, $key)
    {
        return $this->redis->hget($name, $key);
    }

    public function hdel($name, $key)
    {
        return $this->redis->hdel($name, $key);
    }

    public function hclear($name)
    {
        return $this->redis->hclear($name);
    }
}