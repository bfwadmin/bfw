<?php

namespace App\Hbapi\Client;

use Lib\BoClient;

/**
 *
 * @author Herry
 *         标的调用类
 */
class Client_Hongbao extends BoClient {
	CONST DATA_NULL = 1;
	const NO_POWER = 2;
	CONST TIME_WRONG=3;
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
	 * 确认报备
	 * @param unknown $_indata
	 * @param unknown $_uid
	 */
	function Confirm($_indata, $_uid) {
		return $this->___Confirm($_indata, $_uid);
	}

	/**
	 * 撤销报备
	 * @param unknown $_id
	 * @param unknown $_uid
	 */
	function Cancel($_id, $_uid) {
		return $this->___Cancel($_id, $_uid);
	}
	
	/**
	 * 获取楼盘信息
	 */
	function GetLoupan(){
		return $this->___GetLoupan();
	}
	
	
}