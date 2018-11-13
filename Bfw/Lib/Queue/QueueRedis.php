<?php
namespace Lib\Queue;

use Lib\Exception\QueueException;

class QueueRedis implements BoQueueInterface
{

    private static $_instance = null;

    private $_server_ip = "127.0.0.1";

    private $_server_port = 6379;

    private $_pconnect = false;

    private $_timeout = 5;

    private $_redis;

    function __construct()
    {
        $this->_redis = new \Redis();
        
        if ($this->_pconnect) {
            if (! $this->_redis->pconnect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new QueueException("redis connect fail");
            }
        } else {
            if (! $this->_redis->connect($this->_server_ip, $this->_server_port, $this->_timeout)) {
                throw new QueueException("redis connect fail");
            }
        }
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new QueueRedis();
        }
        return self::$_instance;
    }

    public function enqueue($_key, $_val)
    {
        try {
            $this->_redis->LPUSH($_key, $_val);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function dequeue($_key)
    {
        try {
            return $this->_redis->LPOP($_key);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->_redis->close();
    }
}

?>