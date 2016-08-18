<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/17
 * Time: 上午10:52
 */
namespace Core\Api;

use Help\Out;
use Yaf\Dispatcher;

class MyCon extends \Yaf\Controller_Abstract
{
    protected $_login = true;

    public function init()
    {
        Dispatcher::getInstance()->disableView();
        if ($this->_login)
        {
            $this->checkLogin();
            $this->checkRule();
        }
    }

    protected function checkLogin()
    {
        Out::ajaxReturn(['ok'=>1,'msg'=>'not login']);
    }

    protected function checkRule()
    {
        Out::ajaxReturn(['ok'=>1,'msg'=>'no permission']);
    }
}