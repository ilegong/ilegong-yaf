<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/7/19
 * Time: 下午6:12
 */

namespace WxPay\Core;

class SDKRuntimeException extends \Exception
{

    public function errorMessage()
    {
        return $this->getMessage();
    }

}
