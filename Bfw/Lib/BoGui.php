<?php
namespace Lib;

use Lib\Util\FileUtil;

class BoGui
{

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
            file_put_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey(), $_uid);
            // BoCache::Cache(SESS_ID . "app_server_uid", $_uid, 0);
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
            return file_get_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey());
            // $_key=.
            // return BoCache::Cache(SESS_ID . "app_server_uid");
        }
        if (DEV_PLACE == "local") {
            return BoCache::Cache("app_server_uid");
        }
        return "";
    }

    private function logout()
    {
        if (DEV_PLACE == "cloud") {
            return file_put_contents(BFW_LIB . DS . "Cache" . DS . $this->genkey(), "");
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

    public function Run()
    {
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
            if (substr($_file, strlen($_file) - 4) == ".png" || substr($_file, strlen($_file) - 4) == ".jpg") {
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
                if (strtolower($_item) != "config.php" && strtolower($_item) != ".." && strtolower($_item) != ".") {
                    // if (strpos(URL, $_uid) === true) {
                    $_dirarr[] = [
                        'type' => 'self',
                        "name" => $_item,
                        "url" => ""
                    ];
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
                                header("Location:/Cloud/" . $_uid . "/?webide=1");
                                exit();
                            }
                        }
                    }
                } else {
                    if (! $this->checkpower($_uid)) {
                        header("Location:/Cloud/" . $_uid . "/?webide=1");
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
                    BoRes::View("cloudreg", "System", "v1");
                }
                
                exit();
            }
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
            if (isset($_GET['isstatic'])) {
                echo file_get_contents(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['getfiles']));
            } else {
                echo file_get_contents(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['getfiles']));
            }
            
            exit();
        }
        if (isset($_GET['delfiles'])) {
            if (isset($_GET['isstatic'])) {
                @unlink(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['delfiles']));
            } else {
                @unlink(APP_ROOT . DS . "App" . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['delfiles']));
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
            
            echo json_encode([
                'class' => array_merge($_modellist, $_clientllist, $_servicellist),
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
                $_f_get = $_GET['ftype'];
                if ($_f_get == "html") {
                    $_filetype = ".html";
                } elseif ($_f_get == "css") {
                    $_filetype = ".css";
                } elseif ($_f_get == "js") {
                    $_filetype = ".js";
                }
                file_put_contents(APP_BASE . DS . STATIC_NAME . DS . $_GET['parent'] . DS . str_replace("./", "", $_GET['pfolder']) . DS . $_GET['createfiles'] . $_filetype, "");
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
            }
            
            exit();
        }
        if (isset($_GET['savefiles'])) {
            if (isset($_GET['isstatic'])) {
                if (file_put_contents(APP_BASE . DS . STATIC_NAME . DS . str_replace("./", "", $_GET['savefiles']), $_POST["data"])) {
                    echo "ok";
                }
            } else {
                if (file_put_contents(APP_ROOT . DS . "App" . DS . str_replace("./", "", $_GET['savefiles']), $_POST["data"])) {
                    echo "ok";
                }
            }
            exit();
        }
        if (isset($_GET['getstaticurl'])) {
            if (DEV_PLACE == "cloud") {
                echo DEV_DEMO_URL .str_replace("/index.php","", str_replace("/Cloud/", "", APPSELF)) . "/" . STATIC_NAME;
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
                                'url' => DEV_DEMO_URL .str_replace("/index.php","", str_replace("/Cloud/", "", APPSELF))  . "/index.php?r=" . $_GET['parent'] . "|" . str_replace("Controler_", "", $classname) . "|" . $_item,
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
        if (isset($_GET['getappdir'])) {
            $_file = str_replace("\\", '', str_replace("/", '', $_GET['getappdir']));
            if (isset($_GET['isstatic'])) {
                echo json_encode(FileUtil::getfilebydir($_file, APP_BASE . DS . STATIC_NAME . DS));
            } else {
                // echo APP_ROOT;
                echo json_encode(FileUtil::getfilebydir($_file, APP_ROOT . DS . "App" . DS));
            }
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
                            BoRes::View("cloudreg", "System", "v1");
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