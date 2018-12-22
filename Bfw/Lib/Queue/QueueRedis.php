<?php
namespace Lib\Queue;

use Lib\Exception\QueueException;
use Lib\BoDebug;

class QueueRedis implements BoQueueInterface
{

    private static $_instance = null;


    private $_redis;

    function __construct()
    {
        $this->_redis = new \Redis();
        
        BoDebug::Info("queueredis connect ".QUEUE_REDIS_HOST);
        if (QUEUE_REDIS_PCONNECT) {
            if (! $this->_redis->pconnect(QUEUE_REDIS_HOST,QUEUE_REDIS_PORT, QUEUE_REDIS_TIMEOUT)) {
                throw new QueueException("redis connect fail");
            }
        } else {
            if (! $this->_redis->connect(QUEUE_REDIS_HOST, QUEUE_REDIS_PORT, QUEUE_REDIS_TIMEOUT)) {
                throw new QueueException("redis connect fail");
            }
        }
        if (QUEUE_REDIS_AUTHKEY != "") {
            $this->_redis->auth(QUEUE_REDIS_AUTHKEY);
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
            BoDebug::Info("queueredis enqueue ".$_key);
            $this->_redis->LPUSH($_key, $_val);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function dequeue($_key)
    {
        try {
            BoDebug::Info("queueredis dequeue ".$_key);
            return $this->_redis->LPOP($_key);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function __destruct()
    {
        BoDebug::Info("queueredis close ");
        $this->_redis->close();
    }
}

?>