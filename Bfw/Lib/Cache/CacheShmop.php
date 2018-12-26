<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;
! function_exists("shmop_open") && die("shmop_open 不支持");

class CacheShmop implements BoCacheInterface
{

    private static $_instance = null;

    function __construct()
    {}

    static function getInstance()
    {
        // BoDebug::Info("opencachedir ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheShmop();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("apccache setkey " . $_key);
        
        try {
            $key = 0x4337b700;
            $size = 4096;
            $shmid = @shmop_open($key, 'c', 0644, $size);
            if($shmid === FALSE){
                exit('shmop_open error!');
            }
            $data = '世界，你好！我将写入很多的数据，你能罩得住么？';
            
            $length = shmop_write($shmid, pack('a*',$data), 0);
            if($length === FALSE){
                exit('shmop_write error!');
            }
            
            @shmop_close($shmid);
            if ($_expire == 0)
                $_expire = null; // null情况下永久缓存
            return apc_store(md5($_key), serialize($_val), $_expire);
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        $key = 0x4337b700;
        $size = 256;
        $shmid = @shmop_open($key, 'c', 0644, $size);
        if($shmid === FALSE){
            exit('shmop_open error!');
        }
        
        $data = unpack('a*', shmop_read($shmid, 0, 256));
        if($data === FALSE){
            exit('shmop_read error!');
        }
        @shmop_close($shmid);
        
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