<?php

namespace Lib\Exception;
class CacheException extends BoException {
	public function __construct($message, $code = 0) {
		parent::__construct ( $message, $code );
	}
}
?>