<?php
namespace Lib\Session;

use Lib\BoDebug;
use Lib\Exception\SessionException;

class SessionRedis implements BoSessionInterface
{

    private static $db = null;

    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                self::$db = new \Redis();
                self::$db->connect(SESSION_REDIS_IP,SESSION_REDIS_PORT);
                if (SESSION_REDIS_AUTHKEY != "") {
                    self::$db->auth(SESSION_REDIS_AUTHKEY);
                }
                BoDebug::Info("redissession connect " . SESSION_REDIS_IP);
                if (! self::$db) {
                    BoDebug::Info("redissession connect fail ");
                    
                    return false;
                }
                return true;
            } catch (\Exception $e) {
                BoDebug::Info("redissession connect fail " . $e->getMessage());
                throw new SessionException($e->getMessage());
            }
        }
        return false;
    }

    static function sess_close()
    {
        try {
            BoDebug::Info("redissession close ");
            self::$db->close();
            self::$db = null;
        } catch (\Exception $e) {
            BoDebug::Info("redissession connect fail " . $e->getMessage());
        }
        return true;
    }

    static function sess_read($sess_id)
    {
        try {
            BoDebug::Info("redissession read " . $sess_id);
            return self::$db->get($sess_id);
        } catch (\Exception $e) {
            BoDebug::Info("redissession connect fail " . $e->getMessage());
        }
    }

    static function sess_write($sess_id, $data)
    {
        try {
            BoDebug::Info("redissession write " . $sess_id);
            return self::$db->setex($sess_id, SESSION_COOKIE_EXPIRE, $data);
        } catch (\Exception $e) {
            BoDebug::Info("redissession connect fail " . $e->getMessage());
        }
    }

    static function sess_destroy($sess_id)
    {
        try {
            BoDebug::Info("redissession destroy " . $sess_id);
            return self::$db->delete($sess_id) >= 1 ? true : false;
        } catch (\Exception $e) {
            BoDebug::Info("redissession connect fail " . $e->getMessage());
        }
    }

    static function sess_gc($sess_maxlifetime)
    {
        try {
            BoDebug::Info("redissession gc ");
           // self::$db->keys("*");
            return true;
        } catch (\Exception $e) {
            BoDebug::Info("redissession connect fail " . $e->getMessage());
            // throw new SessionException("redis无法连接");
        }
    }
}

?>