<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 15/4/12
 * Time: 下午1:34
 */
class UserPlugin extends Yaf\Plugin_Abstract
{
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        Yaf\Session::getInstance()->set('moduleName', $request->getModuleName());
        Yaf\Session::getInstance()->set('controllerName', $request->getControllerName());
        Yaf\Session::getInstance()->set('actionName', $request->getActionName());

        if ($request->getModuleName() == "Index" && $request->getControllerName() == "Admin" && $request->getActionName() == "index") {
            $request->setControllerName("Index");
            $request->setActionName("index");
            $request->setModuleName("Admin");
        }
        if ($request->getModuleName() == "Api")
        {
            $request->setActionName($request->getMethod().$request->getActionName());
        }

        if ($request->getModuleName() == "Weixin")
        {
            Yaf\Loader::import('LaneWeChat/config.php');
            //Yaf\Loader::import(APP_PATH."/conf/config.php");
        }
    }


}
