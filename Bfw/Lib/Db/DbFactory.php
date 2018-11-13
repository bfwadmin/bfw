<?php

namespace Lib\Db;

use Lib\Core;

class DbFactory {
	private static $_instance = array ();
	public static function GetInstance($_connarray = null) {
		if (is_null ( $_connarray )) {
			if (! isset ( self::$_instance ['default'] ) || is_null ( self::$_instance ['default'] )) {
				self::$_instance ['default'] = Core::LoadClass ( "Lib\\Db\\" . DB_TYPE );
			}
			return self::$_instance ['default'];
		} else {
			if (isset ( $_connarray ['dbconnstr'] )) {
				$key = md5 ( $_connarray ['dbconnstr'] );
				if (! isset ( self::$_instance [$key] ) || is_null ( self::$_instance [$key] )) {
					if (isset ( $_connarray ['dbtype'] )) {
						self::$_instance [$key] = Core::LoadClass ( "Lib\\Db\\" . $_connarray ['dbtype'], $_connarray );
					} else {
						self::$_instance [$key] = Core::LoadClass ( "Lib\\Db\\" . DB_TYPE, $_connarray );
					}
				}
				return self::$_instance [$key];
			} else {
				throw new \Exception ( "config wrong" );
			}
		}
	}
}

?>