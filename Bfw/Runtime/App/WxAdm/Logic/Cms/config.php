<?php

function getLogicUrl($_n) {
	$LogicUrl = array ("http://localhost:89/", "http://localhost:89/" );
	//global $DaoUrl;
	return $LogicUrl[0];
}
function getProxy($domian, $classname) {
	include_once APP_ROOT . '/Hessian/HessianClient.php';
	include_once APP_ROOT . '/Model/'.$domian.'/Model_'.$classname.'.php';
	$_urlfors=getLogicUrl(0) . "?domian=" . $domian . "&servicename=" . $classname;
	//echo $_urlfors;
	return new HessianClient ( $_urlfors, array ('transport' => 'http' ) );
}
?>