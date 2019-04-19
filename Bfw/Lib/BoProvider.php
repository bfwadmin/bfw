<?php
namespace Lib;

use Lib\Util\FileUtil;
use Lib\Exception\CoreException;
use Lib\Util\HttpUtil;

/**
 * @author wangbo
 * 服务提供模式
 */
class BoProvider
{

    public function work($_controler, $_action, $_domian)
    {
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
        if (isset($_GET['reg'])) {
            $_serverfilelist = FileUtil::getFileListByDir(APP_ROOT . "/App/" . $_domian . "/Service/");
            $_server_array = array();
            foreach ($_serverfilelist as $_file) {
                $_service_name = str_replace(".php", "", $_file);
                Core::ImportClass("App." . $_domian . ".Service." . $_service_name);
                $r = new \reflectionclass("App\\" . $_domian . "\\Service\\" . $_service_name);
                $_cinstance = Core::LoadClass("App\\" . $_domian . "\\Service\\" . $_service_name);
                $_seckey = $_cinstance->getkey();
                foreach ($r->getmethods() as $key => $methodobj) {
                    if ($methodobj->ispublic() && ! in_array($methodobj->name, array(
                        "getInstance",
                        "getkey",
                        "__construct",
                        "__destruct",
                        "__call"
                    ))) {
                        $_server_array[] = array(
                            $_domian,
                            str_replace("Service_", "", $_service_name),
                            $methodobj->name,
                            $_seckey
                        );
                    }
                }
            }
            // $_http_pro = "http://";
            // if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            // $_http_pro = "https://";
            // }
            // $_url_par = parse_url(SERVICE_REG_CENTER_URL);
           HttpUtil::HttpPost(SERVICE_REG_CENTER_URL, array(
                "data" => serialize($_server_array),
                "notifyurl" => SERVICE_NOTIFY_URL,
                "serviceurl"=>SERVICE_HTTP_URL,
                "lang" => "php",
                "cont" => "service",
                "act" => "reg",
                "dom" => "bfw"
            ));
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
                    $_service_mode_arr = BoConfig::Config("Service", "config", "System");
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
                                    BoDebug::Info("LOCK WAIT:" . LOCK_WAIT_TIME . "s");
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
                                 BoDebug::Info("LOCK TIMEOUT:" . LOCK_TIMEOUT . "s");
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
                        return $_sretmsg(false, $ret, BoDebug::DebugEcho($total));
                    } else {
                        return $_sretmsg(false, $ret);
                    }
                }
            } else {
                throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['key_wrong']);
            }
        }
    }
}
?>