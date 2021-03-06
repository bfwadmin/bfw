<?php
namespace Lib;

use Lib\Util\FileUtil;
use Lib\Util\HttpUtil;
use Lib\Util\StringUtil;

/**
 * @author wangbo
 * 项目管理
 */
class BoApp
{

    private $_mode = 0;

    private $_usermode = "local";

    private $_pdo = null;
    const UNSERVICENUM = 3;

    public function __construct($_mode = 0)
    {
        $this->_mode = $_mode;
    }


    private function initdb()
    {
        if ($this->_pdo == null) {
            $this->_pdo = new \PDO('sqlite:' . DEV_USERDB_DIR . 'user.db');
        }
        $this->_pdo->exec("CREATE TABLE IF NOT EXISTS user (
            id INTEGER PRIMARY KEY,
            username TEXT,
            userpwd TEXT,
            token TEXT,
           regtime TEXT)");
    }

    public function listapp()
    {
        $_uid = BoCache::Cache("app_server_uid");
        $_dirdata = file_get_contents(DEV_HOST_URL . "?apphandler=5&token=" . $_uid);
        if ($_dirdata) {
            echo $_dirdata;
        }
    }

    public function upload($_appname, $_to_temp = false)
    {
        $_uid = BoCache::Cache("app_server_uid");
        $_out = [];
        if ($_to_temp) {
            // 标签替换
            FileUtil::copy_replace_text(APP_ROOT . DS . "App" . DS . $_appname, RUNTIME_DIR . DS . "App" . DS . $_appname, $_appname, "[[DOM]]");
            FileUtil::zip(RUNTIME_DIR . DS . "App" . DS . $_appname, RUNTIME_DIR . $_appname . "back.zip");
            FileUtil::zip(APP_BASE . DS . STATIC_DIR . DS . $_appname, RUNTIME_DIR . $_appname . "static.zip");
            $_bocodeins = Core::LoadClass("Lib\\BoDb");
            $_bocodeins->Backup($_appname, RUNTIME_DIR . $_appname . ".sql");
            $_data = file_get_contents(RUNTIME_DIR . $_appname . ".sql");
            $_db_config = Bfw::Config("Db", "localconfig", $_appname);
            $_dbname = $_db_config["dbname"];
            $_data = str_replace($_dbname, "[[DB]]", $_data);
            $_data = str_replace($_appname, "[[DOM]]", $_data);
            file_put_contents(RUNTIME_DIR . $_appname . ".sql", $_data);

            if (file_exists(RUNTIME_DIR . $_appname . ".sql")) {
                $_out = HttpUtil::Upload(DEV_HOST_URL, [
                    "apphandler" => 2,
                    "token" => $_uid,
                    "temppost" => 1,
                    "file" => "@" . RUNTIME_DIR . $_appname . ".sql"
                ]);
            }
            if (isset($_out['err']) && $_out['err'] == false) {
                if (file_exists(RUNTIME_DIR . $_appname . "back.zip")) {
                    $_out = HttpUtil::Upload(DEV_HOST_URL, [
                        "apphandler" => 2,
                        "token" => $_uid,
                        "temppost" => 1,
                        "file" => "@" . RUNTIME_DIR . $_appname . "back.zip"
                    ]);
                }
            }
            if (isset($_out['err']) && $_out['err'] == false) {
                if (file_exists(RUNTIME_DIR . $_appname . "static.zip")) {
                    $_out = HttpUtil::Upload(DEV_HOST_URL, [
                        "apphandler" => 2,
                        "token" => $_uid,
                        "temppost" => 1,
                        "file" => "@" . RUNTIME_DIR . $_appname . "static.zip"
                    ]);
                }
            }
        } else {
            FileUtil::zip(APP_ROOT . DS . "App" . DS . $_appname, RUNTIME_DIR . $_appname . "back.zip");
            FileUtil::zip(APP_BASE . DS . STATIC_DIR . DS . $_appname, RUNTIME_DIR . $_appname . "static.zip");
            $_bocodeins = Core::LoadClass("Lib\\BoDb");
            $_bocodeins->Backup($_appname, RUNTIME_DIR . $_appname . ".sql");
            if (file_exists(RUNTIME_DIR . $_appname . ".sql")) {
                $_out = HttpUtil::Upload(DEV_HOST_URL, [
                    "apphandler" => 2,
                    "token" => $_uid,
                    "temppost" => 0,
                    "file" => "@" . RUNTIME_DIR . $_appname . ".sql"
                ]);
            }
            if (isset($_out['err']) && $_out['err'] == false) {
                if (file_exists(RUNTIME_DIR . $_appname . "back.zip")) {
                    $_out = HttpUtil::Upload(DEV_HOST_URL, [
                        "apphandler" => 2,
                        "token" => $_uid,
                        "temppost" => 0,
                        "file" => "@" . RUNTIME_DIR . $_appname . "back.zip"
                    ]);
                }
            }
            if (isset($_out['err']) && $_out['err'] == false) {
                if (file_exists(RUNTIME_DIR . $_appname . "static.zip")) {
                    $_out = HttpUtil::Upload(DEV_HOST_URL, [
                        "apphandler" => 2,
                        "token" => $_uid,
                        "temppost" => 0,
                        "file" => "@" . RUNTIME_DIR . $_appname . "static.zip"
                    ]);
                }
            }
        }
        if (isset($_out['err']) && $_out['err'] == false) {
            return true;
        } else {
            return false;
        }
    }

