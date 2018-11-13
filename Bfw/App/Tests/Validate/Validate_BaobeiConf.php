<?php

namespace App\Validate\Xdweb;

use App\Lib\BoValidate;
use App\Lib\Bfw;

/**
 *
 * @author Herry
 *         人物
 */
class Validate_BaobeiConf extends BoValidate {
	public $_validate_array = array (
			array (
					"id",
					"require",
					"唯一编号必填" 
			),
			array (
					"lou_zhuli",
					"require",
					"案场工作人员姓名必填" 
			),
			array (
					"lou_zhigu",
					"require",
					"接待置业顾问姓名必填" 
			),
			
	);
	
}
?>

