<?php
namespace Lib;

use Lib\Exception\CoreException;
class Core
{
    
    // 获取类的变量
    // Core::ClassFields("类");
    public static function ClassFields($class)
    {
        $rtn = array();
        $class_vars = get_class_vars($class);
        foreach ($class_vars as $name => $value) {
            // if (substr ( $name, 0, 1 ) != "_") {
            $rtn[] = $name;
        }
        return $rtn;
    }
    // 设置全局变量
    // Core::S("名称", "值");
    public static function S($key, $val)
    {
        Registry::getInstance()->set($key, $val);
    }
    
    // 显示视图
    // Core::V("视图名称", 域,控制器名称，数据)
    public static function V($viewname, $domain, $contval = CONTROL_VALUE, $_data = null)
    {
        $viewpath = APP_ROOT . DS .'App' .DS .$domain .DS. "View" . DS . $contval . DS . $viewname . ".php";
        if($domain=='System'){
			$viewpath = APP_ROOT . DS .'Lib' .DS. "View" . DS . $contval . DS . $viewname . ".php";
		}
        if (file_exists($viewpath)) {
            $_in_data_obj = null;
            if ($_data != null&& is_array($_data)) {
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
            throw new CoreException(Bfw::Config("Sys", "webapp","System")['view_not_found'] . $viewname);
        }
    }
    // 获取全局变量
    // Core::G("变量名");
    public static function G($key)
    {
        $_d = &Registry::getInstance()->get($key);
        return $_d;
    }
    // 验证模型
    // Core::ValidateModel("模型","类名","域");
    public static function ValidateModel($_model, $classname, $domian)
    {
        $validate_model = self::LoadClass("Validate_" . $classname, "Validate" . DS . $domian);
        $validate_model->start($_model);
    }

    public static function ValidateArray(&$_sarray, $classname, $domian)
    {
        $validate_model = self::LoadClass("App\\{$domian}\\Validate\\Validate_" . $classname);
        $validate_model->_input_array = &$_sarray;
        return $validate_model->validateArray();
    }
    
    // 加载类
    // Core::LoadClass("类名","参数");
    public static function LoadClass($class, $para = null)
    {
        $classpath = APP_ROOT . DS . str_replace(".", DS, str_replace("\\", DS, $class)) . ".php";
        if (file_exists($classpath)) {
            include_once $classpath;
            if (is_null($para)) {
                return new $class();
            } else {
                return new $class($para);
            }
        } else {
            
            throw new CoreException(Bfw::Config("Sys", "webapp","System")['class_not_found'] . $class);
        }
    }
    
    // 导入类
    // Core::ImportClass("类名");
    public static function ImportClass($class)
    {
        $classpath = APP_ROOT . DS . str_replace(".", DS, str_replace("\\", DS, $class)) . ".php";
        if (file_exists($classpath)) {
            include_once $classpath;
            // return new $class ();
        } else {
            throw new CoreException(Bfw::Config("Sys", "webapp","System")['class_not_found'] . $class);
        }
    }

    /**
     * 缓存
     *
     * @param string $_key            
     * @param string $_val            
     * @param int $_lifetime            
     */
    public static function Cache($_key, $_val = null, $_lifetime = 180)
    {
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Bfw::import($_cache_instance);
        if (is_null($_val)) {
            return $_cache_instance::getInstance()->getkey($_key);
        } else {
            return $_cache_instance::getInstance()->setkey($_key, $_val, $_lifetime);
        }
    }

    /**
     * 删除key
     *
     * @param string $_key            
     */
    public static function DelC($_key)
    {
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Bfw::import($_cache_instance);
        
        return $_cache_instance::getInstance()->del($_key);
    }
}
?>