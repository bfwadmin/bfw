<?php
namespace Lib\Queue;
//require_once APP_ROOT . DS . "Lib" . DS . "Queue" . DS . 'BoQueue.php';
interface BoQueueInterface
{
    public function enqueue($_key,$_val);
    public function dequeue($_key);
    public function ack();
}

?>