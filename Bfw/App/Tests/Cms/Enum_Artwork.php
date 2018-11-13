<?php
namespace App\Enum\Cms;

class Enum_Artwork
{
    const WAIT_CHECK = 0;
    
    const CHECK_REJECT = 3;
    
    const CHECK_PASS = 1;
    
    const SOLD = 2;
    
    const NOT_FOR_SALE = 4;
    
    const DELETED = 5;
    
    
    public static function ToArray()
    {
        return [
            self::WAIT_CHECK => "待审核",
            self::CHECK_REJECT => "驳回",
            self::CHECK_PASS => "通过",
            self::SOLD => "已买",
            self::NOT_FOR_SALE => "非卖品",
            self::DELETED => "已删除"
        ];
    }
}

?>