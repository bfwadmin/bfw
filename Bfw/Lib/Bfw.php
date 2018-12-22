<?php
namespace Lib;

use Lib\Registry;
use Lib\Util\StringUtil;
use Lib\Util\CryptionUtil;
use Lib\Util\UrlUtil;
use Lib\Util\ArrayUtil;
use Lib\Exception\CoreException;

class Bfw
{

    /**
     * 加载小部件
     *
     * @param string $_name            
     * @param object $_data            
     * @param string $_domian            
     */
    public static function Widget($_name, $_data = null, $_domian = DOMIAN_VALUE)
    {
        $_name = "App\\" . $_domian . "\\Widget\\Widget_" . $_name;
        return Core::LoadClass($_name, $_data)->Render();
    }

    /**
     * if判断
     *
     * @param object $_v            
     * @param object $_true_v            
     * @param object $_false_v            
     */
    public static function IIF($_v, $_true_v, $_false_v)
    {
        if ($_v) {
            return $_true_v;
        } else {
            return $_false_v;
        }
    }

    /**
     * 修改配置文件
     *
     * @param unknown $_file            
     * @param unknown $_key            
     * @param unknown $_infoarr            
     * @param string $_domian            
     */
    public static function ConfigSet($_file, $_key, $_infoarr, $_domian = DOMIAN_VALUE)
    {
        $_configfile = APP_ROOT . DS . 'App' . DS . $_domian . DS . "Config" . DS . $_file . ".php";
        $_str = ArrayUtil::printArrayAsPhpCode($_infoarr, true);
        file_put_contents($_configfile, "<?php \r\n\$_config_arr['{$_file}']['{$_key}']=" . $_str . ";");
    }

    public static function ConfigGet($_file, $_key, $_domian = DOMIAN_VALUE)
    {
        static $_config_arr = array();
        if (isset($_config_arr[$_key])) {
            return $_config_arr[$_key];
        }
        $_config_path = "";
        if ($_domian == "System") {
            $_config_path = APP_ROOT . DS . 'Lib' . DS . "Config" . DS . $_file . ".php";
        } else {
            $_config_path = APP_ROOT . DS . 'App' . DS . $_domian . DS . "Config" . DS . $_file . ".php";
        }
        if (file_exists($_config_path)) {
            include $_config_path;
            if (isset($_config_arr[$_key])) {
                return $_config_arr[$_key];
            } else {
                return null;
            }
        } else {
            throw new CoreException("配置文件不存在" . $_config_path);
        }
    }

    /**
     * 获取自定义数组
     *
     * @param string $_file            
     * @param string $_key            
     * @return Ambigous <>|NULL
     */
    public static function Config($_file, $_key, $_domian = DOMIAN_VALUE)
    {
        static $_config_arr = array();
        if (isset($_config_arr[$_file][$_key])) {
            return $_config_arr[$_file][$_key];
        }
        $_config_path = "";
        if ($_domian == "System") {
            $_config_path = APP_ROOT . DS . 'Lib' . DS . "Config" . DS . $_file . ".php";
        } else {
            $_config_path = APP_ROOT . DS . 'App' . DS . $_domian . DS . "Config" . DS . $_file . ".php";
        }
        if (file_exists($_config_path)) {
            include $_config_path;
            if (isset($_config_arr[$_file][$_key])) {
                return $_config_arr[$_file][$_key];
            } else {
                return null;
            }
        } else {
            throw new CoreException("配置文件不存在" . $_config_path);
        }
    }

    /**
     * 获取全局变量值
     *
     * @param string $key
     *            key键名
     * @param bool $htmlentity是否实体化            
     * @return object
     */
    public static function GlobalGet($key, $htmlentity = false)
    {
        if ($htmlentity) {
            $_val = Registry::getInstance()->get($key);
            if (is_string($_val)) {
                return htmlentities($_val, ENT_QUOTES, "utf-8");
            }
            return $_val;
        }
        $_d = &Registry::getInstance()->get($key);
        return $_d;
    }

    /**
     * 设置全局变量值
     *
     * @param string $key            
     * @param object $val            
     */
    public static function GlobalSet($key, $val)
    {
        Registry::getInstance()->set($key, $val);
    }

