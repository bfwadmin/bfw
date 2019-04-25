<?php
namespace Lib;

use Lib\Db\DbFactory;
use Lib\Exception\DbException;
use Lib\Util\StringUtil;
use Lib\Util\FileUtil;

/**
 * @author wangbo
 * 代码自动生成
 */
class BoCode extends WangBo
{

    protected $_dbinfo = [];
    // 不需要重新生成的
    protected $_nogentable = array(
        "cms_user"
    );

    protected $_dbhandle = null;

    public function __construct()
    {
        if ($this->_dbhandle == null) {}
    }

    private function createdb($_dbname)
    {
        $this->_dbhandle->executeNonquery('create database ' . $_dbname . ' default character set ' . DB_CHARACTER . ' collate ' . DB_COLLATE . ';use ' . $_dbname . ';', []);
    }

    private function createtb($_tbname)
    {
        $this->_dbhandle->executeNonquery('CREATE TABLE IF NOT EXISTS `' . $_tbname . '` (`id` int(11) NOT NULL AUTO_INCREMENT,  `title` varchar(50) NOT NULL,`content` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;', []);
    }

    private function createuserwithdb($_user, $_pwd, $_dbname)
    {
        $this->_dbhandle->executeNonquery("CREATE USER '" . $_user . "'@'127.0.0.1' IDENTIFIED BY '" . $_pwd . "';GRANT USAGE ON *.* TO '" . $_user . "'@'127.0.0.1' IDENTIFIED BY '" . $_pwd . "' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;CREATE DATABASE IF NOT EXISTS `" . $_dbname . "`;GRANT ALL PRIVILEGES ON `" . $_dbname . "`.* TO '" . $_user . "'@'127.0.0.1';");
    }

    private function getdevmysqlinfo()
    {
        static $_config_arr = [];
        if (file_exists(APP_ROOT . DS . "App" . DS . "Config.php")) {
            include APP_ROOT . DS . "App" . DS . "Config.php";
        }
        if (isset($_config_arr['Globle']['dev_mysql_conf'])) {
            return $_config_arr['Globle']['dev_mysql_conf'];
        } else {
            return null;
        }
    }

    public function InitApp($_appname, $_uid = "")
    {
        $_dbinfo = $this->getdevmysqlinfo();
        if (! is_array($_dbinfo) || count($_dbinfo) != 4) {
            return false;
        }
        $_controlername = "Hello";
        $_dbname = substr(md5($_uid . $_appname), 8, 16);
        $_uname = $_dbname;
        $_upwd = StringUtil::getRandChar(10);
        try {
            $this->_dbinfo = [
                "dbtype" => "DbMysql",
                "dbhost" => "127.0.0.1",
                "dbport" => 3306,
                "dbuser" => $_uname,
                "dbpwd" => $_upwd,
                "dbname" => $_dbname
            ];
            //var_dump($_dbinfo);
            $this->_dbhandle = DbFactory::GetInstance([
                "dbtype" => "DbMysql",
                "dbhost" => $_dbinfo[0],
                "dbport" => $_dbinfo[1],
                "dbuser" => $_dbinfo[2],
                "dbpwd" => $_dbinfo[3],
                "dbname" => "mysql"
            ]);
            $this->createuserwithdb($_uname, $_upwd, $_dbname);
            $this->_dbhandle = DbFactory::GetInstance($this->_dbinfo);
            $this->createtb("bfw_" . $_controlername);
            $this->_generate([
                'name' => $_controlername
            ], [
                [
                    'key' => "",
                    "name" => "title",
                    "memo" => "标题"
                ],
                [
                    'key' => "pre",
                    "name" => "id",
                    "memo" => "编号"
                ],
                [
                    'key' => "",
                    "name" => "content",
                    "memo" => "内容"
                ]
            ], $_appname, false);
            $this->CreatDir(APP_BASE . DS . STATIC_NAME . DS . $_appname);
            FileUtil::copydir(BFW_LIB . '/' . CODE_TEMP_PATH . DS . "Cloud" . DS . "static", APP_BASE . DS . STATIC_NAME . DS . $_appname . DS);
            file_put_contents(APP_ROOT . DS . "App" .DS. $_appname . DS . "readme.bfw", "项目备注信息填写");
            return true;
        } catch (DbException $ex) {
            echo $ex->getException()['errmsg'].$ex->getLine();
            return false;
        } catch (\Exception $ex) {
            echo $ex->getException()['errmsg'].$ex->getLine();
            return false;
        }
    }

