<?php
namespace App\Enum\Comp;

class Enum_Member
{
    const WAIT_CHECK = 1;
    
    const CHECK_REJECT = 3;
    
    const CHECK_PASS = 2;
   
    
    
    public static function ToArray()
    {
        return [
            self::WAIT_CHECK => "待审核",
            self::CHECK_REJECT => "驳回",
            self::CHECK_PASS => "通过"
        ];
        
    }
}

?>