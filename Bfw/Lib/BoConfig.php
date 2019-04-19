<?php
namespace Lib;

use Lib\Registry;
use Lib\Util\ArrayUtil;
use Lib\Exception\CoreException;

/**
 * @author wangbo
 * 配置
 */
class BoConfig
{

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
            $_config_path = BFW_LIB. DS . "Config" . DS . $_file . ".php";
        } else {
            $_config_path = APP_ROOT.DS."App" . DS.$_domian . DS . "Config" . DS . $_file . ".php";
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
            $_config_path = BFW_LIB . DS . 'Lib' . DS . "Config" . DS . $_file . ".php";
        } else {
            $_config_path = APP_ROOT.DS."App" . DS . $_domian . DS . "Config" . DS . $_file . ".php";
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

}

?>