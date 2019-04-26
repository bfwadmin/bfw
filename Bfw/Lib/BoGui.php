<?php
namespace Lib;

use Lib\Util\FileUtil;

/**
 *
 * @author wangbo
 *         开发模式界面
 */
class BoGui
{

    private $_debug_str = "\$_debug_g_file=APP_ROOT.DS.'App'.DS.'file_info.debug';\$_debug_cont_file=APP_ROOT.DS.'App'.DS.'file_cont.debug';file_put_contents(\$_debug_g_file,serialize(['file'=>\\Lib\\Util\\StringUtil::GetStringByRegx(__FILE__,'/App(.*)\\.debug/'),'line'=>__LINE__,'var'=>\\Lib\\BoDebug::ExportVar(get_defined_vars(),\$this)]));while(true){\$_control_file=file_get_contents(\$_debug_cont_file);if(\$_control_file=='go'){file_put_contents(\$_debug_cont_file,'wait');break;} if(\$_control_file=='exit'){break;} sleep(1);}";

    private $_mode = "";

    public function __construct($_mode = "console")
    {
        $this->_mode = $_mode;
    }

    private function uploadapp($_name, $_to_temp = false)
    {
        set_time_limit(0); // 无时间限制
        $_ba = new BoApp();
        if ($_ba->upload($_name, $_to_temp)) {
            echo "ok";
        } else {
            echo "fali";
        }
    }

    private function getapp($_name, $localname = "", $_dbinfo, $_from_temp = false)
    {
        set_time_limit(0); // 无时间限制
        $_ba = new BoApp();
        echo $_ba->download($_name, $localname, $_dbinfo, $_from_temp);
    }

    private function setuid($_uid)
    {
        if (DEV_PLACE == "cloud") {
            // file_put_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey(), $_uid);
            BoCache::Cache($this->genkey(), $_uid, 0);
        }
        if (DEV_PLACE == "local") {
            BoCache::Cache("app_server_uid", $_uid, 0);
        }
    }

    private function genkey()
    {
        session_start();
        $_sessid = session_id(); // 用户Sessionid
        session_write_close();
        return $_sessid . "app_server_uid";
        // file_put_contents($filename, $data)
    }

    private function getuid()
    {
        if (DEV_PLACE == "cloud") {
            // return file_get_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey());
            // $_key=.
            return BoCache::Cache($this->genkey());
        }
        if (DEV_PLACE == "local") {
            return BoCache::Cache("app_server_uid");
        }
        return "";
    }

    private function logout()
    {
        if (DEV_PLACE == "cloud") {
            // return file_put_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey(), "");

            return BoCache::Cache($this->genkey(), "", 0);
        }
        if (DEV_PLACE == "local") {
            return BoCache::Cache("app_server_uid", "");
        }
        return true;
    }

    private function login($_uname, $_pwd)
    {
        $_ba = new BoApp();
        $_ret = $_ba->login($_uname, $_pwd);
        if ($_ret) {
            $this->setuid($_ret);
            return [
                'err' => false,
                "data" => $_ret
            ];
        } else {
            return [
                'err' => true,
                "data" => "登录失败"
            ];
        }
    }

    private function getappversion($_appname)
    {
        $_ba = new BoVersion();
        return $_ba->getappversion($_appname);
    }

    private function addappversion($_appname, $_memo, $_usertoken)
    {
        $_ba = new BoVersion();
        return $_ba->addappversion($_appname, $_memo, $_usertoken);
    }

    private function setappversion($_appname, $_v, $_usertoken)
    {
        $_ba = new BoVersion();
        return $_ba->setappversion($_appname, $_v, $_usertoken);
    }

    private function commitlog($_appname, $_file, $_actiontype, $_memo, $_usertoken)
    {
        $_ba = new BoVersion();
        return $_ba->commitlog($_appname, $_file, $_actiontype, $_memo, $_usertoken);
    }

    private function getcommitlog($_appname)
    {
        $_ba = new BoVersion();
        return $_ba->getcommitlog($_appname, 10, 0);
    }

    private function gettoken($_uname)
    {
        $_ba = new BoApp();
        $_ret = $_ba->getTokenbyname($_uname);
        if ($_ret) {

            return [
                'err' => false,
                "data" => $_ret
            ];
        } else {
            return [
                'err' => true,
                "data" => ""
            ];
        }
    }

    private function register($_uname, $_pwd)
    {
        $_ba = new BoApp();
        $_ret = $_ba->register($_uname, $_pwd);
        if ($_ret) {
            $this->setuid($_ret);
            return [
                'err' => false,
                "data" => $_ret
            ];
        } else {
            return [
                'err' => true,
                "data" => "注册失败"
            ];
        }
    }

