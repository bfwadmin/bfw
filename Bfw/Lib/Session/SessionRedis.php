<?php
namespace Lib\Session;

use Lib\Exception\SessionException;
use Lib\Bfw;

class SessionRedis implements BoSessionInterface
{

    private static $db = null;

    const SESSION_REDIS_HOST = "localhost";

    const SESSION_REDIS_PORT = "6379";

    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                self::$db = new \Redis();
                self::$db->connect(self::SESSION_REDIS_HOST, self::SESSION_REDIS_PORT);
                if (! self::$db) {
                    if (WEB_DEBUG && DEBUG_IP == IP) {
                        return Bfw::Debug("SESSION_OPEN_ERR:" . $e->getMessage());
                    } else {
                        return Bfw::LogR("CANNOT OPEN", "SESSION_ERR");
                    }
                    return false;
                }
                return true;
            } catch (\Exception $e) {
                if (WEB_DEBUG && DEBUG_IP == IP) {
                    return Bfw::Debug("SESSION_OPEN_ERR:" . $e->getMessage());
                } else {
                    return Bfw::LogR($e->getMessage(), "SESSION_ERR");
                }
            }
        }
        return false;
    }

    static function sess_close()
    {
        // if(is_null(self::$db)){
        // return Bfw::LogR("not connected","SESSION_ERR");
        // throw new SessionException("未连接");
        // }
        try {
            self::$db->close();
            self::$db = null;
        } catch (\Exception $e) {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                return Bfw::Debug("SESSION_CLOSE_ERR:" . $e->getMessage());
            } else {
                return Bfw::LogR($e->getMessage(), "SESSION_ERR");
            }
            
            // throw new SessionException("redis无法连接");
        }
        return true;
    }

    static function sess_read($sess_id)
    {
        try {
            return self::$db->get($sess_id);
        } catch (\Exception $e) {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                return Bfw::Debug("SESSION_READ_ERR:" . $e->getMessage());
            } else {
                return Bfw::LogR($e->getMessage(), "SESSION_ERR");
            }
            // throw new SessionException("redis无法连接");
        }
    }

    static function sess_write($sess_id, $data)
    {
        try {
            return self::$db->setex($sess_id, ini_get('session.gc_maxlifetime'), $data);
        } catch (\Exception $e) {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                return Bfw::Debug("SESSION_WRITE_ERR:" . $e->getMessage());
            } else {
                return Bfw::LogR($e->getMessage(), "SESSION_ERR");
            }
            // throw new SessionException("redis无法连接");
        }
    }

    static function sess_destroy($sess_id)
    {
        try {
            return self::$db->delete($sess_id) >= 1 ? true : false;
        } catch (\Exception $e) {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                return Bfw::Debug("SESSION_DESTROY_ERR:" . $e->getMessage());
            } else {
                return Bfw::LogR($e->getMessage(), "SESSION_ERR");
            }
            // throw new SessionException("redis无法连接");
        }
    }

    static function sess_gc($sess_maxlifetime)
    {
        try {
            self::$db->keys("*");
            return true;
        } catch (\Exception $e) {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                return Bfw::Debug("SESSION_GC_ERR:" . $e->getMessage());
            } else {
                return Bfw::LogR($e->getMessage(), "SESSION_ERR");
            }
            // throw new SessionException("redis无法连接");
        }
    }
}

?>