    public function download($_appname, $_localname, $_dbinfo, $_from_temp = false)
    {

        // 检测数据库是否可以连接
        $_conn = mysql_connect($_dbinfo[0] . ":" . $_dbinfo[1], $_dbinfo[2], $_dbinfo[3]);
        if ($_conn) {
            mysql_close($_conn);
        } else {
            // r die('<p class="dbDebug"><span class="err">Mysql Connect Error : </span>' . mysql_error() . '</p>')
            return "mysqlerr" . mysql_error();
        }
        $_uid = BoCache::Cache("app_server_uid");
        if ($_from_temp) {
            $_appdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&token=" . $_uid . "&t=1&f=" . $_appname . "back.zip");
            if ($_appdata == false) {
                return "获取模板失败";
            }
            file_put_contents(RUNTIME_DIR . $_appname . "back.zip", $_appdata);
            $_appdata = null;
            if (FileUtil::unzip(RUNTIME_DIR . $_appname . "back.zip", APP_ROOT . DS . "App" . DS . $_localname)) {
                FileUtil::replace_text(APP_ROOT . DS . "App" . DS . $_localname . DS, "[[DOM]]", $_localname);
            }
            // FileUtil::unzip(RUNTIME_DIR . $_appname . "back.zip", APP_ROOT . DS . "App" . DS . $_localname);
            // FileUtil::replace_text(APP_ROOT . DS . "App" . DS . $_localname, "App\\" . $_appname, "App\\" . $_localname);
            $_staticdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&token=" . $_uid . "&t=1&f=" . $_appname . "static.zip");
            if ($_staticdata) {
                file_put_contents(RUNTIME_DIR . $_appname . "static.zip", $_staticdata);
            }

            $_staticdata = null;
            FileUtil::unzip(RUNTIME_DIR . $_appname . "static.zip", APP_BASE . DS . STATIC_DIR . DS . $_localname);

            $_sqlcdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&token=" . $_uid . "&t=1&f=" . $_appname . ".sql");
            if ($_sqlcdata == false) {
                return "获取数据库失败";
            }
            file_put_contents(RUNTIME_DIR . $_appname . ".sql", $_sqlcdata);
            $_sqlcdata = null;
            $_db_config = Bfw::Config("Db", "localconfig", $_appname);
            $_dbname = $_db_config["dbname"];
            if (is_array($_db_config)) {
                $_db_config["dbtype"] = "DbMysql";
                $_db_config["dbhost"] = $_dbinfo[0];
                $_db_config["dbport"] = $_dbinfo[1];
                $_db_config["dbuser"] = $_dbinfo[2];
                $_db_config["dbpwd"] = $_dbinfo[3];
                $_db_config["dbname"] = $_localname . "_db";
                BoConfig::ConfigSet("Db", "localconfig", $_db_config, $_localname);
            }

            $_data = file_get_contents(RUNTIME_DIR . $_appname . ".sql");
            $_data = str_replace("[[DB]]", $_localname . "_db", $_data);
            $_data = str_replace("[[DOM]]", $_localname, $_data);
            file_put_contents(RUNTIME_DIR . $_appname . "_1.sql", $_data);
            $_bocodeins = Core::LoadClass("Lib\\BoDb");
            $_bocodeins->Restore($_appname, RUNTIME_DIR . $_appname . "_1.sql", $_dbinfo);
            return "ok";
        } else {
            $_localname = $_appname;
            $_appdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&t=0&token=" . $_uid . "&f=" . $_appname . "back.zip");
            if ($_appdata == false) {
                return "获取模板失败";
            }
            file_put_contents(RUNTIME_DIR . $_appname . "back.zip", $_appdata);
            $_appdata = null;
            if (FileUtil::unzip(RUNTIME_DIR . $_appname . "back.zip", APP_ROOT . DS . "App" . DS . $_localname)) {
                // FileUtil::replace_text(APP_ROOT . DS . "App" . DS . $_localname . DS, $_appname, $_localname);
            }

            $_staticdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&t=0&token=" . $_uid . "&f=" . $_appname . "static.zip");
            if ($_staticdata) {
                file_put_contents(RUNTIME_DIR . $_appname . "static.zip", $_staticdata);
            }

            $_staticdata = null;
            FileUtil::unzip(RUNTIME_DIR . $_appname . "static.zip", APP_BASE . DS . STATIC_DIR . DS . $_localname);
            $_sqlcdata = file_get_contents(DEV_HOST_URL . "?apphandler=1&t=0&token=" . $_uid . "&f=" . $_appname . ".sql");
            if ($_sqlcdata == false) {
                return "获取数据库失败";
            }
            file_put_contents(RUNTIME_DIR . $_appname . ".sql", $_sqlcdata);
            $_sqlcdata = null;

            $_db_config = BoConfig::Config("Db", "localconfig", $_appname);
            $_dbname = $_db_config["dbname"];
            if (is_array($_db_config)) {
                $_db_config["dbtype"] = "DbMysql";
                $_db_config["dbhost"] = $_dbinfo[0];
                $_db_config["dbport"] = $_dbinfo[1];
                $_db_config["dbuser"] = $_dbinfo[2];
                $_db_config["dbpwd"] = $_dbinfo[3];
                $_db_config["dbname"] = $_localname . "_db";
                Bfw::ConfigSet("Db", "localconfig", $_db_config, $_localname);
            }

            $_data = file_get_contents(RUNTIME_DIR . $_appname . ".sql");
            // $_data = str_replace($_dbname, $_localname . "_db", $_data);
            // $_data = str_replace($_appname, $_localname, $_data);
            // file_put_contents(RUNTIME_DIR . $_appname . "_1.sql", $_data);
            $_bocodeins = Core::LoadClass("Lib\\BoDb");
            $_bocodeins->Restore($_appname, RUNTIME_DIR . $_appname . ".sql", $_dbinfo);
            // $_bocodeins->Restore($_appname, RUNTIME_DIR . $_appname . "_1.sql", $_dbinfo);
            return "ok";
        }
    }

