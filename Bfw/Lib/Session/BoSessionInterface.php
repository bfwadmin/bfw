<?php
namespace Lib\Session;
interface BoSessionInterface {
	static function sess_open($sess_path, $sess_name);
	static function sess_close();
	static function sess_read($sess_id);
	static function sess_write($sess_id, $data);
	static function sess_destroy($sess_id);
	static function sess_gc($sess_maxlifetime);
}
?>