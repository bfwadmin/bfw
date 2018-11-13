<?php

namespace App\Hbapi\Validate;

use Lib\BoValidate;
use Lib\Bfw;

/**
 *
 * @author Herry
 *         人物
 */
class Validate_Hongbao extends BoValidate {
	public $_validate_array = array (
			array (
					"reg_name",
					"require",
					"报备人名称必须填写" 
			),
			array (
					"reg_phone",
					"require",
					"报备人电话必填" 
			),
			array (
					"cus_name",
					"require",
					"客户姓名必填" 
			),
			array (
					"cus_phone",
					"require",
					"客户电话必填，我们会严格保密" 
			),
			array (
					"loupan_id",
					"require",
					"请选择项目信息" 
			),
			array (
					"look_time",
					"require",
					"预约看房时间必选" 
			),
			array (
					'id',
					'addId' 
			) 
			,array("look_time","changeFormat")
	);
	function changeFormat(&$a){		
		$a=str_replace("日","",str_replace("月", "-", str_replace("年", "-", $a)));
		$a=str_replace("时",":",$a);
		$a=str_replace("分","",$a);
		$a=strtotime($a);
		if($a-UNIX_TIME<24*3600){
			return array (
					"err" => true,
					"data" => "预约时间错误，请至少提前一天报备"
			);
		}
	}
	function addId(&$a) {
		$a = md5 ( uniqid ( mt_rand (), true ) );
	}
	function checkExsit($a) {
		$_checkdata = Client_Apply::getInstance ()->Count ( "bidno=? and itemid=?", [ 
				$a,
				$this->_input_array ['itemid'] 
		] );
		if ($_checkdata ['err']) {
			return array (
					"err" => true,
					"data" => $_checkdata ['data'] 
			);
		}
		if ($_checkdata ['data'] > 0) {
			return array (
					"err" => true,
					"data" => "牌号已经存在，请更换" 
			);
		}
	}
}
?>

