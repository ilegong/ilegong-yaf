<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/5/17
 * Time: 下午8:00
 */
class DemoController extends Yaf\Controller_Abstract
{

    private $_tousername = "oY-rFuCeF-EwMGO27Peno_84nn3A";

    public function init()
    {
        Yaf\Dispatcher::getInstance()->disableView();
    }

    public function testAction()
    {
        echo WECHAT_APPID;
        die;
    }

    public function setAction()
    {
        Db\Redis\Redis::getInstance()->set('wx_access_token',"y8I8WUnIiChjQpy-J-B7hY7aKnI7fqBU8yKBoEUVMQtdEydb3g7daCAmhr-zuEMRKniJJvrYrgIJQ-c81hQt7wAzuDJaLmGNos-TiACN5dArdLWYX3rZR4TNa7KUUv7gKYIhAAAOCM");
    }

    public function getAction()
    {
        var_dump(\Db\Redis\Redis::getInstance()->get("wx_access_token"));
    }

    public function delAction()
    {
        var_dump(\Db\Redis\Redis::getInstance()->del("wx_access_token"));
    }

    public function wxAction()
    {
        $redirect_uri = 'http://wx.tongshijia.com/weixin/demo/wxlogin';
        \LaneWeChat\Core\WeChatOAuth::getCode($redirect_uri, $state = 1, $scope = 'snsapi_userinfo');
    }

    public function wxLoginAction()
    {
        $code = $_GET['code'];
        $accessTokenAndOpenId = \LaneWeChat\Core\WeChatOAuth::getAccessTokenAndOpenId($code);
        $openId = $accessTokenAndOpenId['openid'];
        $accessToken = $accessTokenAndOpenId['access_token'];
        $userinfo = \LaneWeChat\Core\WeChatOAuth::getUserInfo($accessToken, $openId);
        var_dump($userinfo);
        die;
    }

    public function pushAction()
    {
        var_dump(\LaneWeChat\Core\ResponseInitiative::text($this->_tousername, "你好吗"));
    }

    public function userinfoAction()
    {
        $res = \LaneWeChat\Core\UserManage::getUserInfo($this->_tousername);
        var_dump($res);
    }

    public function menuAction()
    {
        //设置菜单
        $menuList = array(
            array('id' => '1', 'pid' => '', 'name' => '常规', 'type' => '', 'code' => 'key_1'),
            array('id' => '2', 'pid' => '1', 'name' => '点击', 'type' => 'click', 'code' => 'key_2'),
            array('id' => '3', 'pid' => '1', 'name' => '浏览', 'type' => 'view', 'code' => 'http://wx.tongshijia.com'),
            array('id' => '4', 'pid' => '', 'name' => '扫码', 'type' => '', 'code' => 'key_4'),
            array('id' => '5', 'pid' => '4', 'name' => '扫码带提示', 'type' => 'scancode_waitmsg', 'code' => 'key_5'),
            array('id' => '6', 'pid' => '4', 'name' => '扫码推事件', 'type' => 'scancode_push', 'code' => 'key_6'),
            array('id' => '7', 'pid' => '', 'name' => '发图', 'type' => '', 'code' => 'key_7'),
            array('id' => '8', 'pid' => '7', 'name' => '系统拍照发图', 'type' => 'pic_sysphoto', 'code' => 'key_8'),
            array('id' => '9', 'pid' => '7', 'name' => '拍照或者相册发图', 'type' => 'pic_photo_or_album', 'code' => 'key_9'),
            array('id' => '10', 'pid' => '7', 'name' => '微信相册发图', 'type' => 'pic_weixin', 'code' => 'key_10'),
            array('id' => '11', 'pid' => '1', 'name' => '发送位置', 'type' => 'location_select', 'code' => 'key_11'),
        );
        $res = \LaneWeChat\Core\Menu::setMenu($menuList);
        var_dump($res);
    }

    public function templateMsgAction()
    {
        $res = LaneWeChat\Core\TemplateMessage::sendTemplateMessage([],$this->_tousername,'AmO0edPBLHvgt0AlEOQjD9gHfY85KMIRW8dLvqZmFQY',"");
        var_dump($res);
    }

    public function dbAction()
    {
        \Help\Loader::model('Weixin','Weshare');
        $weshareModel = new WeshareModel();
        $res = $weshareModel->getById(15);
        var_export($res);
    }
}
