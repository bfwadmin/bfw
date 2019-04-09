<?php

namespace App\[[DOM]]\Widget;

use Lib\BoWidget;
use Lib\Bfw;
use App\[[DOM]]\Client\Client_Member;
use Lib\Util\UrlUtil;

/**
 *
 * @author Herry
 *         网站头
 */
class Widget_Header extends BoWidget {
	public function __construct(&$_data = null) {
		parent::__construct ( $_data );
	}
	public function Render() {
		$_uid = Bfw::Session ( USER_ID );
		//$_unreadmessage = Client_Message::getInstance ()->Count ( "uid=? and rtime=0", [ 
			//	$_uid 
		//] );
		//if ($_unreadmessage ['err']) {
			$this->AddData ( "unreadcount", "0" );
		//} else {
			//$this->AddData ( "unreadcount", $_unreadmessage ['data'] );
	//	}
		

		// $this->AddData('uid', $_uid);
		// $_memberdata = Client_Member::getInstance()->Cache(1000)->Single($_uid, "nickname");
		// if ($_memberdata['err'] || $_memberdata['data'] == null) {
		// $this->AddData("nickname", "");
		// }
		// $this->AddData("nickname", $_memberdata['data']['nickname']);
		// $host = str_replace("http://", "", UrlUtil::getbase());
		// if($host!="www.88art.com"){
		// return $this->RenderIt("ArtistSiteHeader");
		// }
		$this->RenderIt ( "Header" );
	}
}

?>