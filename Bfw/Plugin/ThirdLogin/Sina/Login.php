<?php
namespace Plugin\ThirdLogin\Sina;
use Plugin\ThirdLogin\BoThirdLoginInterface;
class Login implements BoThirdLoginInterface
{

    public static function Go()
    {
        session_start();
        require_once (dirname(__FILE__) . "/API/Qc.php");
        $qc = new \Qc();
        $qc->GotoAuth();
      
    }

    public static function CallBack()
    {
        session_start();
        require_once (dirname(__FILE__) . "/API/Qc.php");
        $qc = new \Qc();
        $userInfo = $qc->CallBackData();
        $userInfo['userimg'] = $userInfo['headimgurl'];
        return $userInfo;
    }
}

?>