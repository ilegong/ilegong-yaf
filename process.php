<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/17
 * Time: 下午2:24
 */

swoole_timer_tick(1000,'onFirst');
swoole_timer_tick(10000,'onSecond');
swoole_timer_tick(60000,'onThird');


function onFirst(){
    for ($i = 0; $i < 5 ;$i++)
    {
        $process = new swoole_process(function(swoole_process $worker){
            $params = [
                __DIR__."/public/cli.php",
                "request_uri=/weixin/msg/templatefirst"
            ];
            $worker->exec("php",$params);
        },false,false);
        $process->start();
        $process->wait(false);
    }
}

function onSecond(){
    for ($i = 0; $i < 3;$i++)
    {
        $process = new swoole_process(function(swoole_process $worker){
            $params = [
                __DIR__."/public/cli.php",
                "request_uri=/weixin/msg/templatesecond"
            ];
            $worker->exec("php",$params);
        },false,false);
        $process->start();
        $process->wait(false);
    }
}

function onThird(){
    for ($i = 0; $i < 2;$i++)
    {
        $process = new swoole_process(function(swoole_process $worker){
            $params = [
                __DIR__."/public/cli.php",
                "request_uri=/weixin/msg/templatethird"
            ];
            $worker->exec("php",$params);
        },false,false);
        $process->start();
        $process->wait(false);
    }
}