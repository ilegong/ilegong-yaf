<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/7/19
 * Time: 下午6:07
 */

namespace WxPay\Core;

/**
 * 对账单接口
 */
class DownloadBill extends WxpayClient
{

    function __construct()
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/downloadbill";
        //设置curl超时时间
        $this->curl_timeout = WxPayConf::CURL_TIMEOUT;
    }

    /**
     * 生成接口参数xml
     */
    function createXml()
    {
        try {
            if ($this->parameters["bill_date"] == null) {
                throw new SDKRuntimeException("对账单接口中，缺少必填参数bill_date！" . "<br>");
            }
            $this->parameters["appid"] = WxPayConf::APPID; //公众账号ID
            $this->parameters["mch_id"] = WxPayConf::MCHID; //商户号
            $this->parameters["nonce_str"] = $this->createNoncestr(); //随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters); //签名
            return $this->arrayToXml($this->parameters);
        } catch (SDKRuntimeException $e) {
            die($e->errorMessage());
        }
    }

    /**
     *    作用：获取结果，默认不使用证书
     */
    function getResult()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->result_xml);
        return $this->result;
    }

}