    public function getTokenbyname($_uname)
    {
        return $this->getoken($_uname);
    }

    public function setApppower($_app, $tokenstr)
    {
        return $this->setappow($_app, $tokenstr);
    }

    public function login($_uname, $_pwd)
    {
        if ($_uname != "" && $_pwd != "") {
            if (DEV_PLACE == "local") {
                $data = HttpUtil::HttpGet(DEV_HOST_URL . "?apphandler=3&uname=" . $_uname . "&upwd=" . $_pwd);
                if ($data["err"]) {
                    return false;
                }
                if ($data['data'] == "err") {
                    return false;
                }
                return $data['data'];
            } else {
                return $this->auth($_uname, $_pwd);
            }
        } else {
            return false;
        }
        return false;
    }

    public function register($_uname, $_pwd)
    {
        if ($_uname != "" && $_pwd != "") {
            if (DEV_PLACE == "local") {
                $data = HttpUtil::HttpGet(DEV_HOST_URL . "?apphandler=4&uname=" . $_uname . "&upwd=" . $_pwd);
                // var_dump($data);
                if ($data["err"]) {
                    return false;
                }
                if ($data['data'] == "err") {
                    return false;
                }
                return $data['data'];
            } else {
                return $this->reg($_uname, $_pwd);
            }
        } else {
            return false;
        }
        return false;
    }

