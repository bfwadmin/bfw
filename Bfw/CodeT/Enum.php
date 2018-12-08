<?php

namespace App\DOM\Enum;

/**
 * @author bfw
 * 枚举数据类型
 */
class Enum_CONTNAME {
    //以下是样例，可修改
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