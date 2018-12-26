<?php
namespace Lib\Queue;

use Lib\Exception\QueueException;
use Lib\BoDebug;
use App\Libraries\Stomp\Stomp;

class QueueActiveMq implements BoQueueInterface
{

    private static $_instance = null;

    private $_parent_path = "/queue/";

    private $_ac;

    private $_frame;

    function __construct()
    {
        BoDebug::Info("queueaq connect " . QUEUE_AQ_HOST);
        $this->_ac = new Stomp('tcp://' . QUEUE_AQ_HOST . ':' . QUEUE_AQ_PORT);
        $this->_ac->connect();
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
            BoDebug::Info("queueaq enqueue " . $_key);
            $this->_ac->send($this->_parent_path . $_key, json_encode($_val));
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function dequeue($_key)
    {
        try {
            BoDebug::Info("queueaq dequeue " . $_key);
            $this->_ac->subscribe($this->_parent_path . $_key);
            if ($this->_ac->hasFrame()) {
                $this->_frame = $this->_ac->readFrame();
                if ($this->_frame != NULL) {
                    return json_decode($this->_frame->body, true);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new QueueException($e->getMessage());
        }
    }

    public function ack()
    {
        BoDebug::Info("queueaq ack ");
        $this->_ac->ack($this->_frame);
    }

    public function __destruct()
    {
        BoDebug::Info("queueaq close ");
        $this->_ac->disconnect();
    }
}

?>