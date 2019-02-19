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
            throw new CoreException(BoConfig::Config("Sys", "webapp","System")['class_not_found'] . $class);
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
            throw new CoreException(BoConfig::Config("Sys", "webapp","System")['class_not_found'] . $class);
        }
    }

}
?>