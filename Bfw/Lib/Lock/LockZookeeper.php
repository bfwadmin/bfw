<?php
namespace Lib\Lock;

use Lib\Exception\LockException;
use Lib\BoDebug;

class LockRedis implements BoLockInterface
{

    private static $_instance = null;

    private $_timeout = 0;

    private $mem;

    private $_parent_path = "/lock";

    private $_name;

    private $_acl = array(
        array(
            'perms' => \Zookeeper::PERM_ALL,
            'scheme' => 'world',
            'id' => 'anyone'
        )
    );

    function __construct($_name, $_timeout = LOCK_TIMEOUT)
    {
        $this->_name = $_name;
        $this->_timeout = $_timeout;
        $this->mem = new \Zookeeper(LOCK_ZK_IP . ":" . LOCK_ZK_PORT);
        if (! $this->mem->exists($this->_parent_path))
            $this->me->create($this->_parent_path, "parent", $this->_acl);
        BoDebug::Info("zklock connect " . LOCK_ZK_IP);
    }

    static function getInstance($_name, $_timeout = LOCK_TIMEOUT)
    {
        if (self::$_instance == null) {
            self::$_instance = new LockZookeeper($_name, $_timeout);
        }
        return self::$_instance;
    }

    function lock()
    {
        $is_lock = $this->mem->create($this->_parent_path . "/" . $this->_name, "child", $this->_acl);
        // 不能获取锁
        if (! $is_lock) {
            // 判断锁是否过期
        }
        BoDebug::Info("zklock lock  " . $this->_name . " " . $is_lock ? "yes" : "fail");
        return $is_lock ? true : false;
    }

    function unlock()
    {
        BoDebug::Info("zklock del lock " . $this->_name);
        return $this->mem->delete($this->_parent_path . "/" . $this->_nam);
    }

    function __destruct()
    {
        $this->mem->close();
    }
}

?>