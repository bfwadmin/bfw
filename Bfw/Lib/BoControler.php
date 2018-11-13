<?php
namespace Lib;

use Lib\Util\StringUtil;
use Lib\Exception\CoreException;
use Lib\Exception\FormException;

/**
 *
 * @author Herry
 *         控制器类
 */
class BoControler extends WangBo
{

    private $_cachepagetime = 0;

    private $_smarty = null;

    private $_tempeng_name = "sys";

    /**
     * 使用模板引擎，默认是系统自带，可选smarty
     *
     * @param string $tempeng            
     */
    protected function UseTemp($tempeng = "sys")
    {
        $this->_tempeng_name = $tempeng;
    }

    protected function CacheTimeGet()
    {
        return $this->_cachepagetime;
    }

    /**
     * smarty缓存时间 单位 秒
     *
     * @param number $ctime            
     */
    protected function CachePage($ctime = 0)
    {
        if ($this->_tempeng_name === "smarty") {
            $this->InitSmarty();
            $this->_smarty->caching = true;
            $this->_smarty->cache_lifetime = $ctime;
            $this->_cachepagetime = $ctime;
        } else {
            $this->_cachepagetime = $ctime;
        }
    }

    /**
     * 获取view的cache
     *
     * @param string $_view            
     * @param string $_add            
     * @throws CoreException
     * @return Ambigous <boolean, object>
     */
    protected function IsViewCachedData($_view = null, $_add = "")
    {
        if (is_null($_view)) {
            $_view = ACTION_VALUE;
        }
        if (! is_string($_add)) {
            throw new CoreException("IsViewCachedData第二个参数必须为string");
        }
        $_cache_data = $this->GetCache(md5("view_cache_" . $_view . $_add));
        if (is_null($_cache_data)) {
            return false;
        } else {
            echo $_cache_data;
            return true;
        }
    }

    /**
     * 忽略客户断开后直接执行
     */
    protected function RunToEnd()
    {
        ignore_user_abort(true);
        set_time_limit(0);
    }

    /**
     * 异步执行任务
     *
     * @param string $_contname
     *            控制器名称
     * @param string $_actname
     *            动作器名称
     * @param string $_para
     *            参数
     */
    protected function AsynTask($_contname, $_actname, $_para)
    {
        try {
            $fp = fsockopen("127.0.0.1", 90, $errno, $errstr, 1);
            if (! $fp) {
                Bfw::LogR("fsockopen err:" . $errstr($errno));
            } else {
                $out = "GET /" . Bfw::ACLINK($_contname, $_actname, $_para) . " HTTP/1.1\r\n";
                $out .= "Host: " . $_SERVER['HTTP_HOST'] . "\r\n";
                $out .= "Connection: Close\r\n\r\n";
                fwrite($fp, $out);
                
                fclose($fp);
            }
        } catch (\Exception $e) {
            Bfw::LogR($e->getMessage());
        }
    }
    //
    // $this->FormModel("对象类名","验证对象名");
    /**
     * form实例对象转化
     *
     * @param string $classv
     *            model对象
     * @param string $valiv
     *            验证对象
     * @return model实例
     */
    protected function FormModel($classv = CONTROL_VALUE, $valiv = CONTROL_VALUE)
    {
        $instance = Core::LoadClass("Model_" . $classv, "Model/" . DOMIAN_VALUE);
        $class_var = Core::ClassFields("Model_" . $classv);
        foreach ($_POST as $keys => $values) {
            if (in_array(strtolower($keys), array_change_key_case($class_var, CASE_LOWER))) {
                $instance->$keys = $values;
            }
        }
        foreach ($_GET as $keys => $values) {
            if (in_array(strtolower($keys), array_change_key_case($class_var, CASE_LOWER))) {
                $instance->$keys = $values;
            }
        }
        if ($valiv != null) {
            Core::ValidateModel($instance, $valiv, DOMIAN_VALUE);
        }
        return $instance;
    }

    /**
     * 过滤数组包装
     * $this->FormArray("需要验证数组","是否过滤html","验证器名称");
     *
     * @param array $_filterarr
     *            form中需要的数据组成数组
     * @param bool $_nohtml
     *            是否清除html
     * @param string $valiv
     *            验证类名称
     * @return array
     */
    protected function FormArray($_filterarr, $_nohtml = false, $valiv = null)
    {
        $_paradata = array();
        $_ret = array();
        if (ROUTETYPE == 2) {
            $_paradata = Bfw::GetParaByUrl();
            $_paradata = array_merge($_POST, $_GET, $_paradata);
        } else {
            $_paradata = array_merge($_POST, $_GET);
        }
        
        foreach ($_paradata as $keys => $values) {
            if (in_array(strtolower($keys), array_change_key_case($_filterarr, CASE_LOWER))) {
                if ($_nohtml) {
                    if (is_array($values)) {
                        $newarr = array();
                        foreach ($values as $_v) {
                            $newarr[] = StringUtil::ClearHtml(($_v));
                        }
                        $_ret[$keys] = $newarr;
                    } else {
                        $_ret[$keys] = StringUtil::ClearHtml(trim($values));
                    }
                } else {
                    $_ret[$keys] = ($values);
                }
            }
        }
        if ($valiv != null) {
            $_vret = Core::ValidateArray($_ret, $valiv, DOMIAN_VALUE);
            if ($_vret['err']) {
                return $_vret;
            }
        }
        return array(
            "err" => false,
            "data" => $_ret
        );
    }

