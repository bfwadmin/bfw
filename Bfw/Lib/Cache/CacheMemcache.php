<?php
namespace Lib\Cache;
use Lib\Exception\CacheException;
use Lib\BoDebug;

class CacheMemcache implements BoCacheInterface
{

    private static $_instance = null;
    // 后期可以使用magent进行热备份负责均衡

    private $mem;

    function __construct()
    {
        $this->mem = new \Memcache();
        BoDebug::Info("memcache connect ".CACHE_MEM_HOST);
        if (CACHE_MEM_PCONNECT) {
            if (! $this->mem->pconnect(CACHE_MEM_HOST, CACHE_MEM_PORT, CACHE_MEM_TIMEOUT)) {
                throw new CacheException("memcache connect fail");
            }
        } else {
            if (! $this->mem->connect(CACHE_MEM_HOST, CACHE_MEM_PORT, CACHE_MEM_TIMEOUT)) {
                throw new CacheException("memcache connect fail");
            }
        }
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new CacheMemcache();
        }
        return self::$_instance;
    }

    function setkey($key, $val, $exprie)
    {
        BoDebug::Info("memcache setkey ".$key);
        //var_dump($this->mem->set($key, $val, 0, $exprie));
        return $this->mem->set($key, $val,CACHE_MEM_COMPRESS, $exprie);
    }

    function getkey($key)
    {
        BoDebug::Info("memcache getkey ".$key);
        $_val=$this->mem->get($key);
        return $_val ? $_val : null;
    }

    function del($key)
    {
        BoDebug::Info("memcache delkey ".$key);
        return $this->mem->delete($key);
    }

    function __destruct()
    {
        BoDebug::Info("memcache close ");
        $this->mem->close();
    }
    
    // put your code here
}

?>
