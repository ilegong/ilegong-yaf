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
        $key = "wx_template_first_msg";
        $msg = json_decode(Db\Redis\Redis::getInstance()->rPop($key),true);
        if ($msg) {
            $res = LaneWeChat\Core\TemplateMessage::sendTemplateMessage($msg['data'],$msg['touser'],$msg['template_id'],$msg['url']);
            if($res['errcode'] != 0) {
                Db\Redis\Redis::getInstance()->lPush($key,json_encode($msg));
            }
        }
    }

    public function templateSecondAction()
    {
        $key = "wx_template_second_msg";
        $msg = json_decode(Db\Redis\Redis::getInstance()->rPop($key),true);
        if ($msg) {
            $res = LaneWeChat\Core\TemplateMessage::sendTemplateMessage($msg['data'], $msg['touser'], $msg['template_id'], $msg['url']);
            if ($res['errcode'] != 0) {
                Db\Redis\Redis::getInstance()->lPush($key, json_encode($msg));
            }
        }
    }

    public function templateThirdAction()
    {
        $key = "wx_template_third_msg";
        $msg = json_decode(Db\Redis\Redis::getInstance()->rPop($key),true);
        if($msg) {
            LaneWeChat\Core\TemplateMessage::sendTemplateMessage($msg['data'],$msg['touser'],$msg['template_id'],$msg['url']);
        }
    }

}