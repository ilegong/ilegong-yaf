<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/19
 * Time: 上午11:10
 */
class UserController extends \Core\Api\MyCon
{
    public function testAction()
    {
        \Help\Out::ajaxReturn(['id'=>$this->_userId]);
    }
}