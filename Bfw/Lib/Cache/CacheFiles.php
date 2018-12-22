<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;

class CacheFiles implements BoCacheInterface
{

    private static $_instance = null;

    function __construct()
    {}

    static function getInstance()
    {
        BoDebug::Info("opencachedir  ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheFiles();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("filecache setkey ".$_key);
        $cachedatafile = CACHE_DIR . DS . md5($_key);
        $cacheexipirefile = CACHE_DIR . DS . md5('cachetime_' . $_key);
        try {
            file_put_contents($cachedatafile, serialize($_val), LOCK_EX);
            file_put_contents($cacheexipirefile, $_expire, LOCK_EX);
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        BoDebug::Info("filecache getkey ".$_key);
        $ret = null;
        $cachedatafile = CACHE_DIR . DS . md5($_key);
        $cacheexipirefile = CACHE_DIR . DS . md5('cachetime_' . $_key);
        if (file_exists($cachedatafile) && file_exists($cacheexipirefile)) {
            $expire = file_get_contents($cacheexipirefile);
            $now = time();
            if (filemtime($cachedatafile) + $expire > $now || $expire == 0) {
                $ret = unserialize(file_get_contents($cachedatafile));
            }
        }
        return $ret;
    }

    function del($_key)
    {
        BoDebug::Info("filecache delkey ".$_key);
        $cachedatafile = CACHE_DIR . DS . md5($_key);
        $cacheexipirefile = CACHE_DIR . DS . md5('cachetime_' . $_key);
        $cacheexipirefile = CACHE_DIR . DS . md5('cachetime_' . $_key);
        try {
            return @unlink($cachedatafile) || @unlink($cacheexipirefile);
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }
}

?>