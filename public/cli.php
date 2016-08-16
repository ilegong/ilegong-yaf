<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/4/1
 * Time: 下午11:57
 */
//error_reporting(E_ALL );
//ini_set('display_errors', 'on');

date_default_timezone_set('Asia/Shanghai');

define("APP_PATH", realpath(dirname(__FILE__) . '/../'));
define("APPLICATION_PATH", realpath(dirname(__FILE__) . '/../'));
define("CONF_PATH",realpath(APP_PATH."/conf/"));
define("VENDOR_PATH",realpath(APP_PATH."/vendor/"));

require_once VENDOR_PATH."/autoload.php";

try {
    $app = new \Yaf\Application(APP_PATH . "/conf/application.ini");
    $app->bootstrap();
    $app->getDispatcher()->dispatch(new Yaf\Request\Simple());
} catch (Exception $e) {
    print $e->getMessage();
}