<?php

namespace Lib\Log;

use Lib\BoErrEnum;
use Lib\Exception\LogException;

class LogFiles implements BoLogInterface {
	private static $_instance = null;
	const MAXFILESIZE = 10240000; //单位k字节
	static function getInstance() {
		if (self::$_instance == null) {
			self::$_instance = new LogFiles ();
		}
		return self::$_instance;
	}
	private function getLogfilename($_slice,$_level) {
		$_logfile = LOG_DIR . DS .DOMIAN_VALUE.DS. strftime ( "%Y%m%d", time () ) . "_{$_level}_{$_slice}.log";
		if(file_exists($_logfile)){
			if (filesize ( $_logfile ) > self::MAXFILESIZE) {
				$_slice++;
				return $this->getLogfilename($_slice, $_level);
			}
		}
		return $_logfile;
	}
	public function Log($_word, $_tag, $_level = BoErrEnum::BFW_INFO) {
		try {
			$fp = @fopen ( $this->getLogfilename(0,$_level), "a" );
			if ($fp) {
				if (flock ( $fp, LOCK_EX )) {
					fwrite ( $fp, strftime ( "%Y-%m-%d %H:%M:%S", time () ) . "|T_" . $_tag . " \n" . $_word . "\n" );
					flock ( $fp, LOCK_UN );
				}
				fclose ( $fp );
			}
		} catch ( \Exception $e ) {
			throw new LogException ( $e );
		}
	}
}

?>