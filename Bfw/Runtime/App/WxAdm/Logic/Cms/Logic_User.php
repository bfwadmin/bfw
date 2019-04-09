<?php
//nclude_once '/config.php';
include_once APP_ROOT . '/Logic/'.DOMIAN_VALUE.'/config.php';
class Logic_User {
	public function Insert($inss) {
		$ret=null;
		try {
			$proxy = getProxy ( DOMIAN_VALUE, "User" );
			$ret=$proxy->ListModel();
		} catch ( Exception $ex ) {
			log_result($ex);
			//echo $ex;
		}
		return $ret;
	
	}
}