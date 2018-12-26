<?php
namespace Lib;

class BoQueue
{

    public static function Dequeue($_topic)
    {
        $_queue = Core::LoadClass("Lib\\Queue\\" . QUEUE_HANDLER_NAME);
        if ($_queue) {
            return $_queue->dequeue($_topic);
        } else {
            return false;
        }
    }
    public static function Ack()
    {
        $_queue = Core::LoadClass("Lib\\Queue\\" . QUEUE_HANDLER_NAME);
        if ($_queue) {
            return $_queue->ack();
        } else {
            return false;
        }
    }
    public static function Enqueue($_topic, $_data)
    {
        $_queue = Core::LoadClass("Lib\\Queue\\" . QUEUE_HANDLER_NAME);
        if ($_queue) {
            return $_queue->enqueue($_topic, $_data);
        } else {
            return false;
        }
    }
}

?>