<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;
! function_exists("apc_store") && die("apc_store 不支持");

class CacheApc implements BoCacheInterface
{

    private static $_instance = null;

    function __construct()
    {}

    static function getInstance()
    {
        // BoDebug::Info("opencachedir ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheApc();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("apccache setkey " . $_key);
        
        try {
            if ($_expire == 0)
                $_expire = null; // null情况下永久缓存
            return apc_store(md5($_key), serialize($_val), $_expire);
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        BoDebug::Info("apccache getkey " . $_key);
        $ret = null;
        $ret = unserialize(apc_fetch(md5($_key)));
        
        return $ret;
    }

    function del($_key)
    {
        BoDebug::Info("apccache delkey " . $_key);
        try {
            return apc_delete(md5($_key));
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }
}

?>