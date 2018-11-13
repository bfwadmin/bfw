<?php

namespace App\Client\Xdweb;

use App\Lib\BoClient;

/**
 *
 * @author Herry
 *         用户调用类
 */
class Client_User extends BoClient {
	CONST DATA_NULL = 1;
	CONST USER_ZHULI = 3;
	const USER_NORMAL = 2;
	CONST USER_MENDIAN = 4;
	CONST REPEAT = 5;
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
	 *
	 */
	public static function getInstance() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	


	/**
	 * 获取用户角色信息
	 * @param unknown $_uid
	 */
	function GetType($_uid) {
		return $this->___GetType($_uid);
	}
	
	/**
	 * 门店认真
	 * @param unknown $_data
	 */
	function AddMd($_data){
		return $this->___AddMd($_data);
	}

	
	
}