    private function listapp()
    {
        $_ba = new BoApp();
        return $_ba->listapp();
    }

    private function initapp($_appname)
    {
        $_bocodeins = Core::LoadClass("Lib\\BoCode");
        return $_bocodeins->InitApp($_appname, $this->getuid());
    }

    private function addcont($_appname, $_contname)
    {
        $_bocodeins = Core::LoadClass("Lib\\BoCode");
        return $_bocodeins->AddCont($_appname, $_contname);
    }

    private function getmethod($_class)
    {
        $_cont_act_a = [];
        try {
            $_cont_act_a = [];
            Core::ImportClass($_class);
            $_classname = str_replace(".", "\\", $_class);
            $_control_name_dll = $_classname;
            $r = new \reflectionclass($_control_name_dll);
            $_cont_act_a['doc'] = $r->getDocComment();
            $_cont_act_a['method']['namespace'] = $_classname;
            foreach ($r->getmethods() as $key => $methodobj) {
                if (! $methodobj->isprivate()) {
                    if ($methodobj->name != "__get") {
                        $_params = $methodobj->getParameters();
                        $_paraarr = [];
                        foreach ($_params as $param) {
                            $_paraarr[] = "'" . $param->getName() . "'";
                        }
                        $_parastr = "(";
                        $_parastr .= implode(",", $_paraarr);
                        $_parastr .= ")";

                        if ($methodobj->isStatic()) {
                            $_cont_act_a['method']['staticname'][] = $methodobj->name . $_parastr;
                            $_cont_act_a['method']['staticdoc'][] = $methodobj->getDocComment();
                        } else {
                            $_cont_act_a['method']['name'][] = $methodobj->name . $_parastr;
                            $_cont_act_a['method']['doc'][] = $methodobj->getDocComment();
                        }
                        // $_cont_act_a['method'][] = $methodobj->name;
                    }
                }
            }
        } catch (\Exception $e) {}

        return $_cont_act_a;
    }

    private function checkpower($_uid)
    {
        return APPSELF == "/Cloud/{$_uid}/index.php";
    }

    private function checkisempty($_p)
    {
        if (isset($_p) && trim($_p) != "") {
            return false;
        }
        return true;
    }

    private function delfile($_filepath)
    {
        // 加入版本控制
        return unlink($_filepath);
    }

    private function writefile($_filepath, $_data, $_uid)
    {
        // 加入版本控制
        if (file_put_contents($_filepath, $_data)) {
            $this->commitlog("Demo", $_filepath, "save", "dddd", $_uid);
            return true;
        }
        return false;
    }

