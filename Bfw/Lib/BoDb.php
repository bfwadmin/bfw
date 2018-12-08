<?php
namespace Lib;

use Lib\Bfw;
use Lib\Db\DbFactory;

/**
 *
 * @author bfw
 *         数据库相关操作
 */
class BoDb extends WangBo
{

    protected $_dbhandle = null;

    public function Backup($_domian, $_filename)
    {
        set_time_limit(0); // 无时间限制
        $_dbconf = Bfw::Config("Db", "localconfig", $_domian);
        if (isset($_dbconf['dbtype']) && strtolower($_dbconf['dbtype']) != "dbmysql") {
            echo "仅支持mysql数据库";
            return;
        }
        
        $mydb = new BoMysqlBack($_dbconf['dbhost'], $_dbconf['dbuser'], $_dbconf['dbpwd'], $_dbconf['dbname']);
        $mydb->BackDb($_filename);
    }

    public function Restore($_domian, $_filename)
    {
        $_dbconf = [
            "dbtype" => "Db" . DB_TYPE,
            "dbport" => DB_PORT,
            "dbuser" => DB_UNAME,
            "dbpwd" => DB_PASSWD,
            "dbname" => DB_NAME,
            "dbhost" => DB_HOST
        ];
        if (isset($_dbconf['dbtype']) && strtolower($_dbconf['dbtype']) != "dbmysql") {
            echo "仅支持mysql数据库";
            return;
        }
        $mydb = new BoMysqlBack($_dbconf['dbhost'], $_dbconf['dbuser'], $_dbconf['dbpwd'], $_dbconf['dbname']);
        $mydb->RestoreDb($_filename);
    }

    public function __construct()
    {}
}
?>