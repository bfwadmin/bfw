<?php
namespace App\Enum\Wei;

class Enum_Artwork
{
    const WAIT_CHECK = 1;
    
    const CHECK_REJECT = 3;
    
    const CHECK_PASS = 2;
    
    const SOLD = 55;
    
    const NOT_FOR_SALE = 4;
    
    const DELETED = 99;
    
    const NOT_PUBLIC=8;
    
    
    public static function ToArray()
    {
        return [
            self::WAIT_CHECK => "待审核",
            self::CHECK_REJECT => "驳回",
            self::CHECK_PASS => "通过",
            self::SOLD => "已售罄 ",
            self::NOT_FOR_SALE => "非卖品",
            self::DELETED => "已删除",
            self::NOT_PUBLIC => "下架",
        ];
    }
}

?>