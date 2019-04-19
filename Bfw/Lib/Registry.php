<?php
namespace Lib;

/**
 * @author wangbo
 * 全局内存类
 */
class Registry
{

    private static $_objects = array();

    private static $_instance = null;
    // 单例
    public static function getInstance()
    {
        if (is_null(self::$_instance) || ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function set($name, $object)
    {
        self::$_objects[$name] = $object;
    }

    public function &get($name)
    {
        if (array_key_exists($name, self::$_objects)) {
            return self::$_objects[$name];
        }
        $ret = null;
        $_ret = &$ret;
        return $_ret;
        // var_dump($this->_objects);
        // return array_key_exists($name, self::$_objects) ? self::$_objects[$name] : "";
    }

    public function &getAll()
    {
        return self::$_objects;
    }
}

?>