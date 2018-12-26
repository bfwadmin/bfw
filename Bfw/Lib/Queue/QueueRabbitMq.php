<?php
namespace Lib\Queue;

use Lib\Exception\QueueException;
use Lib\BoDebug;

class QueueRabbitMq implements BoQueueInterface
{

    private static $_instance = null;

    private $_conn;

    private $_channel;
    // private $_e_name = 'e_bfw'; //交换机名
    private $_k_route = 'key_1';
    // 路由key
    private $q_name = 'q_bfw';
    // 队列名
    private $_ex;

    private $_envelope;

    private $_q;

    function __construct()
    {
        $this->_conn = new \AMQPConnection([
            'host' => QUEUE_RABBIT_HOST,
            'port' => QUEUE_RABBIT_PORT,
            'login' => QUEUE_RABBIT_USER,
            'password' => QUEUE_RABBIT_PWD,
            'vhost' => QUEUE_RABBIT_VHOST
        ]);
        if (! $this->_conn) {
            throw new QueueException("can not connect broker");
        }
        $this->_channel = new \AMQPChannel($this->_conn);
        BoDebug::Info("queuerabbit connect " . QUEUE_RABBIT_HOST);
    }

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new QueueRabbitMq();
        }
        return self::$_instance;
    }

    public function enqueue($_key, $_val)
    {
        try {
            BoDebug::Info("queueredis enqueue " . $_key);
            $this->_ex = new \AMQPExchange($this->_channel);
            $this->_ex->setName($_key);
            $this->_ex->publish($_val, $this->_k_route);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function dequeue($_key)
    {
        try {
            $this->_ex = new \AMQPExchange($this->_channel);
            $this->_ex->setName($_key);
            $this->_ex->setType(AMQP_EX_TYPE_DIRECT); // direct类型
            $this->_ex->setFlags(AMQP_DURABLE); // 持久化
                                                // echo "Exchange Status:".$ex->declare()."\n";
                                                // 创建队列
            $this->_q = new \AMQPQueue($this->_channel);
            $this->_q->setName($this->_q_name);
            $this->_q->setFlags(AMQP_DURABLE); // 持久化
                                               // echo "Message Total:".$q->declare()."\n";
            $this->_q->bind($_key, $this->_k_route);
            // 绑定交换机与队列，并指定路由键
            $this->_q->consume('processMessage');
            BoDebug::Info("queuerabbit dequeue " . $_key);
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function ack()
    {
        $this->_q->ack($this->_envelope->getDeliveryTag());
    }

    function processMessage($envelope, $queue)
    {
        $this->_envelope = $envelope;
        $msg = $envelope->getBody();
        // echo $msg."\n"; //处理消息
        // $queue->ack($envelope->getDeliveryTag());
        return false;
        // 手动发送ACK应答
    }

    public function __destruct()
    {
        BoDebug::Info("queuerabbit close ");
        $this->_conn->disconnect();
    }
}

?>