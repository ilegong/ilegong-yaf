<?php
/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/17
 * Time: 下午2:24
 */

$workers = [];
$worker_num = 5;

swoole_timer_tick(1000,'onTime');

function onTime($time_id){
    //取数据
    global $worker_num,$workers;

    for ($i = 0; $i < $worker_num;$i++)
    {
        $process = new swoole_process(function(swoole_process $worker){
            $params = [
                "/Users/ellipsis/Apps/ilegong-yaf/public/cli.php",
                'request_uri="/weixin/demo/test/id/'.time().$worker->pid.'"'
            ];
            $worker->exec("/usr/local/bin/php",$params);
        },false,false);
        $process->start();
        $workers[$process->pid] = $process;
        $process->wait(false);
    }
}