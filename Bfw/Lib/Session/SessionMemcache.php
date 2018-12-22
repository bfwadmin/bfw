<?php
namespace Lib\Session;

use Lib\Exception\SessionException;
use Lib\BoDebug;

class SessionMemcache implements BoSessionInterface
{

    private static $db = null;

    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                BoDebug::Info("memsession open " . $sess_path);
                self::$db = new \Memcache();
                self::$db->connect(SESSION_MEMCACHE_IP, SESSION_MEMCACHE_PORT);
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
        if (is_null(self::$db)) {
            BoDebug::Info("memsession close wrong no conected");
            return true;
        }
        BoDebug::Info("memsession close ");
        self::$db->close();
        self::$db = null;
        return true;
    }

    static function sess_read($sess_id)
    {
        if (is_null(self::$db)) {
            BoDebug::Info("memsession close wrong no conected");
            return true;
        }
        BoDebug::Info("memsession read " . $sess_id);
        return self::$db->get($sess_id);
    }

    static function sess_write($sess_id, $data)
    {
        if (is_null(self::$db)) {
            BoDebug::Info("memsession close wrong no conected");
            return true;
        }
        BoDebug::Info("memsession set " . $sess_id);
        return self::$db->set($sess_id, $data, SESSION_MEMCACHE_COMPRESS, ini_get('session.gc_maxlifetime'));
    }

    static function sess_destroy($sess_id)
    {
        if (is_null(self::$db)) {
            BoDebug::Info("memsession close wrong no conected");
            return true;
        }
        BoDebug::Info("memsession destroy " . $sess_id);
        return self::$db->delete($sess_id);
    }

    static function sess_gc($sess_maxlifetime)
    {
        return true;
    }
}

?>