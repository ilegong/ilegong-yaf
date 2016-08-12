<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/10/23
 * Time: 下午2:58
 */
class Admin_TestController extends \Core\Wap\MyCon
{
    public function indexAction()
    {
        Help\Loader::api('Api' , 'Test');
        $testApi = new TestApi();
        Help\Loader::model('Api' , 'Test');
        $testModel = new TestModel();

        $this->getView()->assign('api' , $testApi->index());

        $this->getView()->assign('model' , $testModel->index());
        $this->out();
    }
}