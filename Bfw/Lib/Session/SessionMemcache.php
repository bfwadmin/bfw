<?php
namespace Lib\Session;
use Lib\Exception\SessionException;
use Lib\Bfw;
class SessionMemcache implements BoSessionInterface
{

    private static $db = null;

    const SESSION_MEM_HOST = "127.0.0.1";

    const SESSION_MEM_PORT = 11211;
    
    const SESSION_COMPRESS = false;

    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                self::$db = new \Memcache();
                self::$db->connect(self::SESSION_MEM_HOST, self::SESSION_MEM_PORT);
                if (! self::$db) {
                    return false;
                }
                return true;
            } catch (\Exception $e) {
               throw new SessionException($e->getMessage());
                // throw
            }
        }
        return false;
    }

    static function sess_close()
    {
    	if(is_null(self::$db)){
    	   return Bfw::LogR("not connected","SESSION_ERR");
    		//throw new SessionException("未连接");
    	}
        // self::$db = new \Memcache();
        // self::$db->connect(self::SESSION_REDIS_HOST, self::SESSION_REDIS_PORT);
        self::$db->close();
        self::$db = null;
        return true;
    }

    static function sess_read($sess_id)
    {
    	if(is_null(self::$db)){
    	    return Bfw::LogR("not connected","SESSION_ERR");
    		//throw new SessionException("未连接");
    	}
        // self::$db = new \Memcache();
        // self::$db->connect(self::SESSION_REDIS_HOST, self::SESSION_REDIS_PORT);
        return self::$db->get($sess_id);
    }

    static function sess_write($sess_id, $data)
    {
    	if(is_null(self::$db)){
    	    return Bfw::LogR("not connected","SESSION_ERR");
    		//throw new SessionException("未连接");
    	}
        // self::$db= new \Memcache();
        // self::$db->connect(self::SESSION_REDIS_HOST, self::SESSION_REDIS_PORT);
        return self::$db->set($sess_id, $data,self::SESSION_COMPRESS, ini_get('session.gc_maxlifetime'));
    }

    static function sess_destroy($sess_id)
    {
    	if(is_null(self::$db)){
    	    return Bfw::LogR("not connected","SESSION_ERR");
    		//throw new SessionException("未连接");
    	}
        // self::$db = new \Memcache();
        // self::$db->connect(self::SESSION_REDIS_HOST, self::SESSION_REDIS_PORT);
        return self::$db->delete($sess_id);
    }

    static function sess_gc($sess_maxlifetime)
    {
        return true;
    }
}

?>