    /**
     * 获取GET POST COOKIE传值,数组或文件不识别，仅为string
     *
     * @param string $key            
     * @return string
     */
    public static function GPC($key)
    {
        if (ROUTETYPE == 1) {
            if (isset($_GET[$key]) && is_string($_GET[$key])) {
                return htmlspecialchars($_GET[$key]);
            }
        }
        if (ROUTETYPE == 2) {
            $_key_arr = self::GetParaByUrl();
            if (isset($_key_arr[$key]) && is_string($_key_arr[$key])) {
                return htmlspecialchars(Bfw::UrlCode($_key_arr[$key], true));
            }
        }
        
        if (isset($_GET[$key]) && is_string($_GET[$key])) {
            return htmlspecialchars($_GET[$key]);
        }
        if (isset($_POST[$key])) {
            if (is_string($_POST[$key])) {
                return htmlspecialchars($_POST[$key]);
            }
            if (is_array($_POST[$key])) {
                return $_POST[$key];
            }
        }
        if (isset($_COOKIE[$key]) && is_string($_COOKIE[$key])) {
            return htmlspecialchars($_COOKIE[$key]);
        }
        
        return "";
    }

    public static function IsWexin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    public static function IsMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|symbian|tablet|up\\.browser|up\\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public static function GetParaByUrl()
    {
        self::import("Lib.Registry");
        $_cachedata = &Registry::getInstance()->get("sys_path_array_cache_data");
        if (! is_null($_cachedata)) {
            return $_cachedata;
        }
        $_key_arr = array();
        $_url = UrlUtil::getrelativepath();
        $_map_data = Bfw::Config("Controler", "url", "System");
        $_url_map_data = [];
        if (self::IsWexin()) {
            foreach ($_map_data as $key => $item) {
                if (isset($item['device']) && $item['device'] != "weixin") {} else {
                    $_url_map_data[$key] = $item;
                }
            }
        } elseif (self::IsMobile()) {
            foreach ($_map_data as $key => $item) {
                if (isset($item['device']) && $item['device'] != "mobile") {} else {
                    $_url_map_data[$key] = $item;
                }
            }
        } else {
            foreach ($_map_data as $key => $item) {
                if (isset($item['device']) && $item['device'] != "pc") {} else {
                    $_url_map_data[$key] = $item;
                }
            }
        }
        foreach ($_url_map_data as $_iurl => $_furl) {
            if (preg_match($_iurl, $_url, $match)) {
                if (isset($_furl["method"])) {
                    if (is_array($_furl["method"])) {
                        if (! in_array(HTTP_METHOD, $_furl["method"])) {
                            break;
                        }
                    } else {
                        if (HTTP_METHOD != $_furl["method"]) {
                            break;
                        }
                    }
                }
                if (isset($_furl['expire'])) {
                    Registry::getInstance()->set("path_cache_" . md5(UrlUtil::getrelativepath()), $_furl['expire']);
                }
                if (isset($_furl["url"])) {
                    $_url = $_furl["url"];
                    
                    for ($i = 1; $i < count($match); $i ++) {
                        $_url = str_replace("[{$i}]", $match[$i], $_url);
                    }
                    if (strstr($_url, "http://") || strstr($_url, "https://")) {
                        header("location:" . $_url);
                        die();
                    }
                    break;
                }
            }
        }
        $arr = explode("/", ltrim($_url, "/"));
        $_key_arr[DOMIAN_NAME] = isset($arr[0]) ? $arr[0] : "";
        $_key_arr[CONTROL_NAME] = isset($arr[1]) ? $arr[1] : "";
        $_key_arr[ACTION_NAME] = isset($arr[2]) ? $arr[2] : "";
        if (count($arr) > 3) {
            for ($i = 3; $i < count($arr); $i ++) {
                if (preg_match("/^[a-zA-Z]{1,25}$/", $arr[$i])) {
                    $_key_arr[$arr[$i]] = isset($arr[$i + 1]) ? $arr[$i + 1] : "";
                    $i ++;
                }
            }
        }
        Registry::getInstance()->set("sys_path_array_cache_data", $_key_arr);
        return $_key_arr;
    }

    /**
     * 过滤重复数据
     *
     * @param string $str            
     * @return mixed
     */
    public static function FilterRedirectPara($str)
    {
        $str = preg_replace("/" . ACTION_NAME . "=[a-zA-Z]+&/", "", $str);
        $str = preg_replace("/" . CONTROL_NAME . "=[a-zA-Z]+&/", "", $str);
        $str = preg_replace("/" . DOMIAN_NAME . "=[a-zA-Z]+/", "", $str);
        $str = preg_replace("/&&/", "&", $str);
        return $str;
        // preg_replace("/".ACTION_NAME."=[a-zA-Z]+/","",preg_replace("/".CONTROL_NAME."=[a-zA-Z]+/",
        // "", $_SERVER ['REDIRECT_QUERY_STRING']))
    }

