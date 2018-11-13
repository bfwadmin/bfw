<?php
namespace App\Hbapi\Points;

use Lib\Bfw;
use Lib\BoPoints;

class Points_Base extends BoPoints
{

    private $_no_validate_array;

    private $_group_power;
    // 不需要验证的模块
    /*
     * private $_group_power = array(
     * "Person_ListData_Cms" => array(
     * 1,
     * 2
     * )
     * );
     */
    protected function checkPower()
    {
        $_userid = $this->Session(USER_ID);
        if ($_userid == "") {
            $this->ActionFor("User", "Wxlogin", "ref=" . Bfw::UrlCode(HTTP_REFERER));
            return false;
        }
        return true;
    }

    function __construct()
    {
         $this->_no_validate_array = Bfw::Config("Validate", "pass");
    }
    // 模块独属
    
    /**
     * action前运行
     *
     * @throws Exception
     */
    function Before()
    {
//     	if(!Bfw::IsWexin()){
//     		$this->Error("请在微信端打开此页面");
//     		return false;
//     	}
//         $_nowcad = CONTROL_VALUE . "_" . ACTION_VALUE . "_" . DOMIAN_VALUE;
//         if (in_array($_nowcad, $this->_no_validate_array)) {
//             return true;
//         }else{
//         	$_userid = $this->Session(USER_ID);
//         	if ($_userid == "") {
//         		$this->ActionFor("User", "WxLogin", "ref=" . Bfw::UrlCode(URL));
//         		return false;
//         	}
//         	return true;
//         }
       
        	return true;
    }

    /**
     * action后运行
     */
    function After()
    {}
}

?>