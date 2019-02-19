<?php
namespace Lib;

use Lib\Registry;

class BoReq
{

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
            $_key_arr = BoRoute::GetParaByUrl();
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
    /**
     * 获取cookie请求变量值
     * $this->COOKIE("变量名");
     *
     * @param string $i
     *            变量名
     * @param bool $_needentity
     *            是否html实体化
     * @return
     *
     */
      public static function  GetCookie($i, $_needentity = false)
    {
        $rtn = "";
        if (isset($_COOKIE[$i])) {
            if (is_array($_COOKIE[$i])) {
                $rtn = array();
                foreach ($_COOKIE[$i] as $_temp) {
                    if ($_needentity) {
                        $rtn[] = htmlentities(trim($_temp));
                    } else {
                        $rtn[] = trim($_temp);
                    }
                }
            } else {
                if ($_needentity) {
                    $rtn = htmlentities(trim($_COOKIE[$i]));
                } else {
                    $rtn = trim($_COOKIE[$i]);
                }
            }
        }
        return $rtn;
    }
    
    /**
     * 获取get请求变量值
     * $this->GET("变量名");
     *
     * @param string $i
     *            变量名
     * @param bool $_needentity
     *            是否html实体化
     * @return
     *
     */
     public static  function GetVal($i, $_needentity = false)
    {
        $rtn = "";
        if (isset($_GET[$i])) {
            if ($_needentity) {
                $rtn = htmlentities(trim($_GET[$i]));
            } else {
                $rtn = trim($_GET[$i]);
            }
        }
        return $rtn;
    }
    
    /**
     * 获取post变量值
     * $this->POST("变量名");
     *
     * @param string $i
     *            变量名
     * @param bool $_needentity
     *            是否html实体化
     * @return
     *
     */
      public static function PostVal($i, $_needentity = false)
    {
        $rtn = "";
        if (isset($_POST[$i])) {
            if (is_array($_POST[$i])) {
                $rtn = array();
                foreach ($_POST[$i] as $_temp) {
                    if ($_needentity) {
                        $rtn[] = htmlentities(trim($_temp));
                    } else {
                        $rtn[] = trim($_temp);
                    }
                }
            } else {
                if ($_needentity) {
                    $rtn = htmlentities(trim($_POST[$i]));
                } else {
                    $rtn = trim($_POST[$i]);
                }
            }
        }
        return $rtn;
    }
    
    /**
     * 判断是否有post传输的值
     *
     * @param bool $_tokenable
     *            是否进行hash验证
     * @param bool $_tokenonced
     *            是否一次性token
     * @throws Exception
     * @return boolean
     */
      public static function IsPost($_tokenable = false, $_tokenonced = false)
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                if ($_tokenable) {
                    $_formtoken = $this->POST(FORM_TOKEN_NAME);
                    if ($_formtoken == "") {
                        throw new FormException(Bfw::Config("Sys", "form", "System")['token_empty']);
                        return false;
                    }
                    if (defined("SESS_ID")) {
                        $_cacheval = $this->GetCache("formtoken_" . SESS_ID);
                        if ($_cacheval != $_formtoken) {
                            throw new FormException(Bfw::Config("Sys", "form", "System")['token_wrong']);
                            return false;
                        }
                        if ($_tokenonced) {
                            $this->ClearCache("formtoken_" . SESS_ID);
                        }
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

?>