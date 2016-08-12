<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 2015/5/14
 * Time: 13:36
 */
class WxController extends Yaf\Controller_Abstract
{

    /**
     * 调试模式，将错误通过文本消息回复显示
     * @var boolean
     */
    private $debug;

    /**
     * 以数组的形式保存微信服务器每次发来的请求
     * @var array
     */
    private $request;

    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     * @param string $token 验证信息
     * @param boolean $debug 调试模式，默认为关闭
     */
    public function init($token, $debug = FALSE)
    {
        Yaf\Loader::import('LaneWeChat/config.php');
        Yaf\Dispatcher::getInstance()->disableView();

        //未通过消息真假性验证
        if ($this->isValidAction() && $this->validateSignatureAction($token)) {
            return $this->getRequest()->get("echostr");
        }
        //是否打印错误报告
        $this->debug = $debug;
        //接受并解析微信中心POST发送XML数据
        $xml = (array)simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);

        //将数组键名转换为小写
        $this->request = array_change_key_case($xml, CASE_LOWER);
    }

    /**
     * 判断此次请求是否为验证请求
     * @return boolean
     */
    private function isValidAction()
    {
        $tmp = $this->getRequest()->get("echostr");
        return isset($tmp);
    }

    /**
     * 判断验证请求的签名信息是否正确
     * @param  string $token 验证信息
     * @return boolean
     */
    private function validateSignatureAction($token)
    {
        $signature = $this->getRequest()->get("signature");
        $timestamp = $this->getRequest()->get("timestamp");
        $nonce = $this->getRequest()->get("nonce");
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray)) == $signature;
    }

    /**
     * 获取本次请求中的参数，不区分大小
     * @param  string $param 参数名，默认为无参
     * @return mixed
     */
    protected function getWXRequestAction($param = FALSE)
    {
        if ($param === FALSE) {
            return $this->request;
        }
        $param = strtolower($param);
        if (isset($this->request[$param])) {
            return $this->request[$param];
        }
        return NULL;
    }

    /**
     * 分析消息类型，并分发给对应的函数
     * @return void
     */
    public function runAction()
    {
        //第一次验证
        return $this->checkSignatureAction();
        $res = \LaneWeChat\Core\WeChatRequest::switchType($this->request);

        echo $res;
    }

    public function checkSignatureAction()
    {
        $signature = $this->getRequest()->get("signature");
        $timestamp = $this->getRequest()->get("timestamp");
        $nonce = $this->getRequest()->get("nonce");

        $token = WECHAT_TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            echo $this->getRequest()->get("echostr");
            return true;
        } else {
            return false;
        }
    }

}
