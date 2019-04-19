<?php
namespace Lib\Util;

/**
 * @author wangbo
 * 文件辅助类
 */
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
        if (($handle = opendir($dir)) != false) {
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
     *
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

    public static function zip($sourcepath, $newpath)
    {
        if (is_dir($sourcepath)) {
            $zip = new \ZipArchive();
            if ($zip->open($newpath, \ZipArchive::OVERWRITE) === TRUE) {
                self::addFileToZip($sourcepath, $zip);
                $zip->close();
                return true;
            } else {
                return false;
            }
        }
    }

    public static function unzip($sourcepath, $newpath)
    {
        $zip = new \ZipArchive();
        if ($zip->open($sourcepath) === TRUE) {
            $zip->extractTo($newpath);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除目录及文件
     *
     * @param unknown $dirName
     * @return boolean
     */
    public static function delDirAndFile($dirName)
    {
        if (false != ($handle = opendir($dirName))) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir($dirName . DS . $item)) {
                        self::delDirAndFile($dirName . DS . $item);
                    } else {
                        @unlink($dirName . DS . $item);
                    }
                }
            }
            closedir($handle);
            return rmdir($dirName);
        }
    }

    public static function addFileToZip($path, &$zip, $folder = '')
    {
        $handler = opendir($path); // 打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") { // 文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . DS . $filename)) { // 如果读取的某个对象是文件夹，则递归
                    $zip->addEmptyDir($folder . DS . $filename);

                    self::addFileToZip($path . DS . $filename, $zip, $folder . DS . $filename);
                } else { // 将文件加入zip对象
                    $zip->addFile($path . DS . $filename, $folder . DS . $filename);
                }
            }
        }
        @closedir($path);
    }

    public static function copydir($src, $des)
    {
        $dir = opendir($src);
        self::CreatDir($des);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . DS . $file)) {
                    self::copydir($src . DS . $file, $des . DS . $file);
                } else {
                    copy($src . DS . $file, $des . DS . $file);
                }
            }
        }
        @closedir($dir);
    }

    public static function replace_text($_src, $_search, $_repalce)
    {
        $_dirdata = scandir($_src);
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_src . DS . $file)) {
                    self::replace_text($_src . DS . $file, $_search, $_repalce);
                } else {
                    $_data = file_get_contents($_src . DS . $file);
                    $_data = str_replace($_search, $_repalce, $_data);
                    file_put_contents($_src . DS . $file, $_data);
                }
            }
        }
    }

    public static function getfilebydir($_dir, $_base = "/")
    {
        $_data = [];
        $_dirdata = scandir($_base . $_dir);
        // var_dump($_dirdata);
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_base . $_dir . DS . $file)) {
                    $_data[] = [
                        "name" => $file,
                        "type" => 1,
                        "data" => self::getfilebydir($file, $_base . $_dir . DS)
                    ];
                } else {
                    $_data[] = [
                        "name" => $file,
                        "type" => 2,
                        "data" => $file
                    ];
                }
            }
        }
        return $_data;
    }

    public static function copy_replace_text($_src, $_des, $_search, $_repalce)
    {
        $_dirdata = scandir($_src);
        self::CreatDir($_des);
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_src . DS . $file)) {
                    self::copy_replace_text($_src . DS . $file, $_des . DS . $file, $_search, $_repalce);
                } else {
                    copy($_src . DS . $file, $_des . DS . $file);
                    $_data = file_get_contents($_des . DS . $file);
                    $_data = str_replace($_search, $_repalce, $_data);
                    file_put_contents($_des . DS . $file, $_data);
                }
            }
        }
    }
}

?>