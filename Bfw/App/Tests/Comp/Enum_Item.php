<?php

namespace App\Enum\Comp;

class Enum_Item {
	const WAIT_BID = 1;
	const BIDDING = 2;
	const SUCCESS = 3;
	const FAIL_NOBIDDER = 4;
	const FAIL_RESERVEPRICE = 5;
	public static function ToArray() {
		return [ 
				self::WAIT_BID => "等待竞价",
				self::BIDDING => "竞价中",
				self::SUCCESS => "成交" ,
				self::FAIL_NOBIDDER => "无人竞价流拍",
				self::FAIL_RESERVEPRICE => "未达保留价流拍"
		];
	}
}

?>