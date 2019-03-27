<?php
namespace Lib;


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
        $_dbconf = BoConfig::Config("Db", "localconfig", $_domian);
        if (isset($_dbconf['dbtype']) && strtolower($_dbconf['dbtype']) != "dbmysql") {
            echo "仅支持mysql数据库";
            return;
        }
        
        $mydb = new BoMysqlBack($_dbconf['dbhost'], $_dbconf['dbuser'], $_dbconf['dbpwd'], $_dbconf['dbname']);
        $mydb->BackDb($_filename);
    }

    public function Restore($_domian, $_filename,$_dbinfo=[])
    {
        $_dbconf = [
            "dbtype" => "DbMysql" ,
            "dbport" => $_dbinfo[1],
            "dbuser" => $_dbinfo[2],
            "dbpwd" => $_dbinfo[3],
            "dbname" => "mysql",
            "dbhost" => $_dbinfo[0]
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