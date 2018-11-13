<?php
namespace App\Enum\Cms;

class Enum_OrderRefund
{

    const REFUND_APPLYED = 1;

    const REFUND_REJECTED = 2;

    const REFUND_SUCCESS = 3;

    const REFUND_CLOSE = 4;

    const REFUND_READY = 5;

    public static function ToArray()
    {
        return [
            self::REFUND_READY => "",
            self::REFUND_APPLYED => "申请退款",
            self::REFUND_REJECTED => "退款拒绝",
            self::REFUND_SUCCESS => "退款成功",
            self::REFUND_CLOSE => "退款申请关闭 "
        ];
    }
}

?>