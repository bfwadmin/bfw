<?php
if (version_compare ( "5.4", PHP_VERSION, ">" )) {
	die ( "PHP 5.4 or greater is required!!!" );
}
$indexdir = dirname ( __FILE__ );
define ( "APP_BASE", $indexdir );
define ( "DS", DIRECTORY_SEPARATOR );
define ( "APP_ROOT", $indexdir . DS . "Bfw" );
require_once APP_ROOT . DS . 'Lib' . DS . 'Init.php';
?>