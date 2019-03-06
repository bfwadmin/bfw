<?php
namespace Plugin\ThirdLogin;

interface BoThirdLoginInterface
{
    /**
     * 去第三方登录
     */
    public static function Go();
     
    /**
     * 第三方回调信息
     */
    public static function CallBack();
}

    

?>