<?php
namespace App\[[DOM]]\Client;

use Lib\BoClient;
use Lib\Bfw;

/**
 *
 * @author Herry
 *         短信调用类
 */
class Client_Sms extends BoClient
{

    const SMS_SESSION_NAME = "xisggx_dfxsdfff";

    /*
     * protected $_serv_url = array(
     * array(
     * "url" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "key" => "123",
     * "dom" => "Cms",
     * "weight" => 20
     * ),
     * array(
     * "url" => "http://localhost/boframework/server/indexservice.php",
     * "lang" => "php",
     * "key" => "123",
     * "dom" => "Cms",
     * "weight" => 20
     * )
     * );
     */
    // protected $_service_remote = false;
    protected $_serv_url = array(
        array(
            "url" => SERVICE_HTTP_URL,
            "lang" => "php",
            "key" => "123",
            "dom" => "Cms",
            "weight" => 20
        )
    );
    // http://passport.88art.com/application/index.php
    // http://localhost/boframeworkserver/index.php
    private static $_instance;

    /**
     * 获取单例
     * Client_Person
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 检测验证码是否正确
     *
     * @param string $_code
     *            验证码
     * @param string $_phone
     *            手机号
     * @return boolean
     */
    function Verify($_code, $_phone)
    {
        if (trim($_code) == "" || trim($_phone) == "") {
            return false;
        }
        $_secode = Bfw::Session(self::SMS_SESSION_NAME);
        if (! is_array($_secode) || ! isset($_secode['code']) || ! isset($_secode['mobile'])) {
            return false;
        }
        if ($_secode['code'] == $_code && $_secode['mobile'] == $_phone) {
            Bfw::Session(self::SMS_SESSION_NAME, null);
            return true;
        }
        return false;
    }

    /**
     * 发送验证码短信
     *
     * @param string $_mobile
     *            手机号
     * @param string $_code
     *            发给的验证码
     * @param string $_temp
     *            短信模板
     * @return retmsg
     */
    function SendVerifyCode($_mobile, $_code, $_temp)
    {
        if (preg_match("/^1[34578]\\d{9}$/", $_mobile)) {
            Bfw::Session(self::SMS_SESSION_NAME, [
                'code' => $_code,
                'mobile' => $_mobile
            ], 600);
            return $this->Send($_mobile, str_replace("[?]", $_code, $_temp));
        } else {
            return Bfw::RetMsg(true, "手机号码不正确");
        }
    }

    /**
     * 发送短信
     *
     * @param string $_mobile
     *            手机号
     * @param string $_cont
     *            短信内容
     * @param string $_plantime
     *            计划发送时间
     * @return retmsg
     */
    function Send($_mobile, $_cont, $_plantime = "")
    {
        return $this->___Send($_mobile, $_cont, $_plantime);
    }
}