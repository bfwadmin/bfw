<?php
namespace Lib;

use Lib\Util\StringUtil;
use Lib\Util\CryptionUtil;

/**
 * @author wangbo
 * 辅助类
 */
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
               // return StringUtil::base64_de_url($_url);
                return str_replace("|____|", "&", str_replace("|___|", "=", str_replace("|__|", "?", str_replace("|_|", "/", $_url))));
            }
            //return StringUtil::base64_en_url($_url);
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


    public static function RetMsg($_err, $_data)
    {
        return array(
            "err" => $_err,
            "data" => $_data
        );
    }
}

?>