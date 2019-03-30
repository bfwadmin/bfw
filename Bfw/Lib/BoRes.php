<?php
namespace Lib;

use Lib\Exception\CoreException;

class BoRes
{

    /**
     * 输出头编码
     * $this->OutCharset ( "utf-8" );
     *
     * @param string $str
     *            utf-8等
     */
    public static function OutCharset($str)
    {
        header("Content-Type: text/html;charset=" . $str);
    }

    public static function Expire($_seconds = 3600)
    {
        if ($_seconds > 0) {
            $ts = gmdate("D, d M Y H:i:s", time() + $_seconds) . " GMT";
            header("Expires: $ts");
            header("Pragma: cache");
            header("Cache-Control: max-age=$_seconds");
        }
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
    
    // 显示视图
    // Core::V("视图名称", 域,控制器名称，数据)
    public static function View($viewname, $domain, $contval = CONTROL_VALUE, $_data = null)
    {
        $viewpath = APP_ROOT.DS."App" . DS . $domain . DS . "View" . DS . $contval . DS . $viewname . ".php";
        if ($domain == 'System') {
            if ("error" == $viewname) {
                $viewpath = APP_ROOT . DS . ERROR_PAGE;
            } elseif ("msgbox" == $viewname) {
                $viewpath = APP_ROOT . DS . MSGBOX_PAGE;
            } elseif ("success" == $viewname) {
                $viewpath = APP_ROOT . DS . SUCCESS_PAGE;
            } else {
                $viewpath = BFW_LIB. DS."Lib" .DS. "View" . DS . $contval . DS . $viewname . ".php";
            }
        }
        if (file_exists($viewpath)) {
            $_in_data_obj = null;
            if ($_data != null && is_array($_data)) {
                $_in_data_obj = &$_data;
            } else {
                $_in_data_obj = &Registry::getInstance()->getAll();
            }
            if (is_array($_in_data_obj))
                extract($_in_data_obj, EXTR_PREFIX_SAME, 'data');
            else
                $data = $_in_data_obj;
            include $viewpath;
        } else {
            throw new CoreException(BoConfig::Config("Sys", "webapp", "System")['view_not_found'] . $viewname);
        }
    }

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

    public static function HttpStatus($_httpcode)
    {
        switch ($_httpcode) {
            case 404:
                header("HTTP/1.0 404 Not Found");
                break;
            case 200:
                header('HTTP/1.1 200 OK');
                break;
            case 304:
                header('HTTP/1.1 304 Not Modified');
                break;
            case 403:
                header('HTTP/1.1 403 Forbidden');
                break;
            case 401:
                header('HTTP/1.1 401 Unauthorized');
                header('WWW-Authenticate: Basic realm="login"');
                break;
            case 500:
                header('HTTP/1.1 500 Internal Server Error');
                break;
        }
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
    public static function Actlink($c, $a = null, $p = null, $d = null)
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
}

?>