    private function setappow($_app, $tokenstr)
    {
        $this->initdb();
        $time = date('Y-m-d H:i:s');
        $sql = "SELECT id FROM app where username=:username";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':username', $_uname);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } else {
            return false;
            // var_dump($this->_pdo->errorInfo());
        }
        if (empty($_data)) {
            $sql = "INSERT INTO user (username,userpwd,regtime,token) VALUES (:username,:userpwd,:regtime,:token)";
            $stmt = $this->_pdo->prepare($sql);
            if ($stmt) {
                $token = StringUtil::guid();
                $stmt->bindParam(':username', $_uname);
                $stmt->bindParam(':userpwd', $_pwd);
                $stmt->bindParam(':regtime', $time);
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $stmt->closeCursor();
                FileUtil::CreatDir(APP_BASE . DS . "Cloud" . DS . $token);
                FileUtil::copydir(APP_ROOT . DS . "CodeT" . DS . "cloud", APP_BASE . DS . "Cloud" . DS . $token);

                return $token;
            } else {
                return false;
                // var_dump($this->_pdo->errorInfo());
            }
        } else {
            return false;
        }
    }

    private function getoken($_uname)
    {
        $this->initdb();
        $sql = "SELECT token FROM user where username=:uname";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':uname', $_uname);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } else {
            return false;
        }
        if (empty($_data)) {
            return false;
        } else {
            return $_data[0]['token'];
        }
    }

    private function getuid($token)
    {
        $this->initdb();
        $sql = "SELECT id FROM user where token=:token";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } else {
            return false;
        }
        if (empty($_data)) {
            return false;
        } else {
            return $_data[0]['id'];
        }
    }

    private function auth($_uname, $_pwd)
    {
        if ($this->_usermode == "local") {
            $this->initdb();
            $sql = "SELECT token FROM user where username=:username and userpwd=:userpwd ";
            $stmt = $this->_pdo->prepare($sql);
            $_data = [];
            if ($stmt) {
                $stmt->bindParam(':username', $_uname);
                $stmt->bindParam(':userpwd', $_pwd);
                $stmt->execute();
                $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $stmt->closeCursor();
            } else {
                return false;
                // var_dump($this->_pdo->errorInfo());
            }
            if (empty($_data)) {
                return false;
            } else {
                return $_data[0]['token'];
                // return true;
            }
        } else {
            $data = HttpUtil::HttpGet(BFWUSER_HOST_URL . "?act=auth&uname=" . $_uname . "&upwd=" . $_pwd);
            if ($data["err"]) {
                return false;
            }
            if ($data['data'] == "err") {
                return false;
            }
            return $data['data'];
        }

        return false;
    }

    private function reg($_uname, $_pwd)
    {
        if ($this->_usermode == "local") {
            $this->initdb();
            $time = date('Y-m-d H:i:s');
            $sql = "SELECT id FROM user where username=:username";
            $stmt = $this->_pdo->prepare($sql);
            $_data = [];
            if ($stmt) {
                $stmt->bindParam(':username', $_uname);
                $stmt->execute();
                $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $stmt->closeCursor();
            } else {
                return false;
                // var_dump($this->_pdo->errorInfo());
            }
            if (empty($_data)) {
                $sql = "INSERT INTO user (username,userpwd,regtime,token) VALUES (:username,:userpwd,:regtime,:token)";
                $stmt = $this->_pdo->prepare($sql);
                if ($stmt) {
                    $token = StringUtil::guid();
                    $stmt->bindParam(':username', $_uname);
                    $stmt->bindParam(':userpwd', $_pwd);
                    $stmt->bindParam(':regtime', $time);
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();
                    $stmt->closeCursor();
                    FileUtil::CreatDir(APP_BASE . DS . "Cloud" . DS . $token);
                    FileUtil::copydir(APP_ROOT . DS . "CodeT" . DS . "cloud", APP_BASE . DS . "Cloud" . DS . $token);

                    return $token;
                } else {
                    return false;

                }
            } else {
                return false;
            }
        }else{
            $data = HttpUtil::HttpGet(BFWUSER_HOST_URL . "?act=reg&uname=" . $_uname . "&upwd=" . $_pwd);
            if ($data["err"]) {
                return false;
            }
            if ($data['data'] == "err") {
                return false;
            }
            FileUtil::CreatDir(APP_BASE . DS . "Cloud" . DS . $data['data']);
            FileUtil::copydir(APP_ROOT . DS . "CodeT" . DS . "cloud", APP_BASE . DS . "Cloud" . DS . $data['data']);
            return $data['data'];
        }

        return false;
    }

    private function push($_istemp, $_token)
    {
        $uid = $this->getuid($_token);
        if ($uid) {
            $filename = $_FILES['file']['name'];
            $tmpname = $_FILES['file']['tmp_name'];
            if ($_FILES['file']['error'] > 0) {
                return false;
            }
            // $filesize=$_FILES['file']['size'];
            // $max_uploadsize=ini_get('upload_max_filesize');

            $_desdir = DATA_DIR . DS . "User_" . $uid;
            if ($_istemp == 1) {
                $_desdir = DATA_DIR . DS . "TempUnchecked";
            }
            if (! is_dir($_desdir)) {
                FileUtil::CreatDir($_desdir);
            }
            if (move_uploaded_file($tmpname, $_desdir . DS . $filename)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 获取云端个人项目
     */
    private function getlist($_token)
    {
        $uid = $this->getuid($_token);
        if ($uid) {
            $_desdir = APP_BASE . DS . "Cloud" . DS . $_token . DS . "App" . DS;
            $dirArr = scandir($_desdir);
            return json_encode([
                'err' => false,
                "data" => $dirArr
            ]);
        } else {
            return json_encode([
                'err' => true,
                "data" => "login first"
            ]);
        }
    }

    private function get($filename, $_istemp = 0, $_token)
    {
        $uid = $this->getuid($_token);
        if ($uid) {
            $_desdir = DATA_DIR . DS . "User_" . $uid . DS . $filename;
            if ($_istemp == 1) {
                $_desdir = DATA_DIR . DS . "Temp" . DS . $filename;
            }
            if (file_exists($_desdir)) {
                echo file_get_contents($_desdir, true);
            } else {
                header("HTTP/1.0 404 Not Found");
            }
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }

    public function Run()
    {
        if ($this->_mode == 2) {
            if ($this->push($_POST['temppost'], $_POST['token'])) {
                echo "ok";
            } else {
                echo "fail";
            }
        }
        if ($this->_mode == 1) {
            $this->get($_GET['f'], $_GET['t'], $_GET['token']);
        }
        if ($this->_mode == 3) {
            $_ut = $this->auth($_GET['uname'], $_GET['upwd']);
            if ($_ut) {
                echo $_ut;
            } else {
                echo "err";
            }
        }
        if ($this->_mode == 4) {
            $_ut = $this->reg($_GET['uname'], $_GET['upwd']);
            if ($_ut) {
                echo $_ut;
            } else {
                echo "err";
            }
        }
        if ($this->_mode == 5) {
            echo $this->getlist($_GET['token']);
        }
    }
}
?>