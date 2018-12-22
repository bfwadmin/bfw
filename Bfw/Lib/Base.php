<?php
namespace Lib;

use Lib\Registry;
use Lib\Util\UrlUtil;

class Base
{
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
       
        $path="Lib.Registry";
        $classpath = APP_ROOT . DS . str_replace("\\", DS, str_replace(".", DS, $path)) . ".php";
        if (file_exists($classpath)) {
            include_once $classpath;
        }
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

}

?>