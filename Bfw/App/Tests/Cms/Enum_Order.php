<?php
namespace App\Enum\Cms;

class Enum_Order
{

    const DOWNED = 0;

    const PAYED = 1;

    const CANCELED = 2;

    const EXPRESS_SHIPED = 3;

    const SHIP_CONFIRM = 4;

    const REFUND_APPLYED = 5;

    const REFUND_REJECTED = 6;

    const REFUND_SUCCESS = 7;

    const CLOSED = 8;

    public static function ToArray()
    {
        return [
            self::DOWNED => "待付款",
            self::PAYED => "已付款代发货",
            self::CANCELED => "已取消",
            self::EXPRESS_SHIPED => "已发货待确认",
            self::SHIP_CONFIRM => "确认收货",
            self::REFUND_APPLYED => "申请退款",
            self::REFUND_REJECTED => "退款拒绝",
            self::REFUND_SUCCESS => "退款成功",
            self::CLOSED => "订单关闭"
        ];
    }
}

?>