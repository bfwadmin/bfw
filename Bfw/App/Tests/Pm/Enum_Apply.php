<?php

namespace App\Enum\Pm;

class Enum_Apply {
	const WAIT_FOR = 1;
	const GET_NO = 2;
	const CLOSE = 3;
	
	public static function ToArray() {
		return [ 
				self::WAIT_FOR => "等待处理",
				self::GET_NO => "已获取牌号",
				self::CLOSE => "关闭" 
				
		];
	}
}

?>