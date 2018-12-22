<?php
namespace Lib;

class WangBo
{

    /**
     * 锁定
     * 
     * @param string $_key            
     */
    protected function Lock($_key)
    {
    	
        $_lock_instance = "Lib\\lock\\" . LOCK_HANDLER_NAME;
        Bfw::import($_lock_instance);
        return $_lock_instance::getInstance($_key)->lock();
    }

    /**
     * 解除锁
     * 
     * @param string $_key            
     */
    protected function UnLock($_key)
    {
        $_lock_instance = "Lib\\lock\\" . LOCK_HANDLER_NAME;
        Bfw::import($_lock_instance);
        return $_lock_instance::getInstance($_key)->unlock();
    }

    /**
     * 设置或获取session值
     *
     * @param string $_key            
     * @param string $_val            
     * @return bool
     */
    protected function Session($_key, $_val = "", $_expire = 0)
    {
        return Bfw::Session($_key, $_val, $_expire);
    }

    /**
     * 清除缓存
     *
     * @param string $key            
     * @return bool
     */
    protected function ClearCache($key)
    {
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Bfw::import($_cache_instance);
        return $_cache_instance::getInstance()->del($key);
    }

    /**
     * 设置缓存
     *
     * @param string $key            
     * @param object $val            
     * @param number $second            
     * @return bool
     */
    protected function SetCache($key, $val, $second = 1800)
    {
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Bfw::import($_cache_instance);
        return $_cache_instance::getInstance()->setkey($key, $val, $second);
    }

    /**
     * 获取缓存
     *
     * @param string $key            
     * @return object 如果失败返回null
     */
    protected function GetCache($key)
    {
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Bfw::import($_cache_instance);
        return $_cache_instance::getInstance()->getkey($key);
    }

    /**
     * 动作转发器
     * $this->ActionFor("控制器","动作器","参数");
     *
     * @param string $c
     *            控制器
     * @param string $a
     *            动作器
     * @param string $p
     *            参数如id=1222
     */
    protected function ActionFor($c, $a = null, $p = null)
    {
        if (IS_AJAX_REQUEST) {
            $this->Json(Bfw::RetMsg(false, "redirect:" . Bfw::ACLINK($c, $a, $p)));
        } else {
            header('Location: ' . Bfw::ACLINK($c, $a, $p));
        }
    }

    /**
     * 重定向
     * $this->ReDirect("路径地址");
     *
     * @param string $url            
     */
    protected function ReDirect($url)
    {
        if (IS_AJAX_REQUEST) {
            $this->Json(Bfw::RetMsg(false, "redirect:" . $url));
        } else {
            header('Location: ' . $url);
        }
    }

    /**
     * 销毁session值
     *
     * @param string $_key            
     * @return bool
     */
    protected function DestorySess($_key = null)
    {
        return Bfw::DestorySess($_key);
    }

    /**
     * 输出头编码
     * $this->OutCharset ( "utf-8" );
     *
     * @param string $str
     *            utf-8等
     */
    protected function OutCharset($str)
    {
        header("Content-Type: text/html;charset=" . $str);
    }

    /**
     * 输出页面缓存
     *
     * @param number $_seconds            
     */
    protected function Expire($_seconds = 3600)
    {
        if ($_seconds > 0) {
            $ts = gmdate("D, d M Y H:i:s", time() + $_seconds) . " GMT";
            header("Expires: $ts");
            header("Pragma: cache");
            header("Cache-Control: max-age=$_seconds");
        }
    }

    /**
     * 输出json数组
     *
     * @param array $arr            
     */
    protected function Json($arr)
    {
        // header ( 'Content-type: text/json' );
        echo json_encode($arr);
    }

    /**
     * 执行js
     *
     * @param string $code            
     */
    protected function RunJs($code)
    {
        echo "<script>{$code};</script>";
    }

    /**
     * 模型实例化
     *
     * @param string $_cname            
     */
    protected function Model($_cname = CONTROL_VALUE)
    {
        return Core::LoadClass('App\\' . DOMIAN_VALUE . '\\Model\\Model_' . $_cname);
    }

