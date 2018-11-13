<?php
namespace Lib\Lock;
use Lib\Exception\LockException;
class LockMemcache implements BoLockInterface
{

    private static $_instance = null;
    // 后期可以使用magent进行热备份负责均衡
    private $_server_ip = "127.0.0.1";

    private $_server_port = 11211;

    private $_pconnect = false;

    private $_timeout = LOCK_TIMEOUT;

    private $mem;

    private $_name;

    function __construct($_name, $_path = '')
    {
        $this->_name = $_name;
        $this->mem = new \Memcache();
        
        if ($this->_pconnect) {
            if (! $this->mem->pconnect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new LockException("memcache connect fail");
            }
        } else {
            if (! $this->mem->connect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new LockException("memcache connect fail");
            }
        }
    }

    static function getInstance($_name, $_path = '')
    {
        if (self::$_instance == null) {
            self::$_instance = new LockMemcache($_name, $_path);
        }
        return self::$_instance;
    }

    function lock()
    {
        return $this->mem->add($this->_name, 1, $this->_timeout);
    }

    function unlock()
    {
        return $this->mem->delete($this->_name);
    }

    function __destruct()
    {
        $this->mem->close();
    }
}

?>