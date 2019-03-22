<?php
namespace Plugin;
use Lib\Util\FileUtil;
class Upload {
	public static function uploadfile($_filename = "file", $_filter = array("gif","jpeg","pjpeg","jpg","swf","png"), $_limitsize = 1024000, $_uploaddir = UPLOAD_DIR) {
		$result = array ();
		$result ['err'] = true;
		//print_r($_FILES [$_filename]);
		if (isset ( $_FILES [$_filename] )) {			
				$fileExt = self::getfileExt ( $_FILES [$_filename] ["name"] );				
				if (in_array ( strtolower ( $fileExt ), $_filter )) {
					if ($_FILES [$_filename] ["size"] <= $_limitsize) {
						$_datedir = str_replace ( "-", "", date ( "Y-m-d" ) );
						$_daaedir = $_uploaddir."/".$_datedir;
						$fileHelp = new FileUtil();
						if($fileHelp->CreatDir($_daaedir)){
							$file_name = uniqid () . "." . self::getfileExt ( $_FILES [$_filename] ["name"] );
							if (move_uploaded_file ( $_FILES [$_filename] ["tmp_name"], $_daaedir . "/" . $file_name )) {
								$result ["err"] = false;
								$result ["data"] = $_datedir."/".$file_name;
								$result ["type"] = $fileExt;
								$result ["size"] = $_FILES [$_filename] ["size"];
								$result ["url"] = $_daaedir . "/" . $file_name ;
							} else {
								$result ['data'] = $_FILES [$_filename] ["error"] . "错误啦";
							}
						}else{
							$result ['data'] = "目录创建失败";
						}
												
					} else {
						$result ['data'] = "上传文件大小不能超过" . ($_limitsize / 1024) . "kb";
					}
				} else {
					$result ['data'] = "上传类型只支持" . implode ( ",", $_filter );
				}
			} else {
				$result ['data'] = "上传文件不能为空";
			}
		return $result;
	}
	public static function getfileExt($file_name) {
		$ret = "";
		$extend = explode ( ".", $file_name );
		if (is_array ( $extend ) && count ( $extend ) > 1) {
			$va = count ( $extend ) - 1;
			$ret = $extend [$va];
		}
		return $ret;
	}
	public static function UploadFromUrl($url, $_uploaddir = UPLOAD_DIR, $_limitsize = 1024000) {
		$result = array ();
		$result ['err'] = true;
		
			/* if (getimagesize ( $url )) { // 判断文件是否是图片类型
				$ext = strtolower ( substr ( strrchr ( $url, "." ), 1 ) );*/
				$pictype = array (
						"gif",
						"jpg",
						"jpeg",
						"pjpeg",
						"png" 
				);
				$timeout=3;
				// 1. 初始化
				$ch = curl_init();
				// 2. 设置选项，包括URL
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				//设置curl请求连接时的最长秒数，如果设置为0，则无限
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
				//设置curl总执行动作的最长秒数，如果设置为0，则无限
				curl_setopt ($ch, CURLOPT_TIMEOUT,$timeout*2);
				// 3. 执行并获取HTML文档内容
				$img = curl_exec($ch);
				// 4. 释放curl句柄
				curl_close($ch);
				$ch=null;
				$ext="jpg";
				if (imagecreatefromstring($img)) {
					$filename = date ( "dMYHis" ) . "." . $ext;
					
					//$img = file_get_contents ( $url );
					
					$size = strlen ( $img );
					if ($size < $_limitsize) {
						$_datedir = str_replace ( "-", "", date ( "Y-m-d" ) );
						$_daaedir = $_uploaddir."/".$_datedir;
						$fileHelp = new FileUtil();
						if($fileHelp->CreatDir($_daaedir)){
							$sLocalFile = $_daaedir . "/" . $filename;
							file_put_contents ( $sLocalFile, $img );
							$result ["err"] = false;
							$result ['size'] = $size;
							$result ["data"] =$_datedir."/".$filename;
							$result ["type"] = $ext;
							
						}else{
							$result ['data'] = "目录创建失败";
						}
						
					} else {
						$result ["data"] = "图片大小不能超过".($_limitsize / 1024) . "kb";
					}
				} else {
					//$result ["err"] = "下载类型只支持" . implode ( ",", $pictype );
					$result ["data"] ="图片创建失败";
				}
			/* } else {
				$result ["err"] = "远程文件必须是图片类型，请检查路径";
			} */
			return $result;
		
	}
	public static function UploadFromBoard($sUrl, $_limitsize = 1024000, $_uploaddir = UPLOAD_DIR) {
		$result = array ();
		$result ['err'] = true;
		
			$upExt = "jpg,jpeg,gif,png,pjpeg";
			$reExt = '(' . str_replace ( ',', '|', $upExt ) . ')';
			if (substr ( $sUrl, 0, 10 ) == 'data:image') { // base64编码的图片，可能出现在firefox粘贴，或者某些网站上，例如google图片
				if (preg_match ( '/^data:image\/' . $reExt . '/i', $sUrl, $sExt )) {
					$sExt = $sExt [1]; // 得到图片后缀名称
					$imgContent = base64_decode ( substr ( $sUrl, strpos ( $sUrl, 'base64,' ) + 7 ) );
					$file_name = uniqid () . "." . $sExt; // 图片名称
					
					$size = strlen ( $imgContent );
					// echo $_limitsize;exit();
					
					if ($size <= $_limitsize) {
						$_datedir = str_replace ( "-", "", date ( "Y-m-d" ) );
						$_daaedir = $_uploaddir."/".$_datedir;
						$fileHelp = new FileUtil();
						if($fileHelp->CreatDir($_daaedir)){
							$filename = $_daaedir . "/" . $file_name; // 路径
							if (file_put_contents ( $filename, $imgContent )) {
									
								$result ["err"] = false;
								$result ['size'] = $size;
								$result ["data"] = $_datedir."/".$file_name;
								$result ["type"] = $sExt;
							}
						}
						
					} else {
						$result ['data'] = "图片大小不能超过".($_limitsize / 1024) . "kb";
					}
				}
			} else {
				$result ['data'] = "图片数据格式错误！";
			}
			return $result;
		
	}
}

?>