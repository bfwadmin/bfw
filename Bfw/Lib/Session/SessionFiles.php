<?php
namespace Lib\Session;

use Lib\BoDebug;

class SessionFiles implements BoSessionInterface
{

    static function sess_open($sess_path, $sess_name)
    {
        BoDebug::Info("filesession dir " . SESSION_SAVE_PATH);
        return true;
    }

    static function sess_close()
    {
        return true;
    }

    static function sess_read($sess_id)
    {
        BoDebug::Info("filesession read " . $sess_id);
        if (file_exists(SESSION_SAVE_PATH . DS . $sess_id)) {
            clearstatcache();
            if (filemtime(SESSION_SAVE_PATH . DS . $sess_id) + SESSION_COOKIE_EXPIRE > time()) {
                return (string) @file_get_contents(SESSION_SAVE_PATH . DS . $sess_id);
            } else {
                return "";
            }
        } else {
            return "";
        }
        // return (string) @file_get_contents(SESSION_SAVE_PATH . DS . $sess_id);
    }

    static function sess_write($sess_id, $data)
    {
        try {
            // echo "started";
            // if($data!=""){
            // echo $data;
            BoDebug::Info("filesession write " . $sess_id);
            if (($fp = @fopen(SESSION_SAVE_PATH . DS . $sess_id, "w")) != false) {
                $_ret = fwrite($fp, $data);
                fclose($fp);
                return $_ret;
            } else {
                return false;
            }
            // }
        } catch (\Exception $e) {
            BoDebug::LogR($e->getMessage(), "SESSION_ERR");
            // throw new SessionException($e->getMessage());
            // throw
        }
    }

    static function sess_destroy($sess_id)
    {
        try {
            BoDebug::Info("filesession destroy " . $sess_id);
            return @unlink(SESSION_SAVE_PATH . DS . $sess_id);
        } catch (\Exception $e) {
            BoDebug::LogR($e->getMessage(), "SESSION_ERR");
            // throw new SessionException($e->getMessage());
            // throw
        }
    }

    static function sess_gc($sess_maxlifetime)
    {
        try {
            BoDebug::Info("filesession gc " . SESSION_COOKIE_EXPIRE);
            foreach (glob(SESSION_SAVE_PATH . DS . "*") as $filename) {
                if (filemtime($filename) + SESSION_COOKIE_EXPIRE < time()) {
                    @unlink($filename);
                }
            }
            return true;
        } catch (\Exception $e) {
            BoDebug::LogR($e->getMessage(), "SESSION_ERR");
            // throw new SessionException($e->getMessage());
            // throw
        }
    }
}

?>