    public function Run()
    {
        // StringUtil::GetStringByRegx("ddd\App\sdf\dfd\ll.php.debug","/App(.*)\.debug/");
        // die();
        // 验证
        if (isset($_GET['callback'])) {

            $_sign = $_GET['sign'];
            $_token = $_GET['token'];
            $_username = $_GET['username'];
            // die(APPSELF);
            if (APPSELF != "/index.php") {
                header("location:/?webide=1&callback=1&sign={$_sign}&token={$_token}&username={$_username}");

                exit();
            }
            if ($_token != "" && $_username != "" && $_sign != "") {
                if (md5($_token . $_username . "SJDU!234324(*(DDFDDGF") == $_sign) {
                    // 创建工作目录
                    if (! file_exists(APP_BASE . DS . "Cloud" . DS . $_token . DS . "index.php")) {
                        FileUtil::CreatDir(APP_BASE . DS . "Cloud" . DS . $_token);
                        FileUtil::copydir(APP_ROOT . DS . "CodeT" . DS . "cloud", APP_BASE . DS . "Cloud" . DS . $_token);
                    }
                    $this->setuid($_token);
                }
            }
        }
        // die("d");
        if (isset($_GET['getstatic'])) {
            $_file = str_replace("\\", '', str_replace("/", '', $_GET['getstatic']));
            if (substr($_file, strlen($_file) - 4) == ".css") {
                header('Content-type: text/css');
                echo file_get_contents(BFW_LIB . DS . "Lib" . DS . "View/v1/static/css/" . $_file);
            }
            if (substr($_file, strlen($_file) - 3) == ".js") {
                header('Content-type: text/javascript');
                echo file_get_contents(BFW_LIB . DS . "Lib" . DS . "View/v1/static/js/" . $_file);
            }
            if (substr($_file, strlen($_file) - 4) == ".png" || substr($_file, strlen($_file) - 4) == ".jpg" || substr($_file, strlen($_file) - 4) == ".gif") {
                header('Content-Type: image/jpeg');
                echo file_get_contents(BFW_LIB . DS . "Lib" . DS . "View/v1/static/images/" . $_file);
            }
            exit();
        }
        if (isset($_GET['login'])) {
            if (isset($_GET['login'])) {
                $loginpara = explode("|", $_GET['login']);
                if (count($loginpara) >= 2) {
                    $ret = $this->login($loginpara[0], $loginpara[1]);
                    if ($ret['err'] == false) {
                        setcookie(DEV_USERCOOKIE_NAME, md5($ret . date("Ymd", time())), "/", "", false, true);
                    }
                    echo json_encode($ret);
                } else {
                    echo json_encode([
                        "err" => true,
                        "data" => "参数错误"
                    ]);
                }
                exit();
            }
        }
        if (isset($_GET['logout'])) {
            if ($this->logout()) {
                echo "ok";
            }
            exit();
        }
        if (isset($_GET['register'])) {
            if (isset($_GET['register'])) {
                $loginpara = explode("|", $_GET['register']);
                if (count($loginpara) >= 2) {
                    $ret = $this->register($loginpara[0], $loginpara[1]);
                    // if ($ret['err'] == false) {
                    // setcookie(DEV_USERCOOKIE_NAME, md5($ret . date("Ymd", time())), "/", "", false, true);
                    // }
                    echo json_encode($ret);
                } else {
                    echo json_encode([
                        "err" => true,
                        "data" => "参数错误"
                    ]);
                }
                exit();
            }
        }
        $_uid = $this->getuid();
        if (isset($_GET['getsysclass'])) {
            $_method = [];
            $_utillist = FileUtil::getFileListByDir(BFW_LIB . DS . "Lib" . DS . "Util" . DS);
            $_bofunc = [
                'BoSess.php',
                "BoConfig.php",
                "BoRes.php",
                "BoReq.php",
                "BoDebug.php",
                "BoCache.php",
                "Bfw.php",
                "Core.php"
            ];
            foreach ($_bofunc as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("Lib." . $_file);
                // $_file .= "::getInstance()";
            }
            // echo BFW_LIB . DS . "Util" . DS;
            foreach ($_utillist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("Lib.Util." . $_file);
                // $_file .= "::getInstance()";
            }
            echo json_encode([
                'class' => array_merge($_utillist, $_bofunc),
                "method" => $_method
            ]);
            exit();
        }
        if (isset($_GET['getpro'])) {

            // 获取本地项目
            $_dirarr = [];
            $_remarr = [];
            $dirArr = scandir(APP_ROOT . DS . "App" . DS);

            foreach ($dirArr as $_item) {
                $_ext = strrchr($_item, '.');
                if (strtolower($_item) != "config.php" && strtolower($_item) != ".." && strtolower($_item) != ".") {
                    // if (strpos(URL, $_uid) === true) {
                    if ($_ext) {
                        if ($_ext != ".debug") {
                            $_dirarr[] = [
                                'type' => 'self',
                                "name" => $_item,
                                "url" => ""
                            ];
                        }
                    } else {
                        $_dirarr[] = [
                            'type' => 'self',
                            "name" => $_item,
                            "url" => ""
                        ];
                    }

                    // } else {

                    // }
                }
            }
            if (DEV_PLACE != "cloud") {
                echo json_encode($_dirarr);
                exit();
            }
            $_datapath = BFW_LIB . DS . "Data" . DS . "Teamapp_" . $_uid;
            if (file_exists($_datapath)) {
                $_team_arr = unserialize(file_get_contents($_datapath));
                foreach ($_team_arr as $_item) {

                    $_remarr[] = [
                        'type' => 'team',
                        "name" => $_item[0],
                        "url" => $_item[1]
                    ];
                }
            }
            // 获取团队项
            if ($this->checkpower($_uid)) {
                foreach ($_remarr as $_item) {
                    $_dirarr[] = $_item;
                }
                echo json_encode($_dirarr);
                // die("ss");
            } else {
                foreach ($_remarr as &$_item) {
                    $_item['type'] = "self";
                }
                echo json_encode($_remarr);
            }

            // echo json_encode($_dirarr);
            exit();
        }
        if (DEV_PLACE == "cloud") {
            if ($_uid != "") {
                // 根据权限检测是否可以团队开发
                if (file_exists(DATA_DIR . DS . "apppower_token_" . $_GET['targetappname'])) {
                    $_tokendata = file_get_contents(DATA_DIR . DS . "apppower_token_" . $_GET['targetappname']);
                    if ($_tokendata != "") {
                        // die($_tokendata);
                        $_token_arr = explode("|", $_tokendata);
                        if (! in_array($_uid, $_token_arr)) {
                            if (! $this->checkpower($_uid)) {
                                if (IS_AJAX_REQUEST) {
                                    echo json_encode([
                                        'err' => true,
                                        "data" => "没权限操作"
                                    ]);
                                } else {
                                    // die("s");
                                    header("Location:/Cloud/" . $_uid . "/?webide=1");
                                }

                                exit();
                            }
                        }
                    }
                } else {
                    if (! $this->checkpower($_uid)) {
                        if (IS_AJAX_REQUEST) {
                            echo json_encode([
                                'err' => true,
                                "data" => "没权限操作"
                            ]);
                        } else {
                            // die("ds");
                            header("Location:/Cloud/" . $_uid . "/?webide=1");
                        }
                        exit();
                    }
                }
            } else {
                if (IS_AJAX_REQUEST) {
                    echo json_encode([
                        'err' => true,
                        "data" => "请登录后再操作"
                    ]);
                } else {
                    header("Location:" . BFWUSER_HOST_URL . "?authbackurl=" . urlencode(URL));
                    // BoRes::View("cloudreg", "System", "v1");
                }

                exit();
            }
        }
        if (isset($_GET['addwikipage'])) {
            if ($this->checkisempty($_POST['classname']) || $this->checkisempty($_POST['title']) || $this->checkisempty($_POST['cont'])) {
                echo json_encode([
                    'err' => true,
                    "data" => "请填写完整"
                ]);
                exit();
            }
            $_bwiki = new BoWiki();
            if ($_bwiki->addwikipage($_POST['classname'], $_POST['title'], $_POST['cont'], $_uid)) {
                echo json_encode([
                    'err' => false,
                    "data" => ""
                ]);
            }
            exit();
        }
        if (isset($_GET['listjob'])) {
            $_bjob = new BoJob();
            echo json_encode($_bjob->listjob($_uid, "going"));
            exit();
        }
        if (isset($_GET['addjob'])) {
            if ($this->checkisempty($_POST['title']) || $this->checkisempty($_POST['cont']) || $this->checkisempty($_POST['starttime']) || $this->checkisempty($_POST['endtime'])) {
                echo json_encode([
                    'err' => true,
                    "data" => "请填写完整"
                ]);
                exit();
            }
            $_bjob = new BoJob();
            if ($_bjob->addjob($_POST['title'], $_POST['cont'], $_POST['starttime'], $_POST['endtime'], $_uid)) {
                echo json_encode([
                    'err' => false,
                    "data" => ""
                ]);
            }
            exit();
        }
        if (isset($_GET['getwikipage'])) {
            $_bwiki = new BoWiki();
            echo json_encode($_bwiki->getwikipage($_GET['getwikipage']));
            exit();
        }
        //
        if (isset($_GET['getwikiclass'])) {
            $_bwiki = new BoWiki();
            echo json_encode($_bwiki->getwikiclass());
            exit();
        }
        // 获得app的开发权限
        if (isset($_GET['getapppower'])) {
            echo file_get_contents(DATA_DIR . DS . "apppower_" . $_GET['getapppower']);
            // echo "1111|12313";
            exit();
        }
        // 设置权限
        if (isset($_GET['setapppower'])) {
            if (DEV_PLACE == "cloud") {
                if (! $this->checkpower($_uid)) {
                    echo "no power";
                    exit();
                }
            }
            file_put_contents(DATA_DIR . DS . "apppower_" . $_GET['setapppower'], $_GET['powers']);

            if ($_GET['powers'] != "") {
                $_powers = explode("|", $_GET['powers']);
                $_token_arr = [];
                foreach ($_powers as $_power) {
                    $_token = $this->gettoken($_power);
                    if (! $_token['err']) {
                        if ($_token['data'] != $_uid) {

                            $_token_arr[] = $_token['data'];
                            $_datapath = BFW_LIB . DS . "Data" . DS . "Teamapp_" . $_token['data'];
                            $_data = [];
                            if (file_exists($_datapath)) {
                                $_data = unserialize(file_get_contents($_datapath));
                            }
                            $_tokenpar = [
                                $_GET['setapppower'],
                                $_uid
                            ];
                            if (! in_array($_tokenpar, $_data)) {
                                $_data[] = $_tokenpar;
                            }

                            file_put_contents($_datapath, serialize($_data));
                        }
                    }
                }
                file_put_contents(DATA_DIR . DS . "apppower_token_" . $_GET['setapppower'], implode("|", $_token_arr));
            }
            exit();
        }
        if (isset($_GET['delapp'])) {
            // die(APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['delapp']));
            if (FileUtil::delDirAndFile(APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['delapp']))) {
                echo "ok";
            }
            exit();
        }
        if (isset($_GET['getfiles'])) {
            $_filename = "";
            if (isset($_GET['isstatic'])) {
                $_filename = APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['getfiles']);
            } else {
                $_filename = APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['getfiles']);
            }
            if (file_exists($_filename)) {
                $_file_ext = strtolower(pathinfo($_filename, PATHINFO_EXTENSION));
                if ($_file_ext == "png" || $_file_ext == "jpg" || $_file_ext == "gif") {
                    echo file_get_contents($_filename);
                } else {
                    $_breakline = file_get_contents($_filename . ".break.debug");
                    echo json_encode([
                        'err' => false,
                        "data" => file_get_contents($_filename),
                        "filehash" => md5_file($_filename),
                        "breakline" => $_breakline ? explode("|", $_breakline) : []
                    ]);
                }
            } else {
                echo json_encode([
                    'err' => true,
                    "data" => "文件不存在"
                ]);
            }
            exit();
        }
        if (isset($_GET['delfiles'])) {
            if (isset($_GET['isstatic'])) {
                if ($this->delfile(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['delfiles']))) {
                    echo "ok";
                }
            } else {
                if ($this->delfile(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['delfiles']))) {
                    echo "ok";
                }
            }

            exit();
        }
        if (isset($_GET['createfolder'])) {
            if (isset($_GET['isstatic'])) {
                FileUtil::CreatDir(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . $_GET['createfolder']);
            }
            exit();
        }

        if (isset($_GET['getclass'])) {
            $_appname = $_GET['getclass'];
            $_method = [];
            $_modellist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . $_appname . DS . "Model" . DS);
            foreach ($_modellist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("App." . $_appname . ".Model." . $_file);
                // $_file .= "::getInstance()";
                // $_modellist[$_file]=$_cont_act_a;
            }
            $_clientllist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . $_appname . DS . "Client" . DS);
            foreach ($_clientllist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("App." . $_appname . ".Client." . $_file);
                // $_file .= "::getInstance()";
            }
            $_servicellist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . $_appname . DS . "Service" . DS);
            foreach ($_servicellist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("App." . $_appname . ".Service." . $_file);
                // $_file .= "::getInstance()";
            }
            $_controlllist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . $_appname . DS . "Controler" . DS);
            foreach ($_controlllist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("App." . $_appname . ".Controler." . $_file);
                // $_file .= "::getInstance()";
            }
            $_pluginlist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . $_appname . DS . "Plugin" . DS);
            foreach ($_pluginlist as &$_file) {
                $_file = str_replace(".php", "", $_file);
                $_method[$_file] = $this->getmethod("App." . $_appname . ".Plugin." . $_file);
                // $_file .= "::getInstance()";
            }
            echo json_encode([
                'class' => array_merge($_modellist, $_clientllist, $_servicellist, $_pluginlist),
                "method" => $_method
            ]);
            exit();
        }

        if (isset($_GET['getcloudpro'])) {
            echo $this->listapp();
            exit();
        }
        if (isset($_GET['renamefolder'])) {
            if (isset($_GET['isstatic'])) {
                // FileUtil::CreatDir(APP_BASE . DS . STATIC_NAME.DS.$_GET['parent'].DS.$_GET['createfolder']);
            }
            exit();
        }
        if (isset($_GET['renamefile'])) {
            $_filetype = $_GET['filetype'];
            if (in_array($_filetype, [
                "php",
                "js",
                "css",
                "html"
            ])) {
                if (isset($_GET['isstatic'])) {
                    // echo APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . str_replace("./", "", $_GET['renamefile']) . "." . $_filetyp;
                    rename(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . str_replace("./", "", $_GET['renamefile']) . "." . $_filetype, APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . str_replace("./", "", $_GET['newname']) . "." . $_filetype);
                    // FileUtil::CreatDir(APP_BASE . DS . STATIC_NAME.DS.$_GET['parent'].DS.$_GET['createfolder']);
                } else {
                    rename(APP_ROOT . DS . "App" . DS . $_GET['parent'] . str_replace("./", "", $_GET['renamefile']) . "." . $_filetype, APP_ROOT . DS . "App" . DS . $_GET['parent'] . str_replace("./", "", $_GET['newname']) . "." . $_filetype);
                }
            }

            exit();
        }
        if (isset($_GET['createfiles'])) {
            if (isset($_GET['isstatic'])) {
                $_filetype = "";
                $_filedata = "";
                $_f_get = $_GET['ftype'];
                if ($_f_get == "html") {
                    $_filetype = ".html";
                    $_filedata = file_get_contents(BFW_LIB . DS . "CodeT" . DS . "html.temp");
                } elseif ($_f_get == "css") {
                    $_filetype = ".css";
                } elseif ($_f_get == "js") {
                    $_filetype = ".js";
                }

                if (file_put_contents(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . $_GET['createfiles'] . $_filetype, $_filedata)) {
                    $this->commitlog($_GET['parent'], $_GET['createfiles'], "create", "dddd", $_uid);
                }
            } else {
                if ($_GET['pfolder'] == "Controler") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Controler_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Controler.php"))));
                }
                if ($_GET['pfolder'] == "Service") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Service_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Service.php"))));
                }
                if ($_GET['pfolder'] == "Validate") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Validate_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Validate.php"))));
                }
                if ($_GET['pfolder'] == "Model") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Model_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Model.php"))));
                }
                if ($_GET['pfolder'] == "Points") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Points_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Points.php"))));
                }
                if ($_GET['pfolder'] == "Client") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Client_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Client.php"))));
                }
                if ($_GET['pfolder'] == "View") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . "Controler_" . $_GET['createfiles'] . ".php", str_replace("CONTNAME", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Controler.php"))));
                }
                if ($_GET['pfolder'] == "Plugin") {
                    file_put_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . $_GET['createfiles'] . ".php", str_replace("Demo", $_GET['createfiles'], str_replace("DOM", $_GET['parent'], file_get_contents(BFW_LIB . DS . "CodeT" . DS . "Plugin.php"))));
                }
            }

            exit();
        }
        if (isset($_GET['checkfilesmod'])) {
            $_filename = "";
            if (isset($_GET['isstatic'])) {
                $_filename = APP_BASE . DS . STATIC_NAME . DS . str_replace("./", "", $_GET['checkfilesmod']);
            } else {
                $_filename = APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['checkfilesmod']);
            }
            if (file_exists($_filename)) {
                if (md5_file($_filename) == $_GET['filehash']) {
                    echo json_encode([
                        'err' => false,
                        "data" => false
                    ]);
                } else {
                    $_olddata = file_get_contents($_filename);
                    $diff = \Plugin\Diff::compare($_olddata, $_POST["data"]);
                    echo json_encode([
                        'err' => false,
                        "data" => true,
                        "diff" => \Plugin\Diff::toTable($diff),
                        "serverdata" => $_olddata,
                        "serverhash" => md5_file($_filename)
                    ]);
                }
            } else {
                echo json_encode([
                    'err' => true,
                    "data" => "文件不存在"
                ]);
            }
            exit();
        }
        if (isset($_GET['savefiles'])) {
            if (isset($_GET['isstatic'])) {
                if ($this->writefile(APP_BASE . DS . STATIC_NAME . DS . str_replace("./", "", $_GET['savefiles']), $_POST["data"], $_uid)) {
                    echo json_encode([
                        'err' => false,
                        "data" => md5_file(APP_BASE . DS . STATIC_NAME . DS . str_replace("./", "", $_GET['savefiles']))
                    ]);
                }
            } else {
                if ($this->writefile(APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['savefiles']), $_POST["data"], $_uid)) {
                    echo json_encode([
                        'err' => false,
                        "data" => md5_file(APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['savefiles']))
                    ]);
                }
            }
            exit();
        }
        if (isset($_GET['getcommitlog'])) {
            echo json_encode($this->getcommitlog($_GET['getcommitlog']));
            exit();
        }
        if (isset($_GET['getstaticurl'])) {
            if (DEV_PLACE == "cloud") {
                echo DEV_DEMO_URL . str_replace("/index.php", "", str_replace("/Cloud/", "", APPSELF)) . "/" . STATIC_NAME;
            } else {
                echo DEV_DEMO_URL . "/" . STATIC_NAME;
            }
            exit();
        }
        if (isset($_GET["getcontrolact"])) {
            $_controlfile = APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['getcontrolact']);
            if (file_exists($_controlfile)) {
                $classname = basename($_controlfile, ".php");
                $_ins = Core::LoadClass("App\\{$_GET['parent']}\\Controler\\{$classname}");
                $_data = get_class_methods($_ins);
                $_ret = [];
                foreach ($_data as $_item) {
                    if ($_item != "__get") {
                        if (DEV_PLACE == "cloud") {
                            $_ret[] = [
                                'url' => DEV_DEMO_URL . str_replace("/index.php", "", str_replace("/Cloud/", "", APPSELF)) . "/index.php?r=" . $_GET['parent'] . "|" . str_replace("Controler_", "", $classname) . "|" . $_item,
                                "name" => $_item
                            ];
                        } else {
                            $_ret[] = [
                                'url' => DEV_DEMO_URL . "index.php?r=" . $_GET['parent'] . "|" . str_replace("Controler_", "", $classname) . "|" . $_item,
                                "name" => $_item
                            ];
                        }
                    }
                }
                echo json_encode($_ret);
                exit();
            }
        }
        if (isset($_GET['getdebuginfo'])) {
            $_debug_file = APP_ROOT . DS . "App" . DS . "file_info.debug";
            echo json_encode(unserialize(file_get_contents($_debug_file)));
            exit();
        }
        if (isset($_GET['contdebug'])) {
            $_debug_file = APP_ROOT . DS . "App" . DS . "file_cont.debug";
            $_debug_info_file = APP_ROOT . DS . "App" . DS . "file_info.debug";
            file_put_contents($_debug_file, $_GET['contdebug']);
            if ($_GET['contdebug'] == "exit") {
                file_put_contents($_debug_info_file, serialize([]));
            }
            echo "ok";
            exit();
        }
        if (isset($_GET['savelayout'])) {
            $_appname = $_GET["projectname"];
            $_lay_file = APP_ROOT . DS . "App" . DS . "layout.debug";
            $_lay_data = file_get_contents($_lay_file);
            $_lay_arr = [];
            if ($_lay_data != "") {
                $_lay_arr = unserialize($_lay_data);
            }
            $_lay_arr[$_appname] = $_GET['files'];
            file_put_contents($_lay_file, serialize($_lay_arr));
            exit();
        }
        if (isset($_GET['getlayout'])) {
            $_appname = $_GET["projectname"];
            $_lay_file = APP_ROOT . DS . "App" . DS . "layout.debug";
            $_lay_data = file_get_contents($_lay_file);
            $_lay_arr = [];
            if ($_lay_data != "") {
                $_lay_arr = unserialize($_lay_data);
            }
            echo isset($_lay_arr[$_appname]) ? $_lay_arr[$_appname] : "";
            exit();
        }
        if (isset($_GET['addbreak'])) {
            $_line = intval($_GET["line"]) + 1;
            $_filename = APP_ROOT . DS . "App" . DS . str_replace("/", '', str_replace("\\", DS, $_GET["filename"]));
            $_debug_files = $_filename . ".debug";

            if (! file_exists($_debug_files)) {
                file_put_contents($_debug_files, file_get_contents($_filename));
            }
            $_debug_test_files = $_filename . ".test.debug";
            if (! file_exists($_debug_test_files)) {
                file_put_contents($_debug_test_files, file_get_contents($_debug_files));
            }
            FileUtil::insertstrbyline($_debug_test_files, $this->_debug_str, $_line);
            $_err = "";
            // 验证是否存在语法错误
            exec("D:\bfwsetup\bfw\php\php-5.6.27-nts\php -l {$_debug_test_files}", $output, $return);
            // var_dump($output);
            if ($return === 0) {

                @unlink($_debug_test_files);
                FileUtil::insertstrbyline($_debug_files, $this->_debug_str, $_line);
                $_debug_file = APP_ROOT . DS . "App" . DS . "file.debug";
                $_debug_data = file_get_contents($_debug_file);
                $_debug_arr = [];
                if ($_debug_data != "") {
                    $_debug_arr = unserialize($_debug_data);
                }
                if (! in_array($_filename, $_debug_arr)) {
                    $_debug_arr[] = $_filename;
                }
                file_put_contents($_debug_file, serialize($_debug_arr));

                $_debug_breakfile = $_filename . ".break.debug";
                $_debug_breakdata = file_get_contents($_debug_breakfile);
                $_debug_breakarr = [];
                if ($_debug_breakdata != "") {
                    $_debug_breakarr = explode("|", $_debug_breakdata);
                }
                $_line --;
                if (! in_array($_line, $_debug_breakarr)) {
                    $_debug_breakarr[] = $_line;
                }
                file_put_contents($_debug_breakfile, implode("|", $_debug_breakarr));
                echo "ok";
            } else {
                echo "error";
            }
            exit();
        }
        if (isset($_GET['clearbreak'])) {
            $_filename = APP_ROOT . DS . "App" . DS . str_replace("/", '', str_replace("\\", DS, $_GET["filename"]));
            $_debug_file = $_filename . ".debug";
            $_line = intval($_GET["line"]) + 1;
            FileUtil::deletestrbyline($_debug_file, $this->_debug_str, $_line);
            if (strpos(file_get_contents($_debug_file), $this->_debug_str) === false) {
                $_debug_file = APP_ROOT . DS . "App" . DS . "file.debug";
                $_debug_data = file_get_contents($_debug_file);
                $_debug_arr = [];
                if ($_debug_data != "") {
                    $_debug_arr = unserialize($_debug_data);
                }

                for ($i = 0; $i < count($_debug_arr); $i ++) {
                    if ($_debug_arr[$i] == $_filename) {
                        unset($_debug_arr[$i]);
                    }
                }

                file_put_contents($_debug_file, serialize($_debug_arr));
            }

            $_debug_breakfile = $_filename . ".break.debug";
            $_debug_breakdata = file_get_contents($_debug_breakfile);
            $_debug_breakarr = [];
            if ($_debug_breakdata != "") {
                $_debug_breakarr = explode("|", $_debug_breakdata);
            }
            $_line --;
            for ($i = 0; $i < count($_debug_breakarr); $i ++) {
                if ($_debug_breakarr[$i] == $_line) {
                    unset($_debug_breakarr[$i]);
                }
            }
            file_put_contents($_debug_breakfile, implode("|", $_debug_breakarr));
            echo "ok";
            exit();
        }
        if (isset($_GET['getappdir'])) {
            $_file = str_replace("\\", '', str_replace("/", '', $_GET['getappdir']));
            if (isset($_GET['isstatic'])) {
                echo json_encode(FileUtil::getfilebydir($_file, APP_BASE . DS . STATIC_NAME . DS, ".debug"));
            } else {
                echo json_encode(FileUtil::getfilebydir($_file, APP_ROOT . DS . "App" . DS, ".debug"));
            }
            exit();
        }

        if (isset($_GET['getappversion'])) {
            echo json_encode($this->getappversion($_GET['getappversion']));
            exit();
        }
        if (isset($_GET['addappversion'])) {
            if ($this->addappversion($_GET['addappversion'], $_GET['memo'], $_uid)) {
                echo "ok";
            }
            exit();
        }
        if (isset($_GET['setappversion'])) {
            echo json_encode($this->setappversion($_GET['setappversion'], $_GET['v'], $_uid));

            exit();
        }
        if (isset($_GET['uploadapp'])) {
            $_uid = BoCache::Cache("app_server_uid");
            if ($_uid != "") {
                $this->uploadapp($_GET['uploadapp'], isset($_GET['totemp']) ? true : false);
            } else {
                echo "login first";
            }
            exit();
        }
        if (isset($_GET['initapp'])) {
            // echo $_uid;
            // exit();
            if (DEV_PLACE == "cloud") {
                if (! $this->checkpower($_uid)) {
                    echo "no power";
                    // header("Location:/Cloud/" . $_uid . "/?webide=1");
                    exit();
                }
            }
            $_appname = ucwords($_GET['initapp']);
            if (preg_match("/^[a-zA-Z]{3,20}$/", $_appname)) {
                $dbpara = explode("|", $_GET['dbinfo']);
                if (isset($_GET['tempid'])) {
                    $this->getapp($_GET['tempid'], $_appname, $dbpara, true);
                } else {
                    if ($this->initapp($_appname)) {
                        echo "ok";
                    } else {
                        echo "fail";
                    }
                }
            } else {
                echo "fail";
            }
            exit();
        }
        if (isset($_GET['addcont'])) {
            if (isset($_GET['addcont'])) {
                $para = explode("|", $_GET['addcont']);
                if (count($para) >= 2) {
                    $_contdomname = $_GET['addcont'];
                    if (preg_match("/^[a-zA-Z]{3,20}$/", $para[0]) && preg_match("/^[a-zA-Z]{3,20}$/", $para[1])) {
                        if ($this->addcont(ucwords($para[0]), ucwords($para[1]))) {
                            echo "ok";
                        } else {
                            echo "fail";
                        }
                    }
                }
            } else {
                echo "cont wrong";
            }
            exit();
        }
        if (isset($_GET['getapp'])) {
            $_uid = BoCache::Cache("app_server_uid");
            if ($_uid != "") {
                if (isset($_GET['dbinfo'])) {
                    $dbpara = explode("|", $_GET['dbinfo']);
                    if (count($dbpara) == 4) {
                        $this->getapp($_GET['getapp'], isset($_GET['appname']) ? $_GET['appname'] : $_GET['getapp'], $dbpara, isset($_GET['appname']) ? true : false);
                    } else {
                        echo "dbconf wrong";
                    }
                }
            } else {
                echo "login first";
            }

            exit();
        }

        $_bocodeins = Core::LoadClass("Lib\\BoCheck");
        if ($_bocodeins::CheckDir()) {
            if ($this->_mode == "console") {
                BoRes::View("console", "System", "v1");
                exit();
            }

            if (DEV_PLACE == "cloud") {
                if (! isset($_GET['targetappname'])) {
                    if ($_uid == "") {
                        if (strpos(URL, 'Cloud') === false) {
                            // BFWUSER_HOST_URL
                            // BoRes::View("cloudreg", "System", "v1");
                        } else {
                            // die("s");
                            header("Location:/?webide=1");
                            // BoRes::View("cloudreg", "System", "v1");
                        }
                        exit();
                    } else {
                        if (strpos(URL, 'Cloud') === false) {
                            header("Location:/Cloud/" . $_uid . "/?webide=1");
                            exit();
                        }
                        if (strpos(URL, $_uid) === false) {
                            header("Location:/Cloud/" . $_uid . "/?webide=1");
                            exit();
                        }
                    }
                }
            }

            BoRes::View("webide", "System", "v1", [
                "uid" => $_uid
            ]);
            exit();
        }
    }
}
?>