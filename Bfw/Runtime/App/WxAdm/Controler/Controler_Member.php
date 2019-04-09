<?php
namespace App\[[DOM]]\Controler;

use Lib\Bfw;
use Lib\BoControler;
use App\[[DOM]]\Client\Client_Member;

/**
 *
 * @author Herry
 *         会员中心
 */
class Controler_Member extends BoControler
{

    /**
     * 找回密码
     */
    function FindPwd()
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_formdata = $this->FormArray(array(
                "mobile",
                "userpwd",
                "verifycode",
                "reuserpwd"
            ), true, "FindPwd");
            if ($_formdata['err']) {
                return $this->Error($_formdata['data']);
            }
            $_userdata = Client_Member::getInstance()->Field("id")
                ->Where("mobile=?", [
                $_formdata['data']['mobile']
            ])
                ->Select(false);
            if ($_userdata['err'] || empty($_userdata['data']['data'])) {
                return $this->Error("数据错误");
            }
            $_changedata = Client_Member::getInstance()->NewPwd($_userdata['data']['data'][0]['id'], $_formdata['data']['userpwd']);
            if ($_changedata['err']) {
                return $this->Error(Bfw::SelectVal($_changedata['data'], [
                    Client_Member::NEW_PWD_NEED => "新密码请填写",
                    Client_Member::DATA_NULL => "数据不能为空"
                ]));
            }
            return $this->Alert("重置成功", array(
                array(
                    "用户中心",
                    Bfw::ACLINK("Member", "Login"),
                    "_self"
                )
            ));
        }
        $this->Display();
    }

    /**
     * 修改密码
     *
     * @param number $uid            
     */
    function ResetPwd($oldpwd, $newpwd)
    {
        $this->OutCharset("utf-8");
        $uid = $this->Session(USER_ID);
        if ($this->IsPost()) {
            $_changedata = Client_Member::getInstance()->ChangePwd($uid, $oldpwd, $newpwd);
            if ($_changedata['err']) {
                $this->Error(Bfw::SelectVal($_changedata['data'], [
                    Client_Member::OLD_PWD_NEED => "旧密码必填",
                    Client_Member::NEW_PWD_NEED => "新密码请填写",
                    Client_Member::OLD_PWD_WRONG => "旧的密码不匹配"
                ]));
                return;
            }
            return $this->Success("密码修改成功");
        }
        $this->Display();
    }

    /**
     * 会员中心
     */
    function Center()
    {
        $this->OutCharset("utf-8");
        $_kindid = $this->Session(ROLE_ID);
        $_uid = $this->Session(USER_ID);
        return $this->Display();
    }

    /**
     * 退出登录
     */
    function Logout()
    {
        $this->Session(USER_ID, null);
        $this->Session(ROLE_ID, null);
        $this->ActionFor("Member", "Login");
    }

    /**
     * 普通登录
     *
     * @return retmsg
     */
    private function normalLogin()
    {
        $_formdata = $this->FormArray(array(
            "username",
            "userpwd"
        ), true, "Login");
        
        if ($_formdata['err']) {
            return $_formdata;
        }
        $_formdata['data']['ip'] = IP;
        $_auth_data = Client_Member::getInstance()->Auth($_formdata['data']);
        if ($_auth_data['err']) {
            return $_auth_data;
        }
        return Bfw::RetMsg(false, [
            'id' => $_auth_data['data']['id'],
            "groupid" => $_auth_data['data']['groupid']
        ]);
    }

    /**
     * 短信登录
     *
     * @return retmsg
     */
    private function smsLogin()
    {
        $_formdata = $this->FormArray(array(
            "mobile",
            "verifycode"
        ), true, "SmsLogin");
        
        if ($_formdata['err']) {
            return $_formdata;
        }
        $_memberdata = Client_Member::getInstance()->Where("mobile=?", [
            $_formdata['data']['mobile']
        ])
            ->Field("id,kindid")
            ->PageSize(2)
            ->Select(false);
        if ($_memberdata['err']) {
            return $_memberdata;
        }
        if (count($_memberdata['data']['data']) == 0) {
            return Bfw::RetMsg(true, Client_Member::DATA_NULL);
        }
        return Bfw::RetMsg(false, [
            'id' => $_memberdata['data']['data'][0]['id'],
            'kindid' => $_memberdata['data']['data'][0]['kindid']
        ]);
    }

    /**
     * 登录
     *
     * @param string $ref
     *            来源
     *            
     */
    function Login($ref = "", $method = Client_Member::USER_PWD_LOGIN)
    {
        $this->OutCharset("utf-8");
        if ($this->IsPost()) {
            $_ret = Bfw::RetMsg(true, "para wrong");
            // 短信登录
            if ($method == Client_Member::SMS_LOGIN) {
                // $_ret = $this->smsLogin();
            } elseif (Client_Member::USER_PWD_LOGIN) {
                $_ret = $this->normalLogin();
            } elseif (Client_Member::QQ_LOGIN) {}
            if ($_ret['err']) {
                return $this->Error(Bfw::SelectVal($_ret['data'], [
                    Client_Member::DATA_NULL => "不存在",
                    Client_Member::DATA_STRUCT_WRONG => "数据结构错误",
                    Client_Member::USER_PWD_NOTMATCH => "用户名密码不匹配",
                    Client_Member::USERNAME_NOT_EXSIT => "该用户不存在"
                ]));
            }
            $this->AfterAuth($_ret['data'], $ref);
        } else {
            return $this->Display();
        }
    }

    /**
     * 登录验证后处理
     *
     * @param array $_ret            
     * @param string $ref            
     */
    private function AfterAuth($_ret, $ref)
    {
        $this->Session(USER_ID, $_ret['id']);
        $this->Session(ROLE_ID, $_ret['groupid']);

        return $this->ReDirect($ref != "" ? bfw::UrlCode($ref, true) : Bfw::ACLINK("Member", "Center"));
    }
}

?>