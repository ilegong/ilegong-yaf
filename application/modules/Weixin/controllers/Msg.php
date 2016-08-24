<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/23
 * Time: 下午2:34
 */
class MsgController extends Yaf\Controller_Abstract
{
    public function init()
    {
        Yaf\Dispatcher::getInstance()->disableView();
    }
    public function templateFirstAction()
    {
        $this->send("first");
    }

    public function templateSecondAction()
    {
        $this->send("second");
    }

    public function templateThirdAction()
    {
        $this->send("third");
    }

    private function send($step = "first"){
        $key = "wx_template_{$step}_msg";
        $msg = json_decode(Db\Redis\Redis::getInstance()->rPop($key),true);
        if ($msg) {
            SeasLog::info(json_encode($msg),[],$key);
            $res = LaneWeChat\Core\TemplateMessage::sendTemplateMessage($msg['data'], $msg['touser'], $msg['template_id'], $msg['url'],$msg['topcolor']);
            if ($res['errcode'] != 0) {
                if(in_array($step,["first","second"]))
                {
                    $key = $step == "first" ? "second" : "third";
                    Db\Redis\Redis::getInstance()->lPush($key, json_encode($msg));
                }
                SeasLog::info(json_encode($msg)."|".json_encode($res),[],"wx_template_msg_error");
            }
        }
    }

}