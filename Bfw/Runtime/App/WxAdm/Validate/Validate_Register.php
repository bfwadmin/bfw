<?php
namespace App\[[DOM]]\Validate;

use Lib\BoValidate;
use App\[[DOM]]\Client\Client_Sms;
use App\[[DOM]]\Client\Client_Member;

/**
 *
 * @author Herry
 *         注册验证
 */
class Validate_Register extends BoValidate {
	public $_validate_array = array (
			array (
					"mobile",
					"require",
					"手机号必填" 
			),
			array (
					"verifycode",
					"require",
					"验证码必填" 
			),
			array (
					"userpwd",
					"require",
					"密码必填" 
			),
			array (
					"userpwd",
					"len",
					6,
					12,
					"密码至少6到12位之间" 
			),
			array (
					"kindid",
					"require",
					"类别必选" 
			),
			array (
					"verifycode",
					"checkVerify" 
			),
			array (
					"compname",
					"checkComp" 
			) 
	);
	function checkVerify($n) {
		if (! Client_Sms::getInstance ()->Verify ( $n, $this->_input_array ['mobile'] )) {
		/*	return array (
					"err" => true,
					"data" => "手机验证码不正确" 
			);*/
		}
	}
	function checkComp($n) {
		if ($this->_input_array ['kindid'] == Client_Member::KIND_COMP && trim ( $this->_input_array ['compname'] ) == "") {
			return array (
					"err" => true,
					"data" => "公司名称必填" 
			);
		}
	}
	function checkCopwd($n) {
		if ($this->_input_array ['userpwd'] != $this->_input_array ['reuserpwd']) {
			return array (
					"err" => true,
					"data" => "两次密码输入正确" 
			);
		}
	}
}
?>