<?php
namespace Lib\Session;

use Lib\Exception\DbException;
use Lib\Exception\SessionException;
use Lib\Bfw;
class SessionMysql implements BoSessionInterface
{

    /*
     * sql CREATE TABLE IF NOT EXISTS `cms_Sessions` ( `id` varchar(32) NOT NULL, `access` int(10) unsigned DEFAULT NULL, `data` text, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
     */
    private static $db = null;

    const SESSION_MYSQL_HOST = "localhost";

    const SESSION_MYSQL_USER = "root";

    const SESSION_MYSQL_PWD = "";

    const SESSION_MYSQL_DB = "test";

    const SESSION_MYSQL_TB = "cms_Sessions";

    static function sess_open($sess_path, $sess_name)
    {
        if (self::$db) {
            return true;
        } else {
            try {
                self::$db = new \Lib\Db\DbMysql(array(
                    "dbconnstr" => "mysql:host=" . self::SESSION_MYSQL_HOST . ";dbname=" . self::SESSION_MYSQL_DB,
                    "dbuser" => self::SESSION_MYSQL_USER,
                    "dbpwd" => self::SESSION_MYSQL_PWD
                ));
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
        self::$db = null;
        return true;
    }

    static function sess_read($sess_id)
    {
        try {
            $_s_data = self::$db->single($sess_id, "data", self::SESSION_MYSQL_TB, "id");
            if ($_s_data['err'] || $_s_data['data'] == "") {
                return "";
            }
            return $_s_data['data']['data'];
        } catch (DbException $e) {
            return Bfw::LogR($e->getMessage(),"SESSION_ERR");
           // throw new SessionException($e->getMessage());
            // throw
        }
    }

    static function sess_write($sess_id, $data)
    {
        try {
            $access = time();
            $_i_data = self::$db->executeNonquery('REPLACE INTO ' . self::SESSION_MYSQL_TB . '  VALUES (?, ?, ?)', array(
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
            return Bfw::LogR($e->getMessage(),"SESSION_ERR");
            // throw
        }
    }

    static function sess_destroy($sess_id)
    {
        $_d_data = self::$db->delete($sess_id, self::SESSION_MYSQL_TB, "id");
        if (! $_d_data['err']) {
            return true;
        }
        return false;
    }

    static function sess_gc($sess_maxlifetime)
    {
        $old = time() - $sess_maxlifetime;
        
        $_i_data = self::$db->executeNonquery('DELETE * FROM ' . self::SESSION_MYSQL_TB . ' WHERE access < ?', array(
            $old
        ));
        if (! $_i_data['err']) {
            return true;
        }
        return false;
    }
}

?>