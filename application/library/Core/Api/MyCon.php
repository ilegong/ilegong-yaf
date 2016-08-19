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
    protected $_oauthServer = null;
    protected $_userId = 0;
    protected $_oauthStorage = null;

    public function init()
    {
        Dispatcher::getInstance()->disableView();

        $mysql = \Yaf\Application::app()->getConfig()->mysql->toArray();
        $config = [
            'dsn' => "mysql:dbname={$mysql['database_name']};host={$mysql['server']}",
            'username' => $mysql['username'],
            'password' => $mysql['password']
        ];

        $this->_oauthStorage = new \OAuth2\Storage\Pdo($config);
        $this->_oauthServer = new \OAuth2\Server($this->_oauthStorage);

        if ($this->_login)
        {
            $this->oauth();
            $this->checkRule();
        }
    }

    protected function oauth()
    {
        if(!@$_REQUEST['access_token'])
        {
            Out::ajaxReturn(['error'=>'empty_token',"error_description"=>'The access token provided is empty']);
        }
        if (!$this->_oauthServer->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $this->_oauthServer->getResponse()->send();
            die;
        }

        $token = $this->_oauthServer->getAccessTokenData(\OAuth2\Request::createFromGlobals());
        $this->_userId = $token['user_id'];
    }

    protected function checkRule()
    {
        //Out::ajaxReturn(['ok'=>1,'msg'=>'no permission']);
    }
}