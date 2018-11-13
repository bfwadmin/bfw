<?php
namespace Lib\Lock;

class LockFiles implements BoLockInterface
{

    public function __construct($name, $path = CACHE_DIR)
    {
        $this->path = $path . DS . "lock_" . md5($name);
        $this->name = $name;
    }

    public static function getInstance($name, $path = CACHE_DIR)
    {
        if (self::$_instance == null) {
            self::$_instance = new LockFiles($name, $path);
        }
        return self::$_instance;
    }

    public function lock()
    {}

    public function unlock()
    {}
}

?>