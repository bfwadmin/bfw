<?php
namespace App\DOM\Client;

use Lib\BoClient;


/**
 * @author Herry
 * CONTMEMO调用类
 */
class Client_CONTNAME extends BoClient
{

    /*
     * protected $_serv_url = array(
     * array(
     * "url" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "key" => "123",
     * "dom" => "Cms",
     * "weight" => 20
     * ),
     * array(
     * "url" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "key" => "123",
     * "dom" => "Cms",
     * "weight" => 20
     * )
     * );
     */
    protected $_service_remote = false;
    // http://passport.88art.com/application/index.php
    // http://localhost/boframeworkserver/index.php
    private static $_instance;

    /**
     * 获取单例
     *
     * @return Client_CONTNAME
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
?>