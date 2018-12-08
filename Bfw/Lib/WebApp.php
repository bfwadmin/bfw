<?php
namespace Lib;

use Lib\Util\UrlUtil;
use Lib\Util\FileUtil;
use Lib\Exception\CoreException;
use Lib\Util\ArrayUtil;

// Bfw::import("App.Lang." . DOMIAN_VALUE . "." . LANG);
class WebApp extends WangBo
{

    private static $_instance = null;

    /**
     * 验证格式是否正确
     *
     * @param string $str            
     * @return bool
     */
    public static Function ValidateStr($str)
    {
        return preg_match("/^[a-zA-Z]{2,20}$/", $str);
    }

    /**
     * 执行action
     *
     * @param string $str            
     */
    private Function ExecuteAction($str)
    {
        include ("data://text/plain," . urlencode($str));
    }
    // 或获得单例
    public static function getInstance()
    {
        if (is_null(self::$_instance) || ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function getPara($_class, $_methodname)
    {
        $_para = array();
        // 有待优化
        $_method = new \ReflectionMethod($_class, $_methodname);
        if ($_method->isPublic()) {
            $_params = $_method->getParameters();
            foreach ($_params as $param) {
                if (strtolower(PHP_SAPI) === 'cli') {
                    $getclipara = function () {
                        $_cliarr = array();
                        global $argv;
                        if (count($argv) > 4) {
                            for ($i = 4; $i < count($argv); $i ++) {
                                $_keyval = explode("=", $argv[$i]);
                                if (count($_keyval) === 2) {
                                    $_cliarr[$_keyval[0]] = $_keyval[1];
                                }
                            }
                        }
                        return $_cliarr;
                    };
                    $_cliparaval = $getclipara();
                    if (isset($_cliparaval[$param->getName()])) {
                        $_para[] = $_cliparaval[$param->getName()];
                    } else {
                        if ($param->isOptional()) {
                            $_para[] = $param->getDefaultValue();
                        } else {
                            $_para[] = "";
                        }
                    }
                } else {
                    if (ROUTETYPE == 2) {
                        $_key_arr = Bfw::GetParaByUrl();
                        $_key_arr = array_merge($_key_arr, $_GET, $_POST);
                        if (isset($_key_arr[$param->getName()])) {
                            $_para[] = Bfw::UrlCode($_key_arr[$param->getName()], true);
                        } else {
                            if ($param->isOptional()) {
                                
                                $_para[] = $param->getDefaultValue();
                            } else {
                                $_para[] = "";
                            }
                        }
                    }
                    if (ROUTETYPE == 1) {
                        $_key_arr = array_merge($_GET, $_POST);
                        if (isset($_key_arr[$param->getName()])) {
                            $_para[] = $_key_arr[$param->getName()];
                        } else {
                            if ($param->isOptional()) {
                                
                                $_para[] = $param->getDefaultValue();
                            } else {
                                $_para[] = "";
                            }
                        }
                    }
                }
            }
        }
        return $_para;
    }

    /**
     * 执行
     *
     * @param string $_controler            
     * @param string $_action            
     * @param string $_domian            
     * @throws Exception
     */
    public function Execute($_controler, $_action, $_domian)
    {
        if (RUN_MODE == "S") {
            $_sretmsg = function ($_err, $_data, $_trace = "") {
                return array(
                    "bo_err" => $_err,
                    "bo_data" => $_data,
                    "bo_trace" => $_trace
                );
            };
            // return $_sretmsg(true, "init lock err");
            if (defined("SERVICE_ALLOW_IP")) {
                $_allowiparr = explode("|", SERVICE_ALLOW_IP);
                if (! in_array(IP, $_allowiparr)) {
                    return $_sretmsg(true, "ip deny");
                }
            }
            // 服务端服务注册
            if (isset($_GET['notify'])) {
                $_serverfilelist = FileUtil::getFileListByDir(APP_ROOT . "/App/" . $_domian . "/Service/");
                $_server_array = array();
                foreach ($_serverfilelist as $_file) {
                    $_service_name = str_replace(".php", "", $_file);
                    Bfw::import("App." . $_domian . ".Service." . $_service_name);
                    $r = new \reflectionclass($_service_name);
                    $_cinstance = Core::LoadClass("App\\" . $_domian . "\\Service\\" . $_service_name);
                    $_seckey = $_cinstance->getkey();
                    foreach ($r->getmethods() as $key => $methodobj) {
                        if ($methodobj->ispublic() && ! in_array($methodobj->name, array(
                            "getInstance",
                            "getkey",
                            "__construct",
                            "__destruct"
                        ))) {
                            $_server_array[] = array(
                                str_replace("Service_", "", $_service_name),
                                $methodobj->name,
                                $_domian,
                                $_seckey
                            );
                        }
                    }
                }
                $_http_pro = "http://";
                if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
                    $_http_pro = "https://";
                }
                $_url_par = parse_url(SERVICE_REG_CENTER_URL);
                // var_dump($_server_array);
                Bfw::AsynPost($_url_par['host'], 80, $_url_par['path'] . "?" . $_url_par['query'] . "act=AddData", array(
                    "data" => serialize($_server_array),
                    "host" => $_http_pro . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
                    "lang" => "php"
                ), 10);
                die("success");
            }
            // 服务端执行
            if (isset($_POST['arg'])) {
                $arg_data = unserialize($_POST['arg']);
            } else {
                $arg_data = null;
            }
            $_cinstance = Core::LoadClass("App\\{$_domian}\\Service\\Service_" . $_controler);
            if ($_cinstance) {
                if ($_cinstance->getkey() == $_POST['key']) {
                    if (method_exists($_cinstance, $_action)) {
                        
                        $_service_mode_arr = Bfw::Config("Service", "config", "System");
                        $_runservicename = $_domian . "_" . $_controler . "_" . $_action;
                        // if (isset($_service_mode_arr[$_runservicename])) {
                        if (isset($_service_mode_arr[$_runservicename]['para'])) {
                            $_runservicename = $_runservicename . var_export($arg_data[$_service_mode_arr[$_runservicename]['para']], true);
                        }
                        if (isset($_service_mode_arr[$_runservicename]['type']) && $_service_mode_arr[$_runservicename]['type'] === 'queue') {
                            
                            $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLE_NAME, $_runservicename);
                            if ($_lock) {
                                try {
                                    $_begintime = microtime(true);
                                    while (! $_lock->lock()) {
                                        usleep(LOCK_WAIT_TIME * 1000000);
                                        Bfw::Debug("LOCK WAIT:" . LOCK_WAIT_TIME . "s");
                                        if (microtime(true) - $_begintime >= LOCK_TIMEOUT) {
                                            goto service_pass;
                                        }
                                    }
                                    
                                    // usleep(2000000);
                                    $ret = call_user_func_array(array(
                                        $_cinstance,
                                        $_action
                                    ), $arg_data);
                                    $_lock->unlock();
                                    if (WEB_DEBUG) {
                                        $total = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; // 计算差值
                                        return $_sretmsg(false, $ret, Bfw::DebugEcho($total));
                                    } else {
                                        return $_sretmsg(false, $ret);
                                    }
                                    
                                    service_pass:
                                    Bfw::Debug("LOCK TIMEOUT:" . LOCK_TIMEOUT . "s");
                                    if (WEB_DEBUG) {
                                        $total = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; // 计算差值
                                        return $_sretmsg(true, "timeout", Bfw::DebugEcho($total));
                                    } else {
                                        return $_sretmsg(true, "timeout");
                                    }
                                } catch (\Exception $e) {
                                    $_lock->unlock();
                                    Bfw::LogToFile("lock exception:" . $e->getMessage());
                                    return $_sretmsg(true, "lock_excption");
                                }
                            } else {
                                return $_sretmsg(true, "init lock err");
                            }
                            // }
                        } elseif (isset($_service_mode_arr[$_runservicename]['type']) && $_service_mode_arr[$_runservicename]['type'] != 'queue') {
                            $_cachekey = "call_limit_s" . $_runservicename;
                            if (isset($_service_mode_arr[$_runservicename]['limit'])) {
                                $_cachedata = Core::Cache($_cachekey);
                                if (is_numeric($_cachedata) && $_cachedata > 0) {
                                    if (time() - $_cachedata < $_service_mode_arr[$_runservicename]['limit']) {
                                        return $_sretmsg(true, "服务器忙,请稍后再试");
                                    }
                                }
                                Core::Cache($_cachekey, time(), 180);
                            }
                        }
                        $ret = call_user_func_array(array(
                            $_cinstance,
                            $_action
                        ), $arg_data);
                        if (WEB_DEBUG) {
                            $total = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; // 计算差值
                            return $_sretmsg(false, $ret, Bfw::DebugEcho($total));
                        } else {
                            return $_sretmsg(false, $ret);
                        }
                    }
                } else {
                    throw new CoreException(Bfw::Config("Sys", "webapp", "System")['class_not_found']);
                }
            }
        } else {
            // 异步cache
            if (isset($_GET['updatecache'])) {
                $_obj = unserialize($_POST['obj']);
                $_keyname = $_POST['key'];
                $_cachetime = $_POST['cachetime'];
                $_method = $_POST['method'];
                $_arg = unserialize($_POST['arg']);
                $_key = $_POST['rpckey'];
                $_cachedependcy = $_POST['cachedependcy'];
                if ($_key == CACHE_DEPENDCY_KEY) {
                    $_obj->RunBg();
                    call_user_func_array([
                        $_obj,
                        $_method
                    ], $_arg);
                }
                exit();
            }

            // 服务端删除
            if (isset($_GET['updateyourconfig'])) {
                if (isset($_GET['servicename']) && isset($_GET['methodname']) && isset($_GET['domainname'])) {
                    $_config_file = APP_ROOT . DS . 'App' . DS . $_GET['domainname'] . DS . "Config" . DS . "Client" . DS . $_GET['servicename'] . DS . $_GET['methodname'] . ".php";
                    unlink($_config_file);
                    exit();
                }
            }
            if (SHOW_APIDOC && isset($_GET['getapidoc'])) {
                $_filelist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "Controler" . DS);
                $_con_act_array = array();
                foreach ($_filelist as $_file) {  
                    $_control_name = str_replace(".php", "", $_file);
                    Bfw::import("App." . DOMIAN_VALUE . ".Controler." . $_control_name);
                    $_control_name_dll = "App\\" . DOMIAN_VALUE . "\\Controler\\" . $_control_name;
                    $r = new \reflectionclass($_control_name_dll);
                    $_cont_act_a = [];
                    foreach ($r->getmethods() as $key => $methodobj) {
                        if ($methodobj->ispublic()) {
                            if ($methodobj->name != "__get") {
                                $_cont_act_a[] = array(
                                    $methodobj->name,
                                    str_replace("/**", "", $methodobj->getDocComment())
                                );
                            }
                        }
                    }
                    $_con_act_array[str_replace("Controler_", "", $_control_name)] = $_cont_act_a;
                }
                Core::V("apilist", "System", "v1", [
                    'con_act_array' => $_con_act_array
                ]);
                
                exit();
            }
            if (GEN_CODE && WEB_DEBUG && isset($_GET['gencode'])) {
                $_bocodeins = Core::LoadClass("Lib\\BoCode");
                $_bocodeins->Generate($_domian, isset($_GET['t']) ? $_GET['t'] : '', isset($_GET['o']) ? true : false);
                exit();
            }
            if (WEB_DEBUG && isset($_GET['uploadapp'])) {
                set_time_limit(0); // 无时间限制
                FileUtil::zip(APP_ROOT . DS . "App" . DS . $_GET['uploadapp'] , RUNTIME_DIR . $_GET['uploadapp'] . "back.zip");
               // FileUtil::zip(APP_BASE . DS . "static" . DS . $_GET['uploadapp'] , RUNTIME_DIR . $_GET['uploadapp'] . "static.zip");
                $_bocodeins = Core::LoadClass("Lib\\BoDb");
                $_bocodeins->Backup($_GET['uploadapp'], RUNTIME_DIR.$_GET['uploadapp'] . ".sql");
                //发送到服务器
                echo "ok";
                exit();
            }
            if (WEB_DEBUG && isset($_GET['initproject'])) {
                $_bocodeins = Core::LoadClass("Lib\\BoCode");
                $_bocodeins->InitProject($_GET['initproject'] );
                echo "ok";
                exit();
            }
            if (WEB_DEBUG && isset($_GET['addcont'])) {
                $_bocodeins = Core::LoadClass("Lib\\BoCode");
                $para=explode("|",$_GET['addcont']);
                if(count($para)>=2){
                    $_bocodeins->InitProject($para[0],$para[1]);
                    echo "ok";
                }else{
                    echo "参数错误,请按照这样 bfw add appname controlername'";
                }
                exit();
            }
            if (WEB_DEBUG && isset($_GET['getapp'])) {
                set_time_limit(0); // 无时间限制
                //$_appdata = file_get_contents(APP_HOST_URL . $_GET['getapp'] . "back.zip");
               // file_put_contents(RUNTIME_DIR . $_GET['getapp'] . "back.zip", $_appdata);
                FileUtil::unzip(RUNTIME_DIR . $_GET['getapp'] . "back.zip", APP_ROOT . DS . "App" . DS . $_GET['getapp']);
               // $_staticdata = file_get_contents(APP_HOST_URL . $_GET['getapp'] . "static.zip");
               // file_put_contents(RUNTIME_DIR . $_GET['getapp'] . "static.zip", $_staticdata);
             //   FileUtil::unzip(RUNTIME_DIR . $_GET['getapp'] . "static.zip", APP_BASE . DS . "static" . DS . $_GET['getapp']);
                $_bocodeins = Core::LoadClass("Lib\\BoDb");
                $_bocodeins->Restore($_GET['getapp'], RUNTIME_DIR.$_GET['getapp'] . ".sql");
                echo "ok";
                exit();
            }
            if (WEB_DEBUG && isset($_GET['console'])) {
                Core::V("console", "System", "v1");
                exit();
            }
            //根据control生成控制器与动作器
            if (AUTO_CON && WEB_DEBUG) {
                $_filelist = FileUtil::getFileListByDir(APP_ROOT . DS . DOMIAN_VALUE . DS . "Controler" . DS);
                $_con_act_array = array();
                foreach ($_filelist as $_file) {
                    $_control_name = str_replace(".php", "", $_file);
                    Bfw::import("App." . DOMIAN_VALUE . ".Controler." . $_control_name);
                    
                    $r = new \reflectionclass($_control_name);
                    foreach ($r->getmethods() as $key => $methodobj) {
                        if ($methodobj->ispublic()) {
                            preg_match_all("/[\x{4e00}-\x{9fa5}]+/u", $methodobj->getDocComment(), $chinese);
                            $_txt = implode("", $chinese[0]);
                            if ($_txt != "") {
                                $_con_act_array[] = array(
                                    str_replace("Controler_", "", $_control_name),
                                    $methodobj->name,
                                    DOMIAN_VALUE,
                                    $_txt
                                );
                            }
                        }
                    }
                }
                if (! empty($_con_act_array)) {
                    $_powerins = Core::LoadClass("App\\" . DOMIAN_VALUE . "\\Client\\Client_Power");
                    $_powerins->InsertOrUpdate($_con_act_array);
                }
            }
            if(defined("ALLOW_DOMIAN")){
                if (strpos(strtolower(ALLOW_DOMIAN), strtolower(DOMIAN_VALUE))===false) {
                    header('HTTP/1.1 404 Not Found');
                    header("status: 404 Not Found");
                    exit();
                }
            }

            // 消费端执行
            
            if (self::ValidateStr($_controler) && self::ValidateStr($_action) && self::ValidateStr($_domian) && strtolower($_domian) != "system") {
                if (! URL_CASE_SENS) {
                    $_domian = ucfirst($_domian);
                    $_controler = ucfirst($_controler);
                    $_action = ucfirst($_action);
                }
                $_ackey = $_domian . "_" . $_controler . "_" . $_action;
                
                if (FILTER_CONT) {
                    $_allowcont_arr = Bfw::Config("Controler", "allow", "System");
                    if (! in_array($_domian . "_" . $_controler, $_allowcont_arr)) {
                        throw new CoreException(Bfw::Config("Sys", "webapp", "System")['controler_not_allow'] . $_controler);
                    }
                }
                
                $_points = Core::LoadClass("App\\{$_domian}\\Points\\Points_{$_controler}");
                if ($_points) {
                    $_pret = true;
                    if (method_exists($_points, $_action . "_Before")) {
                        $_pret = call_user_func_array(array(
                            $_points,
                            $_action . "_Before"
                        ), array());
                    }
                    
                    if ($_pret) {
                        if (method_exists($_points, "Before")) {
                            $_pret = call_user_func_array(array(
                                $_points,
                                "Before"
                            ), array());
                        }
                        
                        if ($_pret) {
                            $_expiretime = &Registry::getInstance()->get("path_cache_" . md5(UrlUtil::getrelativepath()));
                            if (is_numeric($_expiretime) && $_expiretime > 0) {
                                $this->Expire($_expiretime);
                            }
                            $_viewcachedata = $this->GetCache("cont_view_cache_" . $_controler . $_action . $_domian);
                            if ($_viewcachedata != null && $_viewcachedata != false) {
                                echo $_viewcachedata;
                            } else {
                                Bfw::import("Lib.BoControler");
                                $_cinstance = Core::LoadClass("App\\{$_domian}\\Controler\\Controler_" . $_controler);
                                if ($_cinstance) {
                                    $responseformat = "html";
                                    $expire = 0;
                                    if (isset($_cinstance->_config)) {
                                        if (isset($_cinstance->_config['allowip']) && is_array($_cinstance->_config['allowip'])) {
                                            if (! in_array(IP, $_cinstance->_config['allowip'])) {
                                                throw new CoreException(Bfw::Config("Sys", "webapp", "System")['ip_disallow']);
                                            }
                                        }
                                        if (isset($_cinstance->_config['runmode'])) {
                                            if (! in_array(strtolower(PHP_SAPI), $_cinstance->_config['runmode'])) {
                                                throw new CoreException(PHP_SAPI . Bfw::Config("Sys", "webapp", "System")['run_mode_disallow']);
                                            }
                                        }
                                        if (isset($_cinstance->_config['allowdevice']) && is_array($_cinstance->_config['allowdevice'])) {
                                            $_device = $this->isMobile() ? "mobile" : "pc";
                                            if (! in_array($_device, $_cinstance->_config['allowdevice'])) {
                                                throw new CoreException(Bfw::Config("Sys", "webapp", "System")['device_disallow']);
                                            }
                                        }
                                        if (isset($_cinstance->_config['responseformat'])) {
                                            $responseformat = $_cinstance->_config['responseformat'];
                                        }
                                        if (isset($_cinstance->_config['expire'])) {
                                            if (is_numeric($_expiretime) && $_expiretime > 0) {} else {
                                                $expire = $_cinstance->_config['expire'];
                                            }
                                        }
                                    }
                                    if ($expire > 0) {
                                        $this->Expire($expire);
                                    }
                                    if (method_exists($_cinstance, $_action)) {
                                        $ret = call_user_func_array(array(
                                            $_cinstance,
                                            $_action
                                        ), $this->getPara("App\\{$_domian}\\Controler\\Controler_" . $_controler, $_action));
                                        
                                        switch ($responseformat) {
                                            case "xml":
                                                header("Content-type: text/xml");
                                                echo ArrayUtil::Toxml($ret);
                                                break;
                                            case "json":
                                                header("Content-type: text/json");
                                                echo json_encode($ret);
                                                break;
                                            default:
                                                // header("Content-type: {$responseformat}");
                                                // echo $ret;
                                                break;
                                        }
                                        if (method_exists($_points, $_action . "_After")) {
                                            call_user_func_array(array(
                                                $_points,
                                                $_action . "_After"
                                            ), array(
                                                $ret
                                            ));
                                        }
                                        if (method_exists($_points, "After")) {
                                            call_user_func_array(array(
                                                $_points,
                                                "After"
                                            ), array(
                                                $ret
                                            ));
                                        }
                                    } else {
                                        
                                        throw new CoreException(Bfw::Config("Sys", "webapp", "System")['action_not_found'] . $_action);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                throw new CoreException(Bfw::Config("Sys", "webapp", "System")['con_act_format_wrong']);
            }
        }
    }
}
?>