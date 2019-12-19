<?php
namespace Lib;

use Lib\Util\FileUtil;
use Lib\Util\HttpUtil;
use Lib\Util\StringUtil;

/**
 * @author wangbo
 * 项目容器管理
 */
class BoMan
{

    private $_mode = 0;

    private $_usermode = "local";

    //发布部署到bfw容器
    public function publish($_appname, $_to_temp = false)
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


    function dd($_istemp, $_token)
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


}
?>