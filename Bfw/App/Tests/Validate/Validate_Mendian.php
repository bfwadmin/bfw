<?php

namespace App\Validate\Xdweb;

use App\Lib\BoValidate;
use App\Lib\Bfw;

/**
 *
 * @author Herry
 *         人物
 */
class Validate_Mendian extends BoValidate {
	public $_validate_array = array (
			array (
					"liense_img",
					"require",
					"营业执照必须上传" 
			),
	
			array (
					"master_phone",
					"require",
					"店长手机号必填" 
			) 
	);
}
?>

