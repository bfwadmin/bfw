<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;

class CacheRedis implements BoCacheInterface
{

    private static $_instance = null;

    private $_server_ip = "127.0.0.1";

    private $_server_port = 6379;

    private $_pconnect = false;

    private $_timeout = 5;

    private $mem;

    private $_key = "";

    function __construct()
    {
        $this->mem = new \Redis();
        
        if ($this->_pconnect) {
            if (! $this->mem->pconnect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new CacheException("redis connect fail");
            }
        } else {
            if (! $this->mem->connect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new CacheException("redis connect fail");
            }
        }
        if ($this->_key != "") {
            $this->mem->auth($this->_key);
        }
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new CacheRedis();
        }
        return self::$_instance;
    }

    function setkey($key, $val, $exprie)
    {
        if ($this->mem->set($key, serialize($val))) {
            return $this->mem->expire($key, $exprie);
        }
        return false;
    }

    function getkey($key)
    {
        $_val = $this->mem->get($key);
        return $_val ? unserialize($_val) : null;
    }

    function del($key)
    {
        return $this->mem->delete($key);
    }

    function __destruct()
    {
        $this->mem->close();
    }
    
    // put your code here
}

?>
