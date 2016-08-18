<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/17
 * Time: 上午11:01
 */
class DemoController extends \Core\Api\MyCon
{
    protected $_login = false;
    public function testAction()
    {
        switch ($this->getRequest()->getMethod())
        {
            case "GET":
                \Help\Out::ajaxReturn(['ok'=>0,'msg'=>$_GET,'raw'=>file_get_contents("php://input")]);
                break;
            case "POST":
                \Help\Out::ajaxReturn(['ok'=>0,'msg'=>$_POST,'raw'=>file_get_contents("php://input")]);
                break;
        }
    }

    public function listAction()
    {

    }

}