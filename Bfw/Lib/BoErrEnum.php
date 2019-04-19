<?php
namespace Lib;

/**
 * @author wangbo
 * 调试消息类型
 */
class BoErrEnum
{

    const BFW_INFO = 0;

    const BFW_ERROR = 3;

    const BFW_WARN = 2;

    const BFW_DEBUG = 1;

    public static function ToArray()
    {
        return [
            self::BFW_DEBUG => "调试",
            self::BFW_ERROR => "错误",
            self::BFW_INFO => "信息",
            self::BFW_WARN => "警告"
        ];
    }
}
?>