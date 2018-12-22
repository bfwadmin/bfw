<?php
namespace App\DOM\Client;

use Lib\BoClient;


/**
 * @author bfw
 * CONTMEMO调用类
 */
class Client_CONTNAME extends BoClient
{

    /*
     * protected $_serv_url = array(
     * array(
     * "serviceurl" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "seckey" => "123",
     * "weight" => 20
     * ),
     * array(
     * "serviceurl" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "seckey" => "123",
     * "weight" => 20
     * )
     * );
     */
    protected $_service_remote = false;
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