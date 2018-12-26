<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;
!function_exists("apache_note") &&   die("apache_note 不支持");
class CacheApacheNote implements BoCacheInterface
{

    private static $_instance = null;

    function __construct()
    {}

    static function getInstance()
    {
        // BoDebug::Info("opencachedir ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheApacheNote();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("apachenotecache setkey " . $_key);
        $cachedatafile = md5($_key);
        $cacheexipirefile = md5('cachetime_' . $_key);
        try {
            apache_note($cachedatafile, serialize($_val));
            apache_note($cacheexipirefile, $_expire + time());
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        BoDebug::Info("apachenotecache getkey " . $_key);
        $ret = null;
        $cachedatafile = md5($_key);
        $cacheexipirefile = md5('cachetime_' . $_key);
        $expire = apache_note($cacheexipirefile);
        if ($expire != "") {
            $now = time();
            if ($expire > $now || $expire == 0) {
                $ret = unserialize(apache_note($cachedatafile));
            }
        }
        
        return $ret;
    }

    function del($_key)
    {
        BoDebug::Info("apachenotecache delkey " . $_key);
        $cachedatafile = md5($_key);
        $cacheexipirefile = md5('cachetime_' . $_key);
        try {
            if(apache_note($cacheexipirefile, "")&& apache_note($cachedatafile, "")){
                return true;
            }
            return false;
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }
}

?>