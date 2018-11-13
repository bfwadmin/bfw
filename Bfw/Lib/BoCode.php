<?php

namespace Lib;

use Lib\Bfw;
use Lib\Db\DbFactory;

class BoCode extends WangBo {
	// 不需要重新生成的
	protected $_nogentable = array (
			"cms_user" 
	);
	protected $_dbhandle = null;
	public function __construct() {
		if ($this->_dbhandle == null) {
			$this->_dbhandle = DbFactory::GetInstance (Bfw::Config ( "Db", "localconfig" ));
		}
	}
	public function Generate($_domian, $_tablename = "", $_isoveride = false) {
		if ($_tablename == '') {
			$_tablesdata = $this->_dbhandle->GetDbTable ();
			if ($_tablesdata ['err']) {
				$this->Error ( $_tablesdata ['data'] );
				return;
			}
			foreach ( $_tablesdata ['data'] as $_table_name ) {
				if (! in_array ( $_table_name ['name'], $this->_nogentable )) {
					$_tableinfodata = $this->_dbhandle->GetTableInfo ( $_table_name ['name'] );
					if ($_tableinfodata ['err']) {
						$this->Error ( $_tableinfodata ['data'] );
						return;
					}
					$this->_generate ( $_table_name, $_tableinfodata ['data'], $_domian, $_isoveride );
				}
			}
		} else {
			if (! in_array ( $_tablename, $this->_nogentable )) {
				$_tableinfodata = $this->_dbhandle->GetTableInfo ( $_tablename );
				if ($_tableinfodata ['err']) {
					$this->Error ( $_tableinfodata ['data'] );
					return;
				}
				$this->_generate ( array (
						"name" => $_tablename,
						"memo" => "" 
				), $_tableinfodata ['data'], $_domian, $_isoveride );
			}
		}
	}
	private function _replacetag($_temp, $_out, $_fields, $_table, $_m, $_isoveride, $_dom) {
		
		if (file_exists ( $_temp )) {
			$_temp_cont = file_get_contents ( $_temp );
			if (preg_match_all ( "/<temp>([\\s\\S]*?)<\\/temp>/", $_temp_cont, $match )) {
				for($i = 0; $i < count ( $match [0] ); $i ++) {
					$_f_h = "";
					foreach ( $_fields as $_f ) {
						if ($_f ['key'] == "") {
							$_f_h .= str_replace ( "FIELDNAME", $_f ['name'], str_replace ( "MEMO", $_f ['memo'] == "" ? $_f ['name'] : $_f ['memo'], $match [1] [$i] ) );
						}
					}
					$_temp_cont = str_replace ( $match [0] [$i], $_f_h, $_temp_cont );
				}
			}
			$_temp_cont = str_replace ( "DOM", $_dom, $_temp_cont );
			$_temp_cont = str_replace ( "KEY", "", $_temp_cont );
			$_temp_cont = str_replace ( "FIELDNAMEARRAY", $this->_implode ( ",", $_fields ), $_temp_cont );
			$_temp_cont = str_replace ( "CONTNAME", $_m, $_temp_cont );
			$_temp_cont = str_replace ( "CONTMEMO", $_table ['memo'], $_temp_cont );
			
			if (file_exists ( $_out )) {
				if ($_isoveride) {
					if (rename ( $_out, $_out . "." . time () . ".bak" )) {
						if ($this->_file_put_content ( $_out, $_temp_cont )) {
							return true;
						}
					} else {
						if ($this->_file_put_content ( $_out . '.new', $_temp_cont )) {
							return true;
						}
					}
				}
			} else {
				if ($this->_file_put_content ( $_out, $_temp_cont )) {
					return true;
				}
			}
			return false;
		}
	}
	private function CreatDir($path) {
		if (! is_dir ( $path )) {
			if ($this->CreatDir ( dirname ( $path ) )) {
				mkdir ( $path, 0777 );
				return true;
			}
		} else {
			return true;
		}
	}
	private function _file_put_content($_filepath, $_filecont) {
		$path = dirname ( $_filepath );
		$this->CreatDir ( $path );
		return file_put_contents ( $_filepath, $_filecont );
	}
	private function _generate($_table, $_fields, $_domian, $_isoveride) {
		$_m_name = ucfirst ( str_replace ( TB_PRE, "", $_table ['name'] ) );
		
		if ($this->_checkValid ( $_m_name )) {
			$_temp_arr = array (
					array (
							"Db.php",
							"/$_domian/Config/Db.php" 
					),
					array (
							"AddData.php",
							"/$_domian/View/$_m_name/AddData.php" 
					),
					array (
							"Model.php",
							"/$_domian/Model/Model_$_m_name.php" 
					),
					array (
							"EditData.php",
							"/$_domian/View/$_m_name/EditData.php" 
					),
					array (
							"ListData.php",
							"/$_domian/View/$_m_name/ListData.php" 
					),
					array (
							"Controler.php",
							"/$_domian/Controler/Controler_$_m_name.php" 
					),
					array (
							"Points.php",
							"/$_domian/Points/Points_$_m_name.php" 
					),
					array (
							"Client.php",
							"/$_domian/Client/Client_$_m_name.php" 
					),
					array (
							"Service.php",
							"/$_domian/Service/Service_$_m_name.php" 
					),
					array (
							"Validate.php",
							"/$_domian/Validate/Validate_$_m_name.php" 
					) 
			);
			foreach ( $_temp_arr as $item ) {
				$_ret = $this->_replacetag ( APP_ROOT . '/' . CODE_TEMP_PATH . '/' . $item [0], APP_ROOT . "/App/".$item [1], $_fields, $_table, $_m_name, $_isoveride, $_domian );
				
				if (WEB_DEBUG) {
					if ($_ret) {
						global $_debug_info_array;
						$_debug_info_array [] = array (
								microtime ( true ),
								APP_ROOT . $item [1] . " 代码生成成功" 
						);
					}
				}
			}
		}
	}
	private function _implode($de, $ar) {
		$str = "";
		foreach ( $ar as $_a ) {
			if ($_a ['key'] == "") {
				$str .= '"' . $_a ['name'] . '"' . $de;
			}
		}
		return rtrim ( $str, $de );
	}
	private function _checkValid($str) {
		if (strlen ( $str ) < 4) {
			return false;
		}
		$arr = str_split ( $str, 1 );
		$num = count ( $arr );
		for($i = 0; $i < $num; $i ++) {
			if (is_numeric ( $arr [$i] )) {
				return false;
			}
		}
		return true;
	}
}
?>