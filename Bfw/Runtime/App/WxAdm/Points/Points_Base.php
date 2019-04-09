<?php
namespace App\[[DOM]]\Points;

use Lib\Bfw;
use Lib\BoPoints;
use App\[[DOM]]\Client\Client_Power;

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
        $_uid = $this->Session(USER_ID);
        // die($_groupid);
        if ($_uid == "") {
            $this->ActionFor("Member", "Login", "ref=" . Bfw::UrlCode(HTTP_REFERER));
            return false;
        }
        return true;
    }

    function __construct()
    {
        $this->_no_validate_array = Bfw::Config("Validate", "pass");
        // $this->_group_power = Bfw::Config("Validate", "group");
    }
    // 模块独属
    
    /**
     * action前运行
     *
     * @throws Exception
     */
    function Before()
    {
        $_nowcad = CONTROL_VALUE . "_" . ACTION_VALUE . "_" . DOMIAN_VALUE;
        if (in_array($_nowcad, $this->_no_validate_array)) {
            return true;
        }
        // $_uid = $this->Session("uid");
        $_groupid = $this->Session(ROLE_ID);

        if ($_groupid == "") {
            $this->ActionFor("Member", "Login", "ref=" . Bfw::UrlCode(URL));
            return false;
        }
        if (defined("SESS_ID")) {
            $lastip = $this->GetCache("lastvisitedip_" . SESS_ID);
            if (is_null($lastip)) {
                $this->ActionFor("Member", "Login", "ref=" . Bfw::UrlCode(URL));
               // $this->Error(Bfw::Config("Sys", "auth")['user_access_err']);
                return false;
            }
            if ($lastip != IP) {
                $this->ActionFor("Member", "Login", "ref=" . Bfw::UrlCode(URL));
                //$this->Error(Bfw::Config("Sys", "auth")['user_access_err']);
                return false;
            }
        }
        
        // if (isset($this->_group_power[$_nowcad])) {
        $_powerdata = Client_Power::getInstance()->GetPowerByGroupId($_groupid);
        if ($_powerdata['err']) {
           $this->Error(Bfw::Config("Sys", "auth")['user_access_err']);
            return false;
        }
        if (! in_array($_nowcad,$_powerdata['data'])) {
          $this->Error(Bfw::Config("Sys", "auth")['user_no_power']);
           return false;
        }
        // }
        
        return true;
    }

    /**
     * action后运行
     */
    function After()
    {
        if (defined("SESS_ID")) {
            $this->SetCache("lastvisitedip_" . SESS_ID, IP);
        }
    }
}

?>