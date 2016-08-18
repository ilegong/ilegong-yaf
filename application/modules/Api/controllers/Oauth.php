<?php

/**
 * Created by PhpStorm.
 * User: ellipsis
 * Date: 16/8/18
 * Time: 上午10:17
 */
class OauthController extends \Core\Api\MyCon
{
    protected $_login = false;
    public function serverAction()
    {
        $mysql = Yaf\Application::app()->getConfig()->mysql->toArray();
        //var_dump($mysql);
        $config = [
            'dns' => "mysql:dbname={$mysql['database_name']};host={$mysql['server']}",
            'username' => $mysql['username'],
            'password' => $mysql['password']
        ];
        $storage = new OAuth2\Storage\Pdo($config);
        $server = new OAuth2\Server($storage);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

    }
}