    /**
     * 导入类
     *
     * @param string $path            
     * @throws Exception
     */
    public static function import($path)
    {
        $classpath = APP_ROOT . DS . str_replace("\\", DS, str_replace(".", DS, $path)) . ".php";
        // echo $classpath;
        if (file_exists($classpath)) {
            include_once $classpath;
            if (WEB_DEBUG) {
                global $_import_info_array;
                $_import_info_array[] = "import file:" . $classpath . ",mem:" . (memory_get_usage() / 1024) . "KB";
            }
        } else {
            throw new \Exception(Bfw::Config("Sys", "webapp", 'System')['class_not_found'] . $path);
        }
    }

    public static function ACLINKForS($args)
    {
        return self::ACLINK($args['c'], $args['a'], $args['p']);
    }

    /**
     * url解析 routetype为 2时可用
     *
     * @param string $_url            
     * @param bool $_decode            
     */
    public static function UrlCode($_url, $_decode = false)
    {
        if (ROUTETYPE == 1 || ROUTETYPE == 0) {
            if ($_decode) {
                return urldecode($_url);
            }
            return urlencode($_url);
        }
        if (ROUTETYPE == 2) {
            if ($_decode) {
                return str_replace("|____|", "&", str_replace("|___|", "=", str_replace("|__|", "?", str_replace("|_|", "/", $_url))));
            }
            return str_replace("&", "|____|", str_replace('=', "|___|", str_replace("?", "|__|", str_replace("/", "|_|", $_url))));
        }
        if ($_decode) {
            return urldecode($_url);
        }
        return urlencode($_url);
    }

    /**
     * 控制器动作器链接
     *
     * @param string $c
     *            控制器
     * @param string $a
     *            动作器
     * @param string $p
     *            参数
     * @param string $d
     *            域
     * @return string
     */
    public static function ACLINK($c, $a = null, $p = null, $d = null)
    {
        if ($a == null) {
            $a = "Index";
        }
        if ($p == null) {
            $p = "";
        }
        if ($d == null) {
            $d = DOMIAN_VALUE;
        }
        if (ROUTETYPE == 1) {
            return APPSELF . "?" . CONTROL_NAME . '=' . $c . '&' . ACTION_NAME . '=' . $a . '&' . DOMIAN_NAME . '=' . $d . '&' . $p;
        }
        if (ROUTETYPE == 0) {
            return APPSELF . "?" . ROUTER_NAME . '=' . $d . '|' . $c . "|" . $a . '&' . $p;
        }
        if (ROUTETYPE == 2) {
            $_url = str_replace('index.php', "", $_SERVER["SCRIPT_NAME"]);
            if (defined("HOST_HIDE_DOM") && $d == HOST_HIDE_DOM) {
                $_url = $_url . $c . '/' . $a;
            } else {
                $_url = $_url . $d . '/' . $c . '/' . $a;
            }
            if ($p != "") {
                $_url = $_url . '/' . str_replace("&", "/", str_replace("=", "/", $p));
            }
            if (PAGE_SUFFIX != "") {
                $_url = $_url . PAGE_SUFFIX;
            }
            return $_url;
        }
        if (ROUTETYPE == 3) {
            if ($p != null && $p != "") {
                return $c . "-" . $a . ".html?" . $p;
            } else {
                return $c . "-" . $a . ".html";
            }
        }
        return APPSELF . "?" . CONTROL_NAME . '=' . $c . '&' . ACTION_NAME . '=' . $a . '&' . DOMIAN_NAME . '=' . DOMIAN_VALUE . '&' . $p;
    }

    public static function SLogR($status, $log, $sid)
    {
        if (strtolower(PHP_SAPI) != 'cli') {
            // $_http_pro = "http://";
            // if (! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
            // $_http_pro = "https://";
            // }
            // $_url_par = parse_url(SERVICE_REG_CENTER_URL);
            // $_ret = HttpUtil::AsynPost($_url_par['host'], 80, $_url_par['path'] . "?" . $_url_par['query'] . "act=AddLog", array(
            // "msg" => $log,
            // "status" => $status,
            // "clienturl" => $_http_pro . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
            // "serviceid" => $sid
            // ), 3);
            // if ($_ret['err']) {
            // self::LogR($_ret['msg'], "logservicefailed", 3);
            // }
        }
    }

