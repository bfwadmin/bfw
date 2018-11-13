<?php
namespace Lib\Cache;
use Lib\Exception\CacheException;

class CacheMemcache implements BoCacheInterface
{

    private static $_instance = null;
    // 后期可以使用magent进行热备份负责均衡
    private $_server_ip = "127.0.0.1";

    private $_server_port = 11211;

    private $_pconnect = false;

    private $_timeout = 5;
    
    private $_iscompressed=false;

    private $mem;

    function __construct()
    {
        $this->mem = new \Memcache();
        
        if ($this->_pconnect) {
            if (! $this->mem->pconnect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new CacheException("memcache connect fail");
            }
        } else {
            if (! $this->mem->connect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new CacheException("memcache connect fail");
            }
        }
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new CacheMemcache();
        }
        return self::$_instance;
    }

    function setkey($key, $val, $exprie)
    {
        //var_dump($this->mem->set($key, $val, 0, $exprie));
        return $this->mem->set($key, $val, $this->_iscompressed, $exprie);
    }

    function getkey($key)
    {
        $_val=$this->mem->get($key);
        return $_val ? $_val : null;
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
