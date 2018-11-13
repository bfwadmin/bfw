<?php

namespace App\Service\Xdweb;

use App\Lib\Bfw;
use App\Lib\BoService;
use App\Lib\Exception\DbException;
use App\Model\Xdweb\Model_Baobei;
use App\Model\Xdweb\Model_Zhuli;
use App\Model\Xdweb\Model_Mendian;

/**
 *
 * @author Herry
 *         用户
 */
class Service_User extends BoService {
	CONST DATA_NULL = 1;
	CONST USER_ZHULI = 3;
	const USER_NORMAL = 2;
	CONST USER_MENDIAN = 4;
	CONST REPEAT = 5;
	protected $_model = "User";
	private static $_instance;
	
	/**
	 * 获取单例
	 *
	 * @return Service_Group
	 */
	public static function getInstance() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	function getkey() {
		return "123";
	}
	function AddMd($_data) {
		if (isset ( $_data ['open_id'] )) {
			$_countdata = Model_Mendian::getInstance ()->Count ( "open_id=?", [ 
					$_data ['open_id'] 
			] );
			if ($_countdata ['err']) {
				return $_countdata;
			}
			if ($_countdata ['data'] > 0) {
				return Bfw::RetMsg ( true, self::REPEAT );
			}
		}
		return Model_Mendian::getInstance ()->Insert ( $_data );
	}
	function GetType($_uid) {
		$_data = Model_Zhuli::getInstance ()->Single ( "*", $_uid );
		
		if ($_data ['err'] || $_data ['data'] == null) {
			return Bfw::RetMsg ( false, array (
					"utype" => self::USER_NORMAL 
			) );
		}
		return Bfw::RetMsg ( false, array (
				"utype" => self::USER_ZHULI,
				"loupan_id" => $_data ['data'] ['loupan_id'] 
		) );
	}
}
?>