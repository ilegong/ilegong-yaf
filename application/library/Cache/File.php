<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Cache;

class File
{

    static public function set($key, $value = NULL, $pre_key = "comm", $life_time = -1)
    {
        $dir = APP_PATH . '/public/data/cache/' . $pre_key;
        $sfile = APP_PATH . '/public/data/cache/' . $pre_key . '/' . md5($key) . '.php';
        $life_time = (-1 == $life_time) ? '300000000' : $life_time;
        $value = '<?php die();?>' . (time() + $life_time) . serialize($value);
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
        return file_put_contents($sfile, $value);
    }

    static public function delete($key, $pre_key = "comm")
    {
        $sfile = APP_PATH . '/public/data/cache/' . $pre_key . '/' . md5($key) . '.php';
        $dir = APP_PATH . '/public/data/cache/' . $pre_key;
        if ($pre_key == "comm") {
            @unlink($sfile);
        } else {
            $dh = opendir($dir);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir . "/" . $file;
                    @unlink($fullpath);
                }
            }
        }
    }

    static public function get($key, $pre_key = "comm")
    {
        $sfile = APP_PATH . '/public/data/cache/' . $pre_key . '/' . md5($key) . '.php';
        if (!is_readable($sfile)) {
            return FALSE;
        }
        $arg_data = file_get_contents($sfile);
        if (substr($arg_data, 14, 10) < time()) {
            @unlink($sfile);
            return FALSE;
        }
        return unserialize(substr($arg_data, 24));
    }

}
