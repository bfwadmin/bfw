<?php

namespace App\Hbapi\Service;

use Lib\Bfw;
use Lib\BoService;
use Lib\Exception\DbException;
use App\Hbapi\Model\Model_Hongbao;

/**
 *
 * @author Herry
 *         
 */
class Service_Hongbao extends BoService {
	CONST DATA_NULL = 1;
	const NO_POWER = 2;
	CONST TIME_WRONG=3;
	protected $_model = "Hongbao";
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
	
	
	function Confirm($_indata, $_uid) {
		if (!isset($_indata['id'])) {
			return Bfw::RetMsg ( true, self::DATA_NULL );
		}
		$_data = Model_Baobei::getInstance ()->Single ( "id,loupan_id,confirm_time", $_indata['id'] );
		if ($_data ['err']) {
			return $_data;
		}
		if ($_data ['data'] == null) {
			return Bfw::RetMsg ( true, self::DATA_NULL );
		}
		if ($_data ['data'] ['confirm_time'] > 0) {
			return Bfw::RetMsg ( false, "" );
		}
		$_countdata = Model_Zhuli::getInstance ()->Count ( "loupan_id=? and open_id=?", array (
				$_data ['data'] ['loupan_id'],
				$_uid 
		) );
		
		if ($_countdata ['err']) {
			return $_countdata;
		}
		if ($_countdata ['data'] == 0) {
			return Bfw::RetMsg ( true, self::NO_POWER );
		}
		unset ( $_data ['data'] ['loupan_id'] );
		$_data ['data'] ['confirm_time'] = UNIX_TIME;
		$_data ['data'] ['lou_builder'] = isset($_indata['lou_builder'])?$_indata['lou_builder']:"";
		$_data ['data'] ['lou_zhuli'] = isset($_indata['lou_zhuli'])?$_indata['lou_zhuli']:"";
		$_data ['data'] ['lou_zhigu'] = isset($_indata['lou_zhigu'])?$_indata['lou_zhigu']:"";
		$_data ['data'] ['memo'] = isset($_indata['memo'])?$_indata['memo']:"";
		
		return Model_Baobei::getInstance ()->Update ( $_data ['data'] );
	}
	
	function Cancel($_id, $_uid) {
		$_data = Model_Baobei::getInstance ()->Single ( "id,open_id,isdeleted,look_time", $_id );
		if ($_data ['err']) {
			return $_data;
		}
		if ($_data ['data'] == null) {
			return Bfw::RetMsg ( true, self::DATA_NULL );
		}
		if($_data ['data'] ['look_time']<UNIX_TIME+24*3600){
			return Bfw::RetMsg ( true, self::TIME_WRONG );
		}
		if ($_data ['data'] ['open_id'] != $_uid) {
			return Bfw::RetMsg ( true, self::NO_POWER );
		}
		if ($_data ['data'] ['isdeleted'] == 0) {
			unset ( $_data ['data'] ['open_id'] );
			$_data ['data'] ['isdeleted'] = 1;
			Model_Baobei::getInstance ()->Update ( $_data ['data'] );
		}
		return Bfw::RetMsg ( false, "" );
	}
	
	function GetLoupan(){
		return Model_Loupan::getInstance()->Select();
	}
	function FindByUsernameOrPassword(){
		
	}
	
}
?>