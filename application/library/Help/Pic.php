<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/1/10
 * Time: 下午5:18
 */

namespace Help;


class Pic
{
    static public function getFullPath($path, $remote = '')
    {
        $pre = $remote == '' ? \Yaf\Application::app()->getConfig()->pic->url : $remote;

        if ($path[0] == '/') {
            $path = $pre . $path;
        }
        return $path;
    }
}