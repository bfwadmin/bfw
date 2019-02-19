<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;

class CacheRedis implements BoCacheInterface
{

    private static $_instance = null;

    private $mem;


    function __construct()
    {      
        $this->mem = new \Redis();
        BoDebug::Info("rediscache connect " . CACHE_REDIS_HOST);
        if (CACHE_REDIS_PCONNECT) {
            if (! $this->mem->pconnect(CACHE_REDIS_HOST,CACHE_REDIS_PORT,CACHE_REDIS_TIMEOUT)) {
                throw new CacheException("rediscache connect fail");
            }
        } else {
            if (! $this->mem->connect(CACHE_REDIS_HOST,CACHE_REDIS_PORT,CACHE_REDIS_TIMEOUT)) {
                throw new CacheException("rediscache connect fail");
            }
        }
        if (CACHE_REDIS_AUTHKEY != "") {
            $this->mem->auth(CACHE_REDIS_AUTHKEY);
        }
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new CacheRedis();
        }
        return self::$_instance;
    }

    function setkey($key, $val, $exprie)
    {
        if ($this->mem->set($key, serialize($val))) {
            BoDebug::Info("rediscache setkey " . $key);
            if($exprie>0){
                return $this->mem->expire($key, $exprie);
            }else{
                return true;
            }
        }
        return false;
    }

    function getkey($key)
    {
        $_val = $this->mem->get($key);
        BoDebug::Info("rediscache getkey " . $key);
        return $_val ? unserialize($_val) : null;
    }

    function del($key)
    {
        BoDebug::Info("rediscache delkey " . $key);
        return $this->mem->delete($key);
    }

    function __destruct()
    {
        BoDebug::Info("rediscache close ");
        $this->mem->close();
    }
    
    // put your code here
}

?>
