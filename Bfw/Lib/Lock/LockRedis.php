<?php
namespace Lib\Lock;

use Lib\Exception\LockException;
use Lib\BoDebug;

class LockRedis implements BoLockInterface
{

    private static $_instance = null;

    private $_timeout = 0;

    private $mem;

    private $_name;

    function __construct($_name, $_timeout = LOCK_TIMEOUT)
    {
        $this->_name = $_name;
        $this->_timeout = $_timeout;
        $this->mem = new \Redis();
        BoDebug::Info("redislock connect " . LOCK_REDIS_IP);
        if (LOCK_REDIS_PCONNECT) {
            if (! $this->mem->pconnect(LOCK_REDIS_IP, LOCK_REDIS_PORT, LOCK_REDIS_TIMEOUT)) {
                throw new LockException("redislock connect fail");
            }
        } else {
            if (! $this->mem->connect(LOCK_REDIS_IP, LOCK_REDIS_PORT, LOCK_REDIS_TIMEOUT)) {
                throw new LockException("redislock connect fail");
            }
        }
        if (LOCK_REDIS_AUTHKEY != "") {
            $this->mem->auth(LOCK_REDIS_AUTHKEY);
        }
    }

    static function getInstance($_name, $_timeout=LOCK_TIMEOUT)
    {
        if (self::$_instance == null) {
            self::$_instance = new LockRedis($_name, $_timeout);
        }
        return self::$_instance;
    }

    function lock()
    {
        $is_lock = $this->mem->setnx($this->_name, time() + $this->_timeout);
        // 不能获取锁
        if (! $is_lock) {
            // 判断锁是否过期
            $lock_time = $this->mem->get($this->_name);
            // 锁已过期，删除锁，重新获取
            if (time() > $lock_time) {
                $this->unlock();
                $is_lock = $this->mem->setnx($this->_name, time() + $this->_timeout);
            }
        }
        BoDebug::Info("redislock lock  " . $this->_name . " " . $is_lock ? "yes" : "fail");
        return $is_lock ? true : false;
    }

    function unlock()
    {
        BoDebug::Info("redislock del lock " . $this->_name);
        return $this->mem->del($this->_name);
    }

    function __destruct()
    {
        $this->mem->close();
    }
}

?>