    /**
     * dao实例化
     *
     * @param string $_cname            
     */
    protected function Dao($_cname = CONTROL_VALUE)
    {
        if (SERVICE_REMOTE) {
            return Core::LoadClass('App\\' . DOMIAN_VALUE . '\\Client\\Client_' . $_cname);
        } else {
            return Core::LoadClass('App\\' . DOMIAN_VALUE . '\\Service\\Service_' . $_cname);
        }
    }

    /**
     * 控制器实例化
     *
     * @param string $_cname            
     */
    protected function Con($_cname)
    {
        return Core::LoadClass('App\\' . DOMIAN_VALUE . '\\Controler\\Controler_' . $_cname);
    }

    /**
     * 成功提示
     * $this->Success("出现错误");
     *
     * @param string $str            
     */
    protected function Success($str)
    {
        if (strtolower(PHP_SAPI) === "cli") {
            echo $str;
            return;
        }
        if (RESPONSE_JSON || IS_AJAX_REQUEST) {
            $this->Json(Bfw::RetMsg(false, $str));
        } else {
            Core::S("but_msg", $str);
            Core::V($this->isMobile() ? "success_m" : "success", "System", "v1");
        }
    }

    /**
     * 判断是否微信内访问
     * 
     * @return boolean
     */
    protected function IsWeixin()
    {
        return Bfw::IsWexin();
    }

    /**
     * 是否移动端访问
     * 
     * @return number
     */
    protected function isMobile()
    {
        return Bfw::IsWexin();
    }

    /**
     * 提醒
     * $this->Alert("成功",array(array("返回首页","http://www.baidu.com","_blank"),array("返回搜索","http://www.baidu.com","_blank")));
     *
     * @param string $msg            
     * @param array $but            
     */
    protected function Alert($msg, $but = null)
    {
        if (strtolower(PHP_SAPI) === "cli") {
            echo $msg;
            return;
        }
        if (RESPONSE_JSON) {
            $this->Json(Bfw::RetMsg(false, $msg));
        } else {
            if (IS_AJAX_REQUEST) {
                if (is_array($but)) {
                    if (isset($but[0])) {
                        $this->Json(Bfw::RetMsg(false, "msgredirect:" . $msg . "---" . $but[0][1]));
                    } else {
                        $this->Json(Bfw::RetMsg(false, $msg));
                    }
                } else {
                    $this->Json(Bfw::RetMsg(false, $msg));
                }
            } else {
                Core::S("but_val", $but);
                Core::S("but_msg", $msg);
                Core::V($this->isMobile() ? "msgbox_m" : "msgbox", "System", "v1");
            }
        }
    }

    /**
     * 提醒
     * $this->MsgRedirect("成功","返回","http://www.baidu.com");
     *
     * @param string $msg
     *            提示信息
     * @param string $urlnane
     *            提示按钮
     * @param string $url
     *            链接地址
     */
    protected function MsgRedirect($msg, $urlnane, $url)
    {
        if (strtolower(PHP_SAPI) === "cli") {
            echo $msg;
            return;
        }
        if (RESPONSE_JSON || IS_AJAX_REQUEST) {
            $this->Json(Bfw::RetMsg(false, "msgredirect:" . $msg . "---" . $url));
        } else {
            $this->Alert($msg, array(
                array(
                    $urlnane,
                    $url,
                    "_self"
                )
            ));
            // if(is_array($but)){
            // Core::S ( "but_val", $but );
            // Core::S ( "but_msg", $msg );
            // require_once APP_ROOT . "/View/System/msgbox.php";
        }
    }

    /**
     * 出错提示
     * $this->Error("出现错误");
     *
     * @param string $str            
     */
    protected function Error($str)
    {
        if (strtolower(PHP_SAPI) === "cli") {
            echo $str;
            return;
        }
        if (RUN_MODE == "S") {
            $this->Json(array(
                "bo_err" => true,
                "bo_data" => $str
            ));
        } elseif (RESPONSE_JSON || IS_AJAX_REQUEST) {
            $this->Json(Bfw::RetMsg(true, $str));
        } else {
            
            Core::S("but_msg", $str);
            Core::V($this->isMobile() ? "error_m" : "error", "System", "v1");
        }
        // die();
    }

    /**
     * 向浏览器发送http code
     *
     * @param numbner $_httpcode            
     */
    protected function HttpStatus($_httpcode)
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
}
?>