<?php

namespace App\Xdweb\Widget;

use Lib\BoWidget;
use Lib\Bfw;
use App\Xdweb\Client\Client_User;

/**
 *
 * @author Herry
 *         用户菜单
 */
class Widget_Menu extends BoWidget {
	
	public function __construct(&$_data = null) {
		parent::__construct ( $_data );
	}
	public function Render() {
		// $_uid = Bfw::Session(USER_ID);
		$_userinfo = Bfw::Session ( USER_ADDINFO );
		if (isset ( $_userinfo ['utype'] ) && $_userinfo ['utype'] == Client_User::USER_ZHULI) {
			$this->RenderIt ( "ZhuliMenu" );
		} else {
			$this->RenderIt ( "NormalMenu" );
		}
		// $this->AddData("test", 123);
		// $this->AddData($_data);
	}
}

?>