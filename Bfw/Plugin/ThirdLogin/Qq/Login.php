<?php
namespace App\Plugin\ThirdLogin\Qq;
use App\Plugin\ThirdLogin\BoThirdLoginInterface;
class Login implements BoThirdLoginInterface
{
    
    public static function Go(){
        session_start();
        require_once(dirname(__FILE__)."/API/comm/config.php");
        require_once(CLASS_PATH."QC.class.php");
        $qc = new \QC ();
        $qc->qq_login ();
    }
    
    public static function CallBack(){
        session_start();
        require_once(dirname(__FILE__)."/API/comm/config.php");
        require_once(CLASS_PATH."QC.class.php");
        $qc = new \QC ();
        $acs = $qc->qq_callback ();
        $openid = $qc->get_openid ();
        $qc = new \QC ( $acs, $openid );
        $userInfo = $qc->get_user_info ();
        $userInfo['openid']=$openid;
        $userInfo['userimg']=$userInfo['figureurl_qq_2'];
        
        return $userInfo;
    }

}

?>