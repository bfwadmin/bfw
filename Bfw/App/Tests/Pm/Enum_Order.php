<?php
namespace App\Enum\Cms;

class Enum_Order
{

    const DOWNED = 1;

    const PAYED = 2;

    const CANCELED = 3;

    const EXPRESS_SHIPED = 4;

    const SHIP_CONFIRM = 5;
    
    const CLOSED = 8;

    public static function ToArray()
    {
        return [
            self::DOWNED => "待付款",
            self::PAYED => "已付款待发货",
            self::CANCELED => "已取消",
            self::EXPRESS_SHIPED => "已发货待确认",
            self::SHIP_CONFIRM => "确认收货,交易完成",
            self::CLOSED => "订单关闭"
        ];
    }
}

?>