    /**
     * 过滤数据
     *
     * @param string $data            
     * @return mixed
     */
    protected function FilterBadWords($data)
    {
        $badwordreplace = "*";
        $pregarr = Array(
            "/<([a-zA-Z]+)[^>]*>/",
            "/javascript/si",
            "/vbscript/si",
            "/on([a-z]+)\s*=/si",
            "/<(\/?script.*?)>/si",
            "/<(script.*?)>(.*?)<(\/script.*?)>/si"
        );
        foreach ($pregarr as $p) {
            $data = preg_replace($p, $badwordreplace, $data);
        }
        return $data;
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
    protected function COOKIE($i, $_needentity = false)
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
    protected function GET($i, $_needentity = false)
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
    protected function POST($i, $_needentity = false)
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
    protected function IsPost($_tokenable = false, $_tokenonced = false)
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

    protected function InitSmarty()
    {
        require_once APP_ROOT . DS . 'Lib' . DS . 'Smarty' . DS . 'Smarty.class.php';
        if (is_null($this->_smarty)) {
            $this->_smarty = new \Smarty();
            $this->_smarty->setCompileDir(TEMPLATE_COMPILE_DIR);
            $this->_smarty->setCacheDir(CACHE_DIR);
            $this->_smarty->setConfigDir(SMARTY_CONFIG_DIR);
            $this->_smarty->registerPlugin("function", "aclink", "Bfw::ACLINKForS");
        }
    }

    /**
     * 模板赋值变量
     * $this->Assign("变量名","值");
     *
     * @param string $key            
     * @param object $val            
     */
    protected function Assign($key, $val)
    {
        // $this->tpl->assign ( $key, $val );
        if ($this->_tempeng_name === "smarty") {
            $this->InitSmarty();
            $this->_smarty->assign($key, $val, true);
        } else {
            Core::S($key, $val);
        }
    }

    /**
     * 模板渲染
     * $this->Display("模板文件名称");
     *
     * @param string $viewname
     *            模板文件
     * @param string $_adddata
     *            模板字符串
     * @param string $_returndata
     *            是否返回数据不输出
     * @return string
     */
    protected function Display($viewname = null, $_adddata = "", $_returndata = false)
    {
        if ($this->_tempeng_name === "smarty") {
            if ($viewname == null) {
                $viewname = ACTION_VALUE;
            }
            if (DEVICE_AUTO_TEMPLATE) {
                $this->isMobile() ? $viewname = $viewname . "_m" : "";
            }
            $this->InitSmarty();
            $this->_smarty->display(APP_ROOT .DS.'App'. DS . DOMIAN_VALUE .DS . "View" .  DS . CONTROL_VALUE . DS . $viewname . ".tpl");
        } else {
            if ($viewname == null) {
                $viewname = ACTION_VALUE;
            }
            if (DEVICE_AUTO_TEMPLATE) {
                $this->isMobile() ? $viewname = $viewname . "_m" : "";
            }
            if ($_adddata != "") {
                @file_put_contents(APP_ROOT . DS .'App'.DS . DOMIAN_VALUE.DS. "View"  . DS . CONTROL_VALUE .DS. $viewname . ".php", $_adddata);
            }
            if ($this->_cachepagetime > 0) {
                ob_start();
                Core::V($viewname, DOMIAN_VALUE, CONTROL_VALUE);
                Core::Cache("cont_view_cache_" . CONTROL_VALUE . ACTION_VALUE . DOMIAN_VALUE, ob_get_contents(), $this->_cachepagetime);
            } else {
                if ($_returndata) {
                    ob_start();
                    Core::V($viewname, DOMIAN_VALUE, CONTROL_VALUE);
                    return ob_get_clean();
                }
                Core::V($viewname, DOMIAN_VALUE, CONTROL_VALUE);
            }
        }
    }
    
    // public function Display
    // 数据层接口
    // $this->Dao("数据对象");
    // public function Dao($_o = CONTROL_VALUE) {
    // return Core::LoadClass ( 'Client_' . $_o, "Client/" . DOMIAN_VALUE );
    // }
    // 逻辑层接口
    // $this->Logic("对象");
    protected function Logic($_o)
    {
        return Core::LoadClass('Logic_' . $_o, "Logic/" . DOMIAN_VALUE);
    }

    public function __get($name)
    {
        $_d = &Registry::getInstance()->get($name);
        return $_d;
    }
}
?>