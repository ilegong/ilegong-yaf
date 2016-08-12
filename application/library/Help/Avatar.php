<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/1/20
 * Time: 下午6:04
 */

namespace Help;


class Avatar
{
    static function useravatar($uid, $type = "middle")
    {
        $pre_url = \Yaf\Application::app()->getConfig()->pic->url;
        return $pre_url . self::useravatarpath($uid, $type);
    }

    static function useravatarpath($uid, $type = "middle")
    {
        $dir = self::useravatardir($uid);
        if ($name = substr($uid, -2)) {
            $filename = $name . '_avatar';
        } else {
            $filename = $uid . '_avatar';
        }
        if ($type) {
            return $dir . $filename . "_{$type}.jpg";
        } else {
            return $dir . $filename . ".jpg";
        }
    }

    static function userres($uid, $type = "bg", $ext = 'css')
    {
        $pre_url = \Yaf\Application::app()->getConfig()->pic->url;
        return $pre_url . self::userrespath($uid, $type, $ext);
    }

    static function userrespath($uid, $type = "bg", $ext = 'css')
    {
        $dir = self::useravatardir($uid);
        if ($name = substr($uid, -2)) {
            $filename = $name;
        } else {
            $filename = $uid;
        }
        return $dir . $filename . "_{$type}.{$ext}";
    }

    static function useravatardir($uid)
    {
        $uid = abs(intval($uid));
        $uid = sprintf("%09d", $uid);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $dir = '/upload/avatars/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
        return $dir;
    }

    static function encode($str)
    {
        return str_replace(array('+', ' ', '/', '='), array('S0U0M', 'E0P0T0Y', 'P0OL0E', 'P9DK8G'), base64_encode($str));
    }

    static function decode($str)
    {
        return base64_decode(str_replace(array('S0U0M', 'E0P0T0Y', 'P0OL0E', 'P9DK8G'), array('+', ' ', '/', '='), $str));
    }
}