    /**
     * 日志记录
     *
     * @param string $word            
     * @param string $tag
     *            标签
     * @param string $level
     *            级别，0 一般 1 有风险 2 较严重，3非常严重 4 危机到系统安全
     */
    public static function LogR($word, $tag = "defaut", $level = 0)
    {
        if (! is_string($word)) {
            $word = var_export($word, true);
        }
        if (defined("LOG_HANDLER_NAME")) {
            $_classname = "Lib\\Log\\" . LOG_HANDLER_NAME;
            $_classname::getInstance()->Log($word, $tag, $level);
        } else {
            echo $word;
        }
    }

    public static function is_serialized($data)
    {
        return (is_string($data) && preg_match("#^((N;)|((a|O|s):[0-9]+:.*[;}])|((b|i|d):[0-9.E-]+;))$#um", $data));
    }

    /**
     * 解密url
     *
     * @param string $str            
     * @return string
     */
    public static function BoDecodeUrl($str)
    {
        self::import("Plugin.Cryption");
        $cipher = new CryptionUtil(SECRIT_KEY);
        return $cipher->decrypt($str);
    }

    /**
     * 加密url
     *
     * @param string $str            
     * @return string
     */
    public static function BoUrlStr($str)
    {
        self::import("Plugin.Cryption");
        $cipher = new CryptionUtil(SECRIT_KEY);
        return $cipher->encrypt($str);
    }



    public static function DestorySess($_key = null)
    {
        if (strtolower(PHP_SAPI) != "cli") {
            // if (defined(SESS_ID) && SESS_ID == "") {
            session_start();
            // }
            if (is_null($_key)) {
                session_unset();
                session_destroy();
            } else {
                unset($_SESSION[$_key]);
            }
            session_write_close();
        }
        return true;
    }
    // session操作 key val 失效时间 秒
    public static function Session($_key, $_val = "", $_expire = 0)
    {
        if (strtolower(PHP_SAPI) != "cli") {
            session_start();
            $ret = null;
            if (empty($_val) || is_null($_val)) {
                if (is_null($_val)) {
                    unset($_SESSION[$_key]);
                    $ret = true;
                } else {
                    if (isset($_SESSION[$_key])) {
                        $_tmp = $_SESSION[$_key];
                        if (is_array($_tmp) && isset($_tmp['expire'])) {
                            if (time() > $_tmp['expire']) {
                                $ret = "";
                            } else {
                                $ret = isset($_tmp['value']) ? $_tmp['value'] : '';
                            }
                        } else {
                            $ret = $_tmp;
                        }
                    } else {
                        $ret = "";
                    }
                    // Bfw::LogR("read,".$_key."=".$ret."</br>");
                }
            } else {
                if ($_expire > 0) {
                    $_SESSION[$_key] = [
                        'value' => $_val,
                        'expire' => time() + $_expire
                    ];
                } else {
                    $_SESSION[$_key] = $_val;
                }
                // Bfw::LogR("write.".$_key."=".$_val."</br>") ;
                
                $ret = true;
            }
            session_write_close();
            
            return $ret;
        }
        return "";
    }

    /**
     * 防止恶意攻击
     *
     * @param int $intvaltime            
     * @return boolean
     */
    public static function AntiRobotAttack($intvaltime)
    {
        $_sesskey = CONTROL_VALUE . '_' . ACTION_VALUE . 'lastvisitedtime';
        if (self::Session($_sesskey) != null) {
            if (time() - self::Session($_sesskey) < $intvaltime) {
                self::Session($_sesskey, time());
                return false;
            } else {
                self::Session($_sesskey, time());
                return true;
            }
        } else {
            self::Session($_sesskey, time());
            return true;
        }
    }

    /**
     * 返回choosedata中key
     *
     * @param array $_choosedata            
     * @param string $_val            
     * @return string
     */
    public static function SelectVal($_val, $_choosedata)
    {
        // var_dump($_choosedata);
        if (! is_array($_choosedata)) {
            return $_val;
        }
        if (array_key_exists($_val, $_choosedata)) {
            return $_choosedata[$_val];
        } else {
            return $_val;
        }
    }

    /**
     * 诊断输出，只有在WEB_DEBUG为true的情况下可以看到
     *
     * @param string $str            
     */
    public static function Debug($str)
    {
        if (defined("WEB_DEBUG")) {
            if (WEB_DEBUG) {
                $_info = &Registry::getInstance()->get("debug_info");
                $_info[] = array(
                    microtime(true),
                    $str . ",mem:" . (memory_get_usage() / 1024) . "KB"
                );
                Registry::getInstance()->set("debug_info", $_info);
            }
        }
    }

