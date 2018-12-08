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
	    if(is_dir($sourcepath)){
	        $zip = new \ZipArchive();
	        if ($zip->open($newpath, \ZipArchive::OVERWRITE) === TRUE) {
	            self::addFileToZip($sourcepath, $zip);
	            $zip->close();
	           
	            return true;
	        }else{
	            return false;
	        }
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
	public static function addFileToZip($path, &$zip,$folder='') {
	    $handler = opendir($path); //打开当前文件夹由$path指定。
	    while (($filename = readdir($handler)) !== false) {
	        if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
	            if (is_dir($path . DS.$filename)) {// 如果读取的某个对象是文件夹，则递归
	                $zip->addEmptyDir($folder.DS.$filename);
	              
	                self::addFileToZip($path . DS.$filename, $zip,$folder.DS.$filename);
	            } else { //将文件加入zip对象
	                $zip->addFile($path . DS.$filename,$folder.DS.$filename);
	            }
	        }
	    }
	    @closedir($path);
	
	}
	/**
	 * Add files and sub-directories in a folder to zip file.
	 * @param string $folder
	 * @param ZipArchive $zipFile
	 * @param int $exclusiveLength Number of text to be exclusived from the file path.
	 */
	private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
	    $handle = opendir($folder);
	    while (false !== $f = readdir($handle)) {
	        if ($f != '.' && $f != '..') {
	            $filePath = "$folder/$f";
	            // Remove prefix from file path before add to zip.
	            $localPath = substr($filePath, $exclusiveLength);
	            if (is_file($filePath)) {
	                $zipFile->addFile($filePath, $localPath);
	            } elseif (is_dir($filePath)) {
	                // Add sub-directory.
	                $zipFile->addEmptyDir($localPath);
	                self::folderToZip($filePath, $zipFile, $exclusiveLength);
	            }
	        }
	    }
	    closedir($handle);
	}
	
	/**
	 * Zip a folder (include itself).
	 * Usage:
	 *   HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip');
	 *
	 * @param string $sourcePath Path of directory to be zip.
	 * @param string $outZipPath Path of output zip file.
	 */
	public static function zipDir($sourcePath, $outZipPath)
	{
	    $pathInfo = pathInfo($sourcePath);
	    $parentPath = $pathInfo['dirname'];
	    $dirName = $pathInfo['basename'];
	
	    $z = new \ZipArchive();
	    $z->open($outZipPath, \ZIPARCHIVE::OVERWRITE);
	    $z->addEmptyDir($dirName);
	    self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
	    $z->close();
	}

}

?>