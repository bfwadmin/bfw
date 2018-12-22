<?php
namespace Lib\Lock;
use Lib\Exception\LockException;
use Lib\BoDebug;
class LockMemcache implements BoLockInterface
{

    private static $_instance = null;
    // 后期可以使用magent进行热备份负责均衡

    private $_timeout = 0;

    private $mem;

    private $_name;

    function __construct($_name, $_timeout = LOCK_TIMEOUT)
    {
        $this->_name = $_name;
        $this->_timeout = $_timeout;
        $this->mem = new \Memcache();
        BoDebug::Info("memlock connect " . LOCK_MEM_IP);
        if (LOCK_MEM_PCONNECT) {
            if (! $this->mem->pconnect(LOCK_MEM_IP, LOCK_MEM_PORT, LOCK_MEM_TIMEOUT)) {
                throw new LockException("memmemlock connect fail");
            }
        } else {
            if (! $this->mem->connect(LOCK_MEM_IP, LOCK_MEM_PORT, LOCK_MEM_TIMEOU)) {
                throw new LockException("memlock connect fail");
            }
        }
    }

    static function getInstance($_name, $_timeout=LOCK_TIMEOUT)
    {
        if (self::$_instance == null) {
            self::$_instance = new LockMemcache($_name, $_timeout);
        }
        return self::$_instance;
    }

    function lock()
    {
        $is_lock = $this->mem->add($this->_name,time()+$this->_timeout,0,  $this->_timeout+1);
        // 不能获取锁
        if (! $is_lock) {
            // 判断锁是否过期
            $lock_time = $this->mem->get($this->_name);
            // 锁已过期，删除锁，重新获取
            if (time() > $lock_time) {
                $this->unlock();
                $is_lock = $this->mem->add($this->_name,time()+$this->_timeout,0,  $this->_timeout+1);
            }
        }
        BoDebug::Info("memlock lock  " . $this->_name . " " . $is_lock ? "yes" : "fail");
        return $is_lock ? true : false;
    }

    function unlock()
    {
        BoDebug::Info("memlock del lock " . $this->_name);
        return $this->mem->delete($this->_name);
    }

    function __destruct()
    {
        $this->mem->close();
    }
}

?>