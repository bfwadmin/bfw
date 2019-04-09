<?php
namespace Lib\Session;

use Lib\Exception\DbException;
use Lib\Exception\SessionException;
use Lib\BoDebug;

class SessionMysql implements BoSessionInterface
{

    private static $db = null;


    
    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                BoDebug::Info("mysqlsession connect ".SESSION_MYSQL_IP);
                self::$db = new \Lib\Db\DbMysql(array(
                    "dbuser" => SESSION_MYSQL_USER,
                    "dbpwd" => SESSION_MYSQL_PWD,
                    "dbhost"=>SESSION_MYSQL_IP,
                    "dbname"=>SESSION_MYSQL_DB,
                    "dbport"=>SESSION_MYSQL_PORT
                ));
                self::$db->executeNonquery("CREATE TABLE IF NOT EXISTS `".SESSION_MYSQL_TB."` ( `id` varchar(32) NOT NULL, `access` int(10) unsigned DEFAULT NULL, `data` text, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8", []);
                return true;
            } catch (\PDOException $e) {
                throw new SessionException($e->getMessage());
                // throw
            }
        }
        return false;
    }

    static function sess_close()
    {
        // if (self::$db->close ()) {
        // return true;
        // }
        BoDebug::Info("mysqlsession close ");
        self::$db = null;
        return true;
    }

    static function sess_read($sess_id)
    {
        
        try {
            BoDebug::Info("mysqlsession read " . $sess_id);
            $_s_data = self::$db->single($sess_id, "data", SESSION_MYSQL_TB, "id");
            if ($_s_data['err'] || $_s_data['data'] == ""||$_s_data['data']['access']<time()) {
                return "";
            }
            return $_s_data['data']['data'];
        } catch (DbException $e) {
            BoDebug::Info("mysqlsession readerr " . $e->getMessage());
        }
        return true;
    }

    static function sess_write($sess_id, $data)
    {
        try {
            BoDebug::Info("mysqlsession write " . $sess_id);
            $access = time()+SESSION_COOKIE_EXPIRE;
            $_i_data = self::$db->executeNonquery('REPLACE INTO ' .SESSION_MYSQL_TB. '  VALUES (?, ?, ?)', array(
                $sess_id,
                $access,
                $data
            ));
            if (! $_i_data['err']) {
                return true;
            }
            return false;
        } catch (DbException $e) {
            // throw new SessionException($e->getMessage());
            BoDebug::Info("mysqlsession writeerr " . $e->getMessage());
            // throw
        }
        return false;
    }

    static function sess_destroy($sess_id)
    {
        BoDebug::Info("mysqlsession destory " . $sess_id);
        $_d_data = self::$db->delete($sess_id, SESSION_MYSQL_TB, "id");
        if (! $_d_data['err']) {
            return true;
        }
        return false;
    }

    static function sess_gc($sess_maxlifetime)
    {
        BoDebug::Info("mysqlsession gc ");
        $old = time() - SESSION_COOKIE_EXPIRE;
        
        $_i_data = self::$db->executeNonquery('DELETE * FROM ' . SESSION_MYSQL_TB. ' WHERE access < ?', array(
            $old
        ));
        if (! $_i_data['err']) {
            return true;
        }
        return false;
    }
}

?>