    public function AddCont($_appname, $_controlername = "Hello")
    {
        try {
            $this->_dbhandle = DbFactory::GetInstance(Bfw::Config("Db", "localconfig", $_appname));
            $this->createtb("bfw_" . $_controlername);
            $this->_generate([
                'name' => $_controlername
            ], [
                [
                    'key' => "",
                    "name" => "title",
                    "memo" => "标题"
                ],
                [
                    'key' => "pre",
                    "name" => "id",
                    "memo" => "编号"
                ],
                [
                    'key' => "",
                    "name" => "content",
                    "memo" => "内容"
                ]
            ], $_appname, false);
            // echo "" . Bfw::ACLINK($_controlername, "ListData", "", $_appname);
            return true;
        } catch (\Exception $ex) {
            echo $ex->getException()['errmsg'];
            return false;
        } catch (DbException $ex) {
            echo $ex->getException()['errmsg'];
            return false;
        }
    }

    public function Generate($_domian, $_tablename = "", $_isoveride = false)
    {
        if ($_tablename == '') {
            $_tablesdata = $this->_dbhandle->GetDbTable();
            if ($_tablesdata['err']) {
                $this->Error($_tablesdata['data']);
                return;
            }
            foreach ($_tablesdata['data'] as $_table_name) {
                if (! in_array($_table_name['name'], $this->_nogentable)) {
                    $_tableinfodata = $this->_dbhandle->GetTableInfo($_table_name['name']);
                    if ($_tableinfodata['err']) {
                        $this->Error($_tableinfodata['data']);
                        return;
                    }
                    $this->_generate($_table_name, $_tableinfodata['data'], $_domian, $_isoveride);
                }
            }
        } else {
            if (! in_array($_tablename, $this->_nogentable)) {
                $_tableinfodata = $this->_dbhandle->GetTableInfo($_tablename);
                if ($_tableinfodata['err']) {
                    $this->Error($_tableinfodata['data']);
                    return;
                }
                $this->_generate(array(
                    "name" => $_tablename,
                    "memo" => ""
                ), $_tableinfodata['data'], $_domian, $_isoveride);
            }
        }
    }

    private function _replacetag($_temp, $_out, $_fields, $_table, $_m, $_isoveride, $_dom)
    {
        if (file_exists($_temp)) {
            $_temp_cont = file_get_contents($_temp);
            if (preg_match_all("/<temp>([\\s\\S]*?)<\\/temp>/", $_temp_cont, $match)) {
                for ($i = 0; $i < count($match[0]); $i ++) {
                    $_f_h = "";
                    foreach ($_fields as $_f) {
                        if ($_f['key'] == "") {
                            $_f_h .= str_replace("FIELDNAME", $_f['name'], str_replace("MEMO", $_f['memo'] == "" ? $_f['name'] : $_f['memo'], $match[1][$i]));
                        }
                    }
                    $_temp_cont = str_replace($match[0][$i], $_f_h, $_temp_cont);
                }
            }
            $_temp_cont = str_replace("DOM", $_dom, $_temp_cont);
            $_temp_cont = str_replace("DBNAME", $this->_dbinfo['dbname'], $_temp_cont);
            $_temp_cont = str_replace("DBTYPE", $this->_dbinfo['dbtype'], $_temp_cont);
            $_temp_cont = str_replace("DBHOST", $this->_dbinfo['dbhost'], $_temp_cont);
            $_temp_cont = str_replace("DBUSER", $this->_dbinfo['dbuser'], $_temp_cont);
            $_temp_cont = str_replace("DBPWD", $this->_dbinfo['dbpwd'], $_temp_cont);
            $_temp_cont = str_replace("DBPORT", $this->_dbinfo['dbport'], $_temp_cont);
            $_temp_cont = str_replace("KEY", "", $_temp_cont);
            $_temp_cont = str_replace("FIELDNAMEARRAY", $this->_implode(",", $_fields), $_temp_cont);
            $_temp_cont = str_replace("FIELDEDITNAMEARRAY", $this->_implode(",", $_fields, true), $_temp_cont);
            $_temp_cont = str_replace("CONTNAME", $_m, $_temp_cont);
            $_temp_cont = str_replace("CONTMEMO", $_table['memo'], $_temp_cont);
            if (file_exists($_out)) {
                if ($_isoveride) {
                    if (rename($_out, $_out . "." . time() . ".bak")) {
                        if ($this->_file_put_content($_out, $_temp_cont)) {
                            return true;
                        }
                    } else {
                        if ($this->_file_put_content($_out . '.new', $_temp_cont)) {
                            return true;
                        }
                    }
                }
            } else {
                if ($this->_file_put_content($_out, $_temp_cont)) {
                    return true;
                }
            }
            return false;
        }
    }

