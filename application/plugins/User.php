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
        if ($request->getModuleName() == "Weixin")
        {
            Yaf\Loader::import('LaneWeChat/config.php');
            if(!$request->isCli())
            {
                $response->setHeader($request -> getServer( 'SERVER_PROTOCOL' ), '404 Not Found');
                $response->setBody("page not found");
                $response->response();
                die;
            }
        }
    }


}
