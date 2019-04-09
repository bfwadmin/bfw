<?php

namespace App\[[DOM]]\Client;
use Lib\BoClient;

/**
 *
 * @author Herry
 *         会员调用类
 */
class Client_Member extends BoClient {
	const THIRD_QQ = 1;
	// 第三方qq登录
	const THIRD_WEIXIN = 2;
	// 第三方微信登录
	const THIRD_SINA = 3;
	// 第三方新浪登录
	const SMS_LOGIN = 1;
	// 短信登录
	const USER_PWD_LOGIN = 2;
	// 账号密码登录
	const QQ_LOGIN = 3;
	// qq登录
	const WEIXIN_LOGIN = 4;
	// 微信登录
	const SINA_LOGIN = 5;
	// 新浪登录
	const KIND_NORMAL = 1;
	// 普通用户
	const KIND_COMP = 2;
	// 艺术家
	const KIND_GALLERY = 5;
	// 画廊
	const DATA_STRUCT_WRONG = 0;
	// 数据结构错误
	const USERNAME_EXSIT = 1;
	// 用户名已存在
	const USERNAME_NEED = 2;
	// 用户名必填
	const PASSWORD_NEED = 3;
	// 密码必填
	const USER_PWD_NOTMATCH = 4;
	// 账号与密码不匹配
	const USERNAME_NOT_EXSIT = 5;
	// 用户名不存在
	const DATA_NULL = 6;
	// 数据错误
	const OLD_PWD_NEED = 7;
	// 旧密码必填
	const NEW_PWD_NEED = 8;
	// 新密码必填
	const OLD_PWD_WRONG = 10;
	// 旧密码不正确
	const PHONE_REGED = 9;
	// 手机号已经注册过
	
	// protected $_service_remote = false;
	// http://passport.88art.com/application/index.php
	// http://localhost/boframeworkserver/index.php
	private static $_instance;
	protected $_serv_url = array (
			array (
					// "url" => "http://mupload.88art.com/index.php",
					"url" => SERVICE_HTTP_URL,
					"lang" => "php",
					"key" => "123",
					"dom" => "Cms",
					"weight" => 20 
			) 
	);
	
	/**
	 * 获取单例
	 *
	 * @return
	 *
	 *
	 */
	public static function getInstance() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	
	/**
	 * 新密码设置
	 *
	 * @param string $_uid
	 *        	用户id
	 * @param string $newp
	 *        	新密码
	 */
	function NewPwd($_uid, $_newp) {
		return $this->___NewPwd ( $_uid, $_newp );
	}
	
	/**
	 * 登录授权功能
	 * 登录验证账户和密码
	 *
	 * @param array $data
	 *        	参数[username,userpwd] 用户名 密码
	 * @return retmsg
	 */
	function Auth($data) {
		return $this->___Auth ( $data );
	}
	
	/**
	 * 注册
	 *
	 * @param array $regdata
	 *        	注册信息数组
	 *        	注册数据
	 * @return retmsg
	 */
	function Reg($kindid, $regdata) {
		return $this->___Reg ( $kindid, $regdata );
	}
	
	/**
	 * 修改密码
	 *
	 * @param number $uid
	 *        	用户uid
	 * @param string $oldp
	 *        	旧密码
	 * @param string $newp
	 *        	新密码
	 * @return retmsg
	 */
	function ChangePwd($uid, $oldp, $newp) {
		return $this->___ChangePwd ( $uid, $oldp, $newp );
	}
	
	/**
	 *
	 * @param number $_uid
	 *        	用户id
	 * @param string $_openid
	 *        	第三方opendi
	 * @param number $_thirdname
	 *        	第三方标识
	 * @param string $_userimg
	 *        	第三方的图片url
	 */
	function BindThirdLogin($_uid, $_openid, $_thirdname, $_userimg) {
		return $this->___BindThirdLogin ( $_uid, $_openid, $_thirdname, $_userimg );
	}
	
	/**
	 * 获取余额
	 *
	 * @param number $_uid
	 *        	用户uid
	 * @return retmsg
	 */
	function GetMoney($uid) {
		return $this->___GetMoney ( $uid );
	}
	
	/**
	 * 根据第三方id获取用户id
	 *
	 * @param string $_openid
	 *        	第三方openid
	 * @param int $_thirdname
	 *        	第三方登录提供方id（详见const里）
	 * @return retmsg
	 */
	function GetUidByOpenid($_openid, $_thirdname) {
		return $this->___GetUidByOpenid ( $_openid, $_thirdname );
	}
	
	/**
	 * 修改个人信息
	 *
	 * @param array $_data
	 *        	个人信息数组
	 * @param number $_uid
	 *        	用户uid
	 * @return retmsg
	 */
	function ChangeData($_data, $_uid) {
		return $this->___ChangeData ( $_data, $_uid );
	}
	/**
	 * @param unknown $_kindid 用户类别
	 * @param unknown $_data 用户数据
	 */
	function UpdateData($_kindid, $_data) {
		return $this->___UpdateData ( $_kindid, $_data );
	}
	
	/**
	 * @param unknown $_kindid //类别
	 * @param unknown $_uid //用户id
	 */
	function GetMyData($_kindid, $_uid) {
		return $this->___GetMyData($_kindid, $_uid);
	}
}