    private function CreatDir($path)
    {
        if (! is_dir($path)) {
            if ($this->CreatDir(dirname($path))) {
                mkdir($path, 0777);
                return true;
            }
        } else {
            return true;
        }
    }

    private function _file_put_content($_filepath, $_filecont)
    {
        $path = dirname($_filepath);
        $this->CreatDir($path);
        return file_put_contents($_filepath, $_filecont);
    }

    private function _generate($_table, $_fields, $_domian, $_isoveride)
    {
        $_m_name = ucfirst(str_replace(TB_PRE, "", $_table['name']));
        if ($this->_checkValid($_m_name)) {
            $_temp_arr = array(
                array(
                    "Db.php",
                    "/$_domian/Config/Db.php"
                ),
                array(
                    "Config.php",
                    "/$_domian/Config/Config.php"
                ),
                array(
                    "AddData.php",
                    "/$_domian/View/$_m_name/AddData.php"
                ),
                array(
                    "WidgetView.php",
                    "/$_domian/View/Widget/Pager.php"
                ),
                array(
                    "Model.php",
                    "/$_domian/Model/Model_$_m_name.php"
                ),
                array(
                    "EditData.php",
                    "/$_domian/View/$_m_name/EditData.php"
                ),
                array(
                    "ListData.php",
                    "/$_domian/View/$_m_name/ListData.php"
                ),
                array(
                    "Controler.php",
                    "/$_domian/Controler/Controler_$_m_name.php"
                ),
                array(
                    "Points.php",
                    "/$_domian/Points/Points_$_m_name.php"
                ),
                array(
                    "Client.php",
                    "/$_domian/Client/Client_$_m_name.php"
                ),
                array(
                    "Service.php",
                    "/$_domian/Service/Service_$_m_name.php"
                ),
                array(
                    "Validate.php",
                    "/$_domian/Validate/Validate_$_m_name.php"
                ),
                array(
                    "Enum.php",
                    "/$_domian/Enum/Enum_$_m_name.php"
                ),
                array(
                    "Widget.php",
                    "/$_domian/Widget/Widget_Pager.php"
                ),
                array(
                    "Plugin.php",
                    "/$_domian/Plugin/Demo.php"
                )
            );
            foreach ($_temp_arr as $item) {
                if (strpos(strtolower($item[0]), "widget") === false && $item[0] != "Db.php" && $item[0] != "Config.php") {
                    $_ret = $this->_replacetag(BFW_LIB . '/' . CODE_TEMP_PATH . '/' . $item[0], APP_ROOT . DS . "App" . DS . $item[1], $_fields, $_table, $_m_name, $_isoveride, $_domian);
                } else {
                    $_ret = $this->_replacetag(BFW_LIB . '/' . CODE_TEMP_PATH . '/' . $item[0], APP_ROOT . DS . "App" . DS . $item[1], $_fields, $_table, $_m_name, false, $_domian);
                }

                if (WEB_DEBUG) {
                    if ($_ret) {
                        global $_debug_info_array;
                        $_debug_info_array[] = array(
                            microtime(true),
                            APP_ROOT . $item[1] . " 代码生成成功"
                        );
                    }
                }
            }
        }
    }

    private function _implode($de, $ar, $full = false)
    {
        $str = "";
        foreach ($ar as $_a) {
            if (! $full) {
                if ($_a['key'] == "") {
                    $str .= '"' . $_a['name'] . '"' . $de;
                }
            } else {
                $str .= '"' . $_a['name'] . '"' . $de;
            }
        }
        return rtrim($str, $de);
    }

    private function _checkValid($str)
    {
        if (strlen($str) < 4) {
            return false;
        }
        $arr = str_split($str, 1);
        $num = count($arr);
        for ($i = 0; $i < $num; $i ++) {
            if (is_numeric($arr[$i])) {
                return false;
            }
        }
        return true;
    }
}
?>