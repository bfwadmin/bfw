<?php
namespace Lib\Util;
class FileUtil
{

    /**
     * 获取目录文件列表
     *
     * @param string $dir
     * @return array
     */
    public static function getFileListByDir($dir)
    {
        $ret = array();
        if (($handle = opendir($dir))!=false) {
            while (false !== ($fileName = readdir($handle))) {
                if ($fileName != "." && $fileName != "..") {
                    $ret[] = $fileName;
                }
            }
        }
        return $ret;
    }
    
    /**
     * 创建目录
     * @param string $path
     * @return boolean
     */
    public static function CreatDir($path)
    {
        if (! is_dir($path)) {
            if (self::CreatDir(dirname($path))) { 
                return mkdir($path, 0777);
            }
        } else {
            return true;
        }
    }
	public static function zip($sourcepath,$newpath){
		$zip = new \ZipArchive();
		if ($zip->open($newpath, \ZipArchive::OVERWRITE) === TRUE) {
			self::addFileToZip($sourcepath, $zip); 
			$zip->close(); 
			@closedir($path);
			return true;
		}else{
			return false;
		}
	}
	public static function unzip($sourcepath,$newpath){
		$zip = new \ZipArchive; 
		if ( $zip->open($sourcepath) === TRUE) { 
			$zip->extractTo($newpath); 
			$zip->close(); 
			return true;
		} else { 
			return false;
		} 
			
	}
	public static function addFileToZip($path, $zip,$folder='/') {
	    $handler = opendir($path); //打开当前文件夹由$path指定。
		while (($filename = readdir($handler)) !== false) {
			if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
					self::addFileToZip($path . "/" . $filename, $zip,$filename);
				} else { //将文件加入zip对象
					$zip->addFile($path . "/" . $filename,$folder.DS.$filename);
				}
			}
		}
		
	}
}

?>