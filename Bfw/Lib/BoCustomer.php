<?php
namespace Lib;

use Lib\Exception\CoreException;
use Lib\Util\UrlUtil;
use Lib\Util\ArrayUtil;
use Lib\Util\HttpUtil;

class BoCustomer extends Wangbo
{

    /**
     * 验证格式是否正确
     *
     * @param string $str            
     * @return bool
     */
    private function ValidateStr($str)
    {
        return preg_match("/^[a-zA-Z]{2,20}$/", $str);
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
                        $_key_arr = BoRoute::GetParaByUrl();
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
                    if (ROUTETYPE == 1 || ROUTETYPE == 0) {
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

    public function work($_controler, $_action, $_domian)
    {
        // 异步cache
        if (isset($_GET['updatecache'])) {}
        
        // 服务端删除
        if (isset($_GET['notify'])) {
            if (isset($_GET['sername']) && isset($_GET['domname'])) {
                $_key = md5("dom_" . $_GET['domname'] . $_GET['sername']);
                $_url = SERVICE_REG_CENTER_URL . "?dom=" . $_GET['domname'] . "&act=get&cont=service&sername=" . $_GET['sername'] . "&notifyurl=" . urlencode(SERVICE_NOTIFY_URL);
                $_data = HttpUtil::HttpGet($_url);
                if ($_data['err']) {
                    // throw new HttpException('get service list http err,' . $_data['data']);
                } else {
                    $_json_data = json_decode($_data['data'], true);
                    if (empty($_json_data)) {
                        // throw new HttpException('get service list http err,empty data' );
                    }
                    Core::Cache($_key, $_json_data, 0);
                    // return $_json_data;
                }
                // $_coreins = Core::LoadClass("Lib\\Core");
                // $_coreins->Cache($_key, "");
                exit();
            }
        }
        
        if (defined("ALLOW_DOMIAN")) {
            if (ALLOW_DOMIAN != "*" && strpos(strtolower(ALLOW_DOMIAN), strtolower(DOMIAN_VALUE)) === false) {
                header('HTTP/1.1 404 Not Found');
                header("status: 404 Not Found");
                exit();
            }
        }
        // client异步执行task
        global $argv;
        
        if (strtolower(PHP_SAPI) === 'cli' && isset($argv[4])) {
            if ($argv[4] == "cache") {
                $_queuename = "cache_list";
                echo "listen task on " . $_queuename . "\r\n";
                do {
                    $_cachedata = BoQueue::Dequeue($_queuename);
                    $_cachelist = BoCache::Cache(AYC_CACHE_NAME);
                    if (is_array($_cachedata)) {
                        echo "rec count " . count($_cachedata) . "\r\n";
                        if (is_array($_cachelist)) {
                            $_cachelist = array_merge($_cachedata, $_cachelist);
                        } else {
                            $_cachelist = $_cachedata;
                        }
                    }
                    BoCache::Cache(AYC_CACHE_NAME, $_cachelist);
                    // echo $_cachelist;
                    echo "merge count " . count($_cachelist) . "\r\n";
                    // usleep(CACHE_UPDATE_INTVAL_TIME * 1000000);
                    // continue;
                    if (is_array($_cachelist)) {
                        foreach ($_cachelist as $item => $_data) {
                            $_expire = BoCache::Cache($item . "expire");
                            echo "check key " . $item . " " . $_expire . " ";
                            if ($_expire == "") {
                                // $_data = BoCache::Cache("asy_" . $item);
                                if ($_data != "") {
                                    if (isset($_data['key']) && isset($_data['cachetime']) && isset($_data['method']) && isset($_data['arg'])) {
                                        echo "update cache task " . $_data['key'] . " mem:" . memory_get_usage() . $_data['method'] . "\r\n";
                                        $_obj = $_data['obj'];
                                        $_obj->RunBg();
                                        call_user_func_array([
                                            $_obj,
                                            $_data['method']
                                        ], $_data['arg']);
                                        $_data = null;
                                        $_obj = null;
                                    } else {
                                        echo "fromat wrong\r\n";
                                    }
                                } else {
                                    echo "no data\r\n";
                                }
                            } else {
                                echo "no expired\r\n";
                            }
                        }
                    } else {
                        echo "no task\r\n";
                    }
                    $_cachelist = null;
                    $_cachedata = null;
                    // die();
                    usleep(CACHE_UPDATE_INTVAL_TIME * 1000000);
                } while (true);
                exit();
            }
            if ($argv[4] == "queue") {
                $_queuename = "service" . $_domian . "_" . $_controler . "_" . $_action;
                echo "listen task on " . $_queuename;
                do {
                    $_data = BoQueue::Dequeue($_queuename);
                    if ($_data != "") {
                        if (isset($_data['reqid']) && isset($_data['obj']) && isset($_data['reqmethod']) && isset($_data['reqargs'])) {
                            echo "get task" . $_data['reqid'] . "\r\n";
                            $_obj = unserialize($_data['obj']);
                            $_obj->RunBg();
                            $_runret = call_user_func_array([
                                $_obj,
                                $_data['reqmethod']
                            ], $_data['reqargs']);
                            BoQueue::Ack();
                            // 执行完输出结果
                            BoCache::DelC($_data['oncekey']);
                            BoCache::Cache($_data['reqid'], $_runret);
                            echo "task done\r\n";
                            $_obj = null;
                            $_data = null;
                        }
                    } else {
                        echo "no_task\r\n";
                    }
                    usleep(QUEUE_INTVAL_TIME * 1000000);
                } while (true);
                exit();
            }
        }
        // 消费端执行
        if ($this->ValidateStr($_controler) && $this->ValidateStr($_action) && $this->ValidateStr($_domian) && strtolower($_domian) != "system") {
            
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
            $_pret = true;
            $_pointfile = APP_ROOT . DS . "App" . DS . $_domian . DS . "Points" . DS . "Points_" . $_controler . ".php";
            if (file_exists($_pointfile)) {
                $_points = Core::LoadClass("App\\{$_domian}\\Points\\Points_{$_controler}");
                if ($_points) {
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
                    }
                }
            }
            $ret = "";
            if ($_pret) {
                $_expiretime = &Registry::getInstance()->get("path_cache_" . md5(UrlUtil::getrelativepath()));
                if (is_numeric($_expiretime) && $_expiretime > 0) {
                    $this->Expire($_expiretime);
                }
                $_viewcachedata = $this->GetCache("cont_view_cache_" . $_controler . $_action . $_domian);
                if ($_viewcachedata != null && $_viewcachedata != false) {
                    echo $_viewcachedata;
                } else {
                    $_cinstance = Core::LoadClass("App\\{$_domian}\\Controler\\Controler_" . $_controler);
                    if ($_cinstance) {
                        $responseformat = "html";
                        $responsecharset = "UTF-8";
                        $expire = 0;
                        if (isset($_cinstance->_config)) {
                            if (isset($_cinstance->_config['rate']) && is_array($_cinstance->_config['rate'])) {
                                if (count($_cinstance->_config['rate']) == 2) {
                                    $_ckey = "controlerlimit" . $_controler . $_action . $_domian;
                                    if ($_cinstance->_config['rate'][0] == "session") {
                                        $_ckey = $_ckey . SESS_ID;
                                    }
                                    if ($_cinstance->_config['rate'][0] == "ip") {
                                        $_ckey = $_ckey . IP;
                                    }
                                    $_lastvisittime = BoCache::Cache($_ckey);
                                    if ($_lastvisittime != "" &&  microtime(true) - floatval($_lastvisittime)< 60 / $_cinstance->_config['rate'][1]) {
                                        $_errmsg = BoConfig::Config("Sys", "webapp", "System")['visit_limit'];
                                        if (IS_AJAX_REQUEST) {
                                            static $_config_arr = [];
                                            if (file_exists(APP_ROOT.DS."App".DS . DOMIAN_VALUE . DS . "Config" . DS . "Config.php")) {
                                                include APP_ROOT.DS."App".DS . DOMIAN_VALUE . DS . "Config" . DS . "Config.php";
                                            }
                                            if (isset($_config_arr['App']['limit_err_array'])) {
                                                echo json_encode($_config_arr['App']['limit_err_array']);
                                            } else {
                                                echo json_encode([
                                                    'err' => true,
                                                    data => $_errmsg
                                                ]);
                                            }
                                        } else {
                                            BoRes::View("error", "System", "v1", [
                                                'but_msg' => $_errmsg
                                            ]);
                                        }
                                        die();
                                    }
                                    BoCache::Cache($_ckey, microtime(true), 60);
                                }
                            }
                            if (isset($_cinstance->_config['allowip']) && is_array($_cinstance->_config['allowip'])) {
                                if (! in_array(IP, $_cinstance->_config['allowip'])) {
                                    throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['ip_disallow']);
                                }
                            }
                            if (isset($_cinstance->_config['runmode'])) {
                                if (! in_array(strtolower(PHP_SAPI), $_cinstance->_config['runmode'])) {
                                    throw new CoreException(PHP_SAPI . BoConfig::Config("Sys", "webapp", "System")['run_mode_disallow']);
                                }
                            }
                            if (isset($_cinstance->_config['auth']) && is_array($_cinstance->_config['auth']) && count($_cinstance->_config['auth']) == 2) {
                                $_sesskey = SESS_ID . "sess_page_App\\{$_domian}\\Controler\\Controler_" . $_controler;
                                if ($_cinstance->_config['auth']) {
                                    if (BoCache::Cache($_sesskey) != "ok") {
                                        if (IS_AJAX_REQUEST) {
                                            if (BoReq::PostVal("username") == $_cinstance->_config['auth'][0] && BoReq::PostVal("password") == $_cinstance->_config['auth'][1]) {
                                                BoCache::Cache($_sesskey, "ok", 1800);
                                                die("ok");
                                            } else {
                                                die(BoConfig::Config("Sys", "webapp", "System")['user_pwd_wrong']);
                                            }
                                        }
                                        BoRes::View("login", "System", "v1", [
                                            'refer' => URL
                                        ]);
                                        die();
                                    }
                                    // throw new CoreException(PHP_SAPI . Bfw::Config("Sys", "webapp", "System")['run_mode_disallow']);
                                }
                            }
                            if (isset($_cinstance->_config['allowdevice']) && is_array($_cinstance->_config['allowdevice'])) {
                                $_device = $this->isMobile() ? "mobile" : "pc";
                                if (! in_array($_device, $_cinstance->_config['allowdevice'])) {
                                    throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['device_disallow']);
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
                            if (isset($_cinstance->_config['responsecharset'])) {
                                 $responsecharset = $_cinstance->_config['responsecharset'];
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
                                    header("Content-type: text/xml;charset={$responsecharset}");
                                    echo ArrayUtil::Toxml($ret);
                                    break;
                                case "json":
                                    header("Content-type: text/json;charset={$responsecharset}");
                                    echo json_encode($ret);
                                    break;
                                default:
                                    header("Content-type: {$responseformat};charset={$responsecharset}");
                                    // echo $ret;
                                    break;
                            }
                        } else {
                            throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['action_not_found'] . $_action);
                        }
                    }
                }
            }
            // $_pointfile=APP_ROOT.DS."App".DS.$_domian.DS."Points".DS."Points_".$_controler.".php";
            if (file_exists($_pointfile)) {
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
            }
        } else {
            throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['con_act_format_wrong']);
        }
    }
}
?>