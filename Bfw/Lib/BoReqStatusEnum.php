<?php
namespace Lib;

/**
 * @author wangbo
 * 服务调用错误状态
 */
class BoReqStatusEnum
{

    const BFW_S_SUC = 1;

    const BFW_S_FAIL = 2;

    const BFW_S_PRO = 3;

    const BFW_S_ERR = 4;

    public static function ToArray()
    {
        return [
            self::BFW_S_SUC => "调用成功",
            self::BFW_S_FAIL => "通讯失败",
            self::BFW_S_PRO => "协议不符",
            self::BFW_S_ERR => "接口返回错误"
        ];
    }
}
?>