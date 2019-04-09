<?php
namespace App\[[DOM]]\Client;

use Lib\BoClient;

class Client_Power extends BoClient
{

    protected $_service_remote = false;
    // http://passport.88art.com/application/index.php
    // http://localhost/boframeworkserver/index.php
    private static $_instance;

    /**
     * 获取单例
     *
     * @return Client_Person
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 获取组权限
     * @param number $_gid
     * @return multitype:unknown 
     */
    function GetPowerByGroupId($_gid)
    {
        return $this->___GetPowerByGroupId($_gid);
    }
    /**
     * 获取组权限菜单
     * @param number $_gid
     * @return multitype:unknown
     */
    function GetPowerDetailByGroupId($_gid)
    {
        return $this->___GetPowerDetailByGroupId($_gid);
    }
}