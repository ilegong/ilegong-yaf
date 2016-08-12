<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/10/21
 * Time: 下午6:06
 */

namespace Help;


use Yaf\Exception;

class Loader
{
    static public function model($moduleName = '', $modelName = '')
    {
        if ($moduleName == '' || $modelName == '' || class_exists($modelName . 'Model', false)) {
            return false;
        }

        if (file_exists(APP_PATH . '/application/modules/' . $moduleName . '/models/' . $modelName . '.php')) {
            return \Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/models/' . $modelName . '.php');
        } else {
            throw new Exception(\Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/models/' . $modelName . '.php') . "文件不存在");
        }
    }

    static public function api($moduleName = '', $apiName = '')
    {
        if ($moduleName == '' || $apiName == '' || class_exists($apiName, false)) {
            return false;
        }
        if (file_exists(APP_PATH . '/application/modules/' . $moduleName . '/apis/' . $apiName . '.php')) {
            return \Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/apis/' . $apiName . '.php');
        } else {
            throw new Exception(\Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/apis/' . $apiName . '.php') . "文件不存在");
        }
    }
}