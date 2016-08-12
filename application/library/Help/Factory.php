<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/10/22
 * Time: 下午5:33
 */

namespace Help;


class Factory
{
    static $_model = array();
    static $_api = array();

    static public function model($moduleName = '', $modelName = '')
    {
        if ($moduleName == '' || $modelName == '') {
            return false;
        }
        if (\Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/models/' . $modelName . '.php')) {
            if (!isset(self::$_model[$moduleName . '|' . $modelName])) {
                $tmp = $modelName . 'Model';
                self::$_model[$moduleName . '|' . $modelName] = new $tmp();
            }
            return self::$_model[$moduleName . '|' . $modelName];
        }
        return false;
    }

    static public function api($moduleName = '', $apiName = '')
    {
        if ($moduleName == '' || $apiName == '') {
            return false;
        }
        if (\Yaf\Loader::import(APP_PATH . '/application/modules/' . $moduleName . '/apis/' . $apiName . '.php')) {
            if (!isset(self::$_model[$moduleName . '|' . $apiName])) {
                $tmp = $apiName . 'Api';
                self::$_model[$moduleName . '|' . $apiName] = new $tmp();
            }
            return self::$_model[$moduleName . '|' . $apiName];
        }
        return false;
    }
}