<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;
! function_exists("xcache_get") && die("xcache_get 不支持");

class CacheXcache implements BoCacheInterface
{

    private static $_instance = null;

    function __construct()
    {}

    static function getInstance()
    {
        // BoDebug::Info("opencachedir ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheXcache();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("xcache setkey " . $_key);
        
        try {
            return xcache_set(md5($_key), serialize($_val), $_expire);
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        BoDebug::Info("xcache getkey " . $_key);
        $ret = null;
        $ret = unserialize(xcache_get(md5($_key)));
        
        return $ret;
    }

    function del($_key)
    {
        BoDebug::Info("xcache delkey " . $_key);
        try {
            return xcache_unset(md5($_key));
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }
}

?>