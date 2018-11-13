<?php

namespace App\Hbapi\Controler;

use Lib\Bfw;
use Lib\BoControler;
use Lib\Util\UrlUtil;

use App\Hbapi\Client\Client_Hongbao;
/**
 *
 * @author Herry
 *         操作
 */
class Controler_Hongbao extends BoControler {
	
	public $_config = [
			'runmode' => [
					'apache2handler',
					"cli",
					"fpm-fcgi"
			],
			'responseformat' => 'json',
			'expire' => 36,
			"allowdevice" => [
					'pc',
					'mobile'
			],
			"allowip" => ['::1']
	];
	
	/**
	 * 发红包
	 */
	public function AddData() {
		if ($this->IsPost ( true )) {
			$_formdata = $this->FormArray ( array (
					"id",
					"reg_name",
					"reg_phone",
					"cus_name",
					'cus_phone',
					'look_time',
					'loupan_id',
					"cus_sex"
			), true, "Hongbao" );
			if ($_formdata ['err']) {
				return _formdata;
			}
			$_formdata ['data'] ['reg_time'] = UNIX_TIME;
			$_formdata ['data'] ['reg_ip'] = IP;
			$_formdata ['data'] ['open_id'] = $this->Session ( USER_ID );
			return  Client_Hongbao::getInstance ()->Insert ( $_formdata ['data'] );	
		}
	}
	
	/**
	 * 红包列表
	 * @param number $page  页数      	
	 * @return array {}
	 */
	public function ListData($page = 0,$keyword="") {
		$pagesize = 10;
		$this->OutCharset ( "utf-8" );
		return Client_Hongbao::getInstance ()->Field ( "*" )->PageNum ( $page )->PageSize ( $pagesize )->Select ();	
	   

	}
	//抢红包
	public function OpenData($id=""){
		$this->OutCharset ( "utf-8" );
		return Client_Hongbao::getInstance()->OpenData($id);
	}
	/**
	 * 报备确认列表
	 *
	 * @param number $page        	
	 */
	function ListDataAdm($page = 0,$keyword="") {
		$pagesize = 10;
		$this->OutCharset ( "utf-8" );
		
		$_userinfo=$this->Session ( USER_ADDINFO );
		if($_userinfo['utype']!=Client_User::USER_ZHULI){
			return $this->Error("没权限");
		}
		
		$_wherestr = "loupan_id=? and isdeleted=0";
		$_wherearr = array (
				$_userinfo['loupan_id']
		);
		
		if($keyword!=""){
			$_wherestr.=" and reg_name like ?";
			$_wherearr[]="%$keyword%";
		}
		
		$_retdata = Client_Baobei::getInstance ()->Field ( "*" )->Where ( $_wherestr, $_wherearr )->PageNum ( $page )->PageSize ( $pagesize )->DescBy ( "reg_time" )->Select ();
		
		foreach ($_retdata ['data'] ['data'] as &$item){
			if($item['confirm_time']>0){
				$item['status']="已确认";
			}else{
				if($item['look_time']<UNIX_TIME){
					$item['status']="未确认";
				}else{
					$item['status']="待确认";
				}
			}
			if(date("Y-m-d",time())==date("Y-m-d",$item['look_time'])){
				$item['look_time_str']="今天";
				$item['look_time_str_det']=date("H时i分",$item['look_time']);
			}else{
				$item['look_time_str']=date("m月d日H时i分",$item['look_time']);
				$item['look_time_str_det']="";
			}
		
		}
		
		if ($_retdata ['err']) {
			return $this->Error ( $_orderdata ['data'] );
		}
		if ($this->IsPost ()) {
			return $this->Json(Bfw::RetMsg ( false, $_retdata ['data'] ['data'] ));
		}
		// $this->Assign ( "pagerdata", \App\Lib\Util\PagerUtil::_GenPageData ( $_retdata ['data'] ['count'], $page, $pagesize, 2 ) );
		$this->Assign ( "itemdata", $_retdata ['data'] ['data'] );
		$this->Display ();
	}
	
	/**
	 * 报备二维码
	 *
	 * @param string $id        	
	 */
	function Qcode($id = "") {
		Bfw::import ( "Plugin.QRcode" );
		\QRcode::png ( "http://wei.iiihouse.com". Bfw::ACLINK ( "Baobei", "Confirm", "id=" . $id ), false, "L", 4 );
	}
	
	/**
	 * 报备确认
	 *
	 * @param string $id        	
	 */
	function Confirm($id = "") {
		if ($this->IsPost (true)) {
			$_formdata = $this->FormArray ( array (
					"id",
					"lou_zhigu",
					"lou_zhuli",
					"lou_builder",
					'memo',
			), true, "BaobeiConf" );
			if ($_formdata ['err']) {
				return $this->Error ( $_formdata ['data'] );
			}
			$_data = Client_Baobei::getInstance ()->Confirm ( $_formdata ['data'] , $this->Session ( USER_ID ) );
			if ($_data ['err']) {
				return $this->Error ( Bfw::SelectVal ( $_data ['data'], [ 
						Client_Baobei::DATA_NULL => "数据错误",
						Client_Baobei::NO_POWER => "无权限" 
				] ) );
			}
			return $this->Alert ( "确认报备成功", array (
					array (
							"返回",
							Bfw::ACLINK ( "Baobei", "ListData" ),
							""
					)
			) );
			
		}else{
			$this->OutCharset ( "utf-8" );
			$_data=Client_Baobei::getInstance()->Single($id);
			if($_data['err']||$_data['data']==null){
				return $this->Error("数据错误");
			}
			$this->Assign("itemdata", $_data['data']);
			$this->Display ();
		}
	}
	
	/**
	 * 撤销报备
	 *
	 * @param string $id        	
	 */
	function Cancel($id = "") {
		if ($this->IsPost ()) {
			$_data = Client_Baobei::getInstance ()->Cancel ( $id, $this->Session ( USER_ID ) );
			if ($_data ['err']) {
				return $this->Error ( Bfw::SelectVal ( $_data ['data'], [ 
						Client_Baobei::DATA_NULL => "数据错误",
						Client_Baobei::NO_POWER => "无权限" ,
						Client_Baobei::TIME_WRONG => "预约时间前一天内无法撤销"
				] ) );
			}
			return $this->Success ( "操作成功" );
		}
	}
}

?>