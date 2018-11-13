<?php

namespace App\Hbapi\Model;

use Lib\BoModel;
use Lib\Bfw;

class Model_Hongbao extends BoModel {
	protected $_prikey = "id";
	protected $_pre = "";
	protected $_isview = false;
	private static $_instance;
	function __construct() {
		$this->_connarray = Bfw::Config ( "Db", "localconfig" );
		parent::__construct ();
	}
	
	/**
	 * 获取单例
	 *
	 * @return Service_Logs
	 */
	public static function getInstance() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	function Single($_field, $_id)
	{
		$this->ChangeDb("wb_hongbao", "id", false, Bfw::Config("Db", "localconfig"));
		$_retdata = parent::Single($_field, $_id);
		$this->ChangeBack();
		return $_retdata;
	}
	function ListData($_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount = true)
	{
		$this->ChangeDb("wb_hongbao", "id", false, Bfw::Config("Db", "localconfig"));
		$_retdata = parent::ListData($_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount);
		$this->ChangeBack();
		return $_retdata;
	}
/* 	public function Insert($_data, $_returnid = false) {
		$_data ['id'] = md5 ( uniqid ( mt_rand (), true ) );
		parent::Insert($_data,$_returnid);
	} */
	
	/*
	 * protected $_connarray = array( "dbtype" => "DbMysql", "dbconnstr" => "mysql:host=127.0.0.1;dbname=88art", "dbport" => 3306, "dbuser" => "root", "dbpwd" => "" );
	 */
}

?>