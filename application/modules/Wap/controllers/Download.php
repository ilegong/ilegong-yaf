<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/11/17
 * Time: 下午5:52
 */
class DownloadController extends \Core\Wap\MyCon
{
    public function indexAction()
    {
        $this->setViewPath(APP_PATH.'/application/views/'.$this->getRequest()->getModuleName().'/'.$this->_theme);

        if(\Help\Net::isMobile())
        {
            echo $this->render('index_wap');
        }else{
            echo $this->render('index_pc');
        }
        die;
    }
}