    /**
     * 根据权重计算
     *
     * @param array $weight
     *            权重 例如array('a'=>10,'b'=>20,'c'=>50)
     * @return string key 键名
     */
    public static function Routeroll($weight = array())
    {
        $roll = rand(1, array_sum($weight));
        $_tmpW = 0;
        $rollnum = 0;
        foreach ($weight as $k => $v) {
            $min = $_tmpW;
            $_tmpW += $v;
            $max = $_tmpW;
            if ($roll > $min && $roll <= $max) {
                $rollnum = $k;
                break;
            }
        }
        return $rollnum;
    }

    public static function DebugHtml($_import_info_arr, $_debug_info_arr, $_spendtime, $_log_toserver, $_totalmem, $_file = "debug")
    {
        ob_start();
        Core::V($_file, "System", "v1", [
            'import_info' => $_import_info_arr,
            "debug_info" => $_debug_info_arr,
            "spendtime" => $_spendtime,
            "islogserver" => $_log_toserver,
            "totalmem" => $_totalmem
        ]);
        $_cont = ob_get_clean();
        ob_start();
        return $_cont;
    }

    public static function DebugEcho($spendtime)
    {
        $_import_info = &Registry::getInstance()->get("import_info");
        $_debug_info = &Registry::getInstance()->get("debug_info");
        if (RUN_MODE == "S") {
            return array(
                "import_file" => $_import_info,
                "debug_info" => $_debug_info,
                "spend_time" => $spendtime,
                "log_toserver" => LOG_TO_SERVER,
                "totalmem" => memory_get_usage()
            );
        }
        echo self::DebugHtml($_import_info, $_debug_info, $spendtime, LOG_TO_SERVER, memory_get_usage());
    }

    /**
     * 返回token值
     *
     * @return string
     */
    public static function GetTokenVal()
    {
        $_token = StringUtil::getRandChar(16);
        if (defined("SESS_ID")) {
            Core::Cache("formtoken_" . SESS_ID, $_token, FORM_TOKEN_EXPIRE_TIME);
        }
        return $_token;
    }

    /**
     * 获取验证数组 发送给浏览器用的
     *
     * @param string $_vname            
     * @param string $_domian            
     */
    public static function GetValidateArray($_vname, $_domian = DOMIAN_VALUE)
    {
        return Core::LoadClass("App\\" . $_domian . "\\Validate\\Validate_" . $_vname)->_validate_array;
    }

    /**
     *
     * @param string $id            
     * @param number $_expiretime
     *            过期时间
     * @param string $_dependency
     *            缓存依赖 仅限cache类型的依赖
     * @return boolean
     */
    public static function CacheBegin($id, $_expiretime = 60, $_dependency = "")
    {
        $_wantcache = false;
        $_cacheexpire = false;
        if ($_dependency != "") {
            $_dependcacheval = Core::Cache(CACHE_DEPENDCY_PRE . $_dependency);
            if ($_dependcacheval == null) {
                Bfw::LogR($_dependency . Bfw::Config("Sys", "cache", 'System')['dependcy_not_found']);
            } else {
                if (Core::Cache(CACHE_DEPENDCY_PRE . $_dependency . "_pre") != $_dependcacheval) {
                    Core::Cache(CACHE_DEPENDCY_PRE . $_dependency . "_pre", $_dependcacheval, 0);
                    $_cacheexpire = true;
                }
            }
            // ? : "";
        } else {
            $_cache_data = Core::Cache("page_cache_" . $id);
            if ($_cache_data != null) {
                echo $_cache_data;
                return false;
            } else {
                $_wantcache = true;
            }
        }
        
        if ($_wantcache || $_cacheexpire) {
            self::GlobalSet("page_cache_data", [
                "id" => $id,
                "expire" => $_expiretime,
                "dependency" => $_dependency
            ]);
            ob_start();
            return true;
        }
    }

    /**
     * cache 结束
     */
    public static function CacheEnd()
    {
        $_cache_data = self::GlobalGet("page_cache_data");
        if ($_cache_data != null) {
            Core::Cache("page_cache_" . $_cache_data['id'], ob_get_contents(), $_cache_data['expire']);
            // echo "11111".Core::Cache("page_cache_" . $_cache_data['id'])."1111111";
        }
    }

    public static function RetMsg($_err, $_data)
    {
        return array(
            "err" => $_err,
            "data" => $_data
        );
    }
}

?>