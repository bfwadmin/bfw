<?php
namespace Lib\Util;

/**
 *
 * @author wangbo
 *         文件辅助类
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
        // $folders = explode("/", $path);

        // $nFolders = count($folders);
        // for($i = 0; $i < $nFolders; $i++){
        // $newFolder = '/' . $folders[$i];
        // $path .= $newFolder;

        // if (!file_exists($path) && !is_dir($path)) {
        // mkdir($path,0777);
        // }

        // }
        if (! is_dir($path)) {
            if (self::CreatDir(dirname($path))) {
                return mkdir($path, 0777);
            }
        } else {
            return true;
        }
    }

    /**
     * 计算目录的文件大小 字节
     * 换算成Mb要/1024/1024
     *
     * @param unknown $dir
     *            目录
     * @return number 大小字节数
     */
    public static function CountDirsize($dir)
    {
        $_totalsize = 0;
        $handle = opendir($dir);
        while (false !== ($FolderOrFile = readdir($handle))) {
            if ($FolderOrFile != "." && $FolderOrFile != "..") {
                if (is_dir($dir . DS . $FolderOrFile)) {
                    $_totalsize += self::CountDirsize($dir . DS . $FolderOrFile);
                } else {
                    $_totalsize += filesize($dir . DS . $FolderOrFile);
                }
            }
        }
        closedir($handle);

        return $_totalsize;
    }

    /**
     * 移动文件到新文件夹
     *
     * @param unknown $_files
     *            文件数组
     * @param unknown $_sourcefolder
     *            源文件夹
     * @param unknown $_tofolder
     *            目标文件夹
     */
    public static function MoveFiles($_files, $_sourcefolder, $_tofolder)
    {
        $nFiles = count($_files);
        for ($i = 0; $i < $nFiles; $i ++) {
            $file = $_files[$i];
            rename($_sourcefolder . $file, $_tofolder . DS . $file);
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

    /**
     *
     * @param unknown $_dir
     *            扫描目录
     * @param string $_base
     *            扫描根目录
     * @param string $_ex
     *            排除文件格式
     * @return multitype:multitype:number unknown multitype:number unknown multitype:multitype:number unknown multitype:number NULL unknown
     */
    public static function getfilebydir($_dir, $_base = "/", $_ex = "")
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
                        "data" => self::getfilebydir($file, $_base . $_dir . DS, $_ex)
                    ];
                } else {

                    $_ext = strrchr($file, '.');
                    if ($_ext && $_ext != $_ex) {
                        $_data[] = [
                            "name" => $file,
                            "type" => 2,
                            "data" => $file
                        ];
                    }
                }
            }
        }
        return $_data;
    }

    /**
     * 获取目录下的所有子目录
     *
     * @param unknown $_dir
     * @param unknown $_folderdata
     */
    public static function getsubfoloderbydir($_dir, $_base = DS, &$_folderdata)
    {
        $_dirdata = scandir($_base . $_dir);
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_base . $_dir . $file)) {
                    $_folderdata[] = $_dir . $file;
                    self::getsubfoloderbydir(DS . $file . DS, $_base, $_folderdata);
                }
            }
        }
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

    /**
     * 指定行插入数据
     *
     * @param unknown $_src
     * @param unknown $_string
     * @param unknown $_iLine
     * @return multitype:string unknown
     */
    public static function insertstrbyline($_src, $_string, $_iLine)
    {
        $file_handle = fopen($_src, "r");
        $i = 0;
        $arr = array();
        while (! feof($file_handle)) {
            $line = fgets($file_handle);
            ++ $i;
            if ($i == $_iLine) {
                $arr[] = $_string . $line;
            } else {
                $arr[] = $line;
            }
        }
        fclose($file_handle);
        file_put_contents($_src, implode("", $arr));
        return $arr;
    }

    /**
     * 指定行删除数据
     *
     * @param unknown $_src
     * @param unknown $_string
     * @param unknown $_iLine
     * @return multitype:string unknown
     */
    public static function deletestrbyline($_src, $_string, $_iLine)
    {
        $file_handle = fopen($_src, "r");
        $i = 0;
        $arr = array();
        while (! feof($file_handle)) {
            $line = fgets($file_handle);
            ++ $i;
            if ($i == $_iLine) {
                $arr[] = str_replace($_string, "", $line);
            } else {
                $arr[] = $line;
            }
        }
        fclose($file_handle);
        file_put_contents($_src, implode("", $arr));
        return $arr;
    }

    /**
     * This function handles the pull / init / clone of a git repo
     *
     * @param $git_url Example
     *            of git clone url git://github.com/someuser/somerepo.git
     *
     * @return bool true
     */
    public static function pullOrCloneRepo($git_url, $file_path)
    {
        if (! isset($git_url)) {
            return false;
        }
        // validate contains git://github.com/

        echo $file_path;
        if (strpos($git_url, 'git') !== FALSE) {

            if (! is_dir($file_path)) {
                self::CreatDir($file_path);
            }

            // $file_path = drupal_realpath($uri); // change this if not in drupal
            if (is_dir($file_path)) {
                $first_dir = getcwd();
                // change dir to the new path
                $new_dir = chdir($file_path);
                // Git init

                $git_init = shell_exec('git init');
                // Git clone
                $git_clone = shell_exec('git clone ' . $git_url);

                // Git pull
                $git_pull = shell_exec('git pull');
                // change dir back
                $change_dir_back = chdir($first_dir);
                return true;
            }
        } else {
            return false;
        }
    }
}

?>