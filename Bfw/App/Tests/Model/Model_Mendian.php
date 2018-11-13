<?php

namespace App\Model\Xdweb;

use App\Lib\BoModel;
use App\Lib\Bfw;

class Model_Mendian extends BoModel {
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
	
	
	/*
	 * protected $_connarray = array( "dbtype" => "DbMysql", "dbconnstr" => "mysql:host=127.0.0.1;dbname=88art", "dbport" => 3306, "dbuser" => "root", "dbpwd" => "" );
	 */
}

?>