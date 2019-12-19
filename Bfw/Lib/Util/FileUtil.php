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
     * 自动解析编码读入文件
     *
     * @param string $file
     *            文件路径
     * @param string $charset
     *            读取编码
     * @return string 返回读取内容
     */
    static function auto_readfile($file, $charset = 'UTF-8')
    {
        $list = array(
            'GBK',
            'UTF-8',
            'UTF-16LE',
            'UTF-16BE',
            'ISO-8859-1'
        );
        $str = file_get_contents($file);
        foreach ($list as $item) {
            $tmp = mb_convert_encoding($str, $item, $item);
            if (md5($tmp) == md5($str)) {
                return iconv($item, "UTF-8//IGNORE", $str);

                return mb_convert_encoding($str, $charset, $item);
            }
        }
        return "";
    }

    /**
     * 创建目录和写入文件
     *
     * @param unknown $_filepath
     */
    static function createWrite($_filepath, $_filecont)
    {
        self::CreatDir(dirname($_filepath));
        return file_put_contents($_filepath, $_filecont);
    }

    /**
     * 获取目录下所有文件，包含子目录文件，并返回一个相对路径数组
     *
     * @param string $_dir
     * @param string $_base
     * @param array $filelist
     */
    static function getfileArraybydir($_dir, $_base = "", &$filelist)
    {
        $_dirdata = scandir($_dir);

        foreach ($_dirdata as $file) {

            if (($file != '.') && ($file != '..')) {
                if (is_dir($_dir . "/" . $file)) {
                    self::getfilebydir($_dir . "/" . $file, $_base . "/" . $file, $filelist);
                } else {
                    $filelist[] = $_base . "/" . $file;
                }
            }
        }
    }

    /**
     * 搜索目录下所有文件，包含子目录文件，并返回一个相对路径数组
     *
     * @param string $_dir
     * @param string $_base
     * @param string $key
     * @param array $filelist
     */
    static function searchfilenameArraybydir($_dir = "", $key = "", &$filelist)
    {
        $_dirdata = scandir($_dir);
        if (! $_dirdata) {
            return;
            // $filelist=[];
        }
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_dir . $file)) {
                    self::searchfilenameArraybydir($_dir . $file, $key, $filelist);
                } else {
                    if (strstr($file, $key)) {
                        $filelist[] = $_dir . $file;
                    }
                }
            }
        }
    }

    /**
     * 搜索目录下所有文件内容，包含子目录文件，并返回一个相对路径数组
     *
     * @param string $_dir
     * @param string $_base
     * @param string $key
     * @param array $filelist
     */
    static function searchfilecontArraybydir($_dir, $key = "", &$filelist)
    {
        $_dirdata = scandir($_dir);
        if (! $_dirdata) {
            return;
        }

        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_dir . $file)) {
                    self::searchfilecontArraybydir($_dir . $file, $key, $filelist);
                } else {
                    $content = file_get_contents($_dir . $file);
                    if (strpos($content, $key) !== false) {
                        $filelist[] = $_dir . $file;
                    }
                }
            }
        }
    }

    /**
     * 搜索目录下所有文件内容，包含子目录文件，并返回一个相对路径数组
     *
     * @param string $_dir
     * @param string $_base
     * @param string $key
     * @param array $filelist
     */
    static function replacefilecontArraybydir($_dir, $key = "", $val = "", &$filelist)
    {
        $_dirdata = scandir($_dir);
        if (! $_dirdata) {
            return;
        }
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_dir . $file)) {
                    self::replacefilecontArraybydir($_dir . $file, $key, $val, $filelist);
                } else {
                    $content = file_get_contents($_dir . $file);
                    // echo $content;
                    $content = str_replace($key, $val, $content, $count);
                    if ($count > 0) {
                        file_put_contents($_dir . $file, $content);
                        $filelist[] = $_dir . $file;
                    }
                }
            }
        }
    }

    /**
     * 获取目录文件md5签名值
     *
     * @param string $_dir
     * @param string $_base
     * @param array $filelist
     */
    static function getfileMd5Arraybydir($_dir, $_base = "", &$filelist)
    {
        $_dirdata = scandir($_dir);

        foreach ($_dirdata as $file) {

            if (($file != '.') && ($file != '..')) {
                if (is_dir($_dir . "/" . $file)) {
                    self::getfileMd5Arraybydir($_dir . "/" . $file, $_base . "/" . $file, $filelist);
                } else {
                    $filelist[$_base . "/" . $file] = md5_file($_dir . "/" . $file);
                }
            }
        }
    }

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
                if ($zip->open($newpath, \ZipArchive::CREATE) === TRUE) {
                    self::addFileToZip($sourcepath, $zip);
                    $zip->close();
                    return true;
                } else {
                    return false;
                }
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

    /**
     * 删除某个目录中的所有子目录及寄文件
     *
     * @param unknown $dirName
     * @param unknown $ext
     *
     */
    public static function delFile($dirName, $ext = [])
    {
        $_dirdata = scandir($dirName);
        foreach ($_dirdata as $file) {
            if ($file != "." && $file != ".." && ! in_array(strtolower($file), $ext)) {
                if (is_dir($dirName . DS . $file)) {
                    self::delFile($dirName . DS . $file, $ext);
                } else {
                    @unlink($dirName . DS . $file);
                }
            }
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

    public static function copydir($src, $des, $extfile = [])
    {
        if (! is_dir($src)) {
            return false;
        }

        $from_files = scandir($src);
        // 如果不存在目标目录，则尝试创建
        if (! self::CreatDir($des)) {
            return false;
        }
        if (! empty($from_files)) {
            foreach ($from_files as $file) {
                if ($file == '.' || $file == '..' || in_array(strtolower($file), $extfile)) {
                    continue;
                }
                if (is_dir($src . '/' . $file)) { // 如果是目录，则调用自身
                    self::copydir($src . '/' . $file, $des . '/' . $file, $extfile);
                } else { // 直接copy到目标文件夹
                    copy($src . '/' . $file, $des . '/' . $file);
                }
            }
        }
        // @closedir($dir);
        // $dir = opendir($src);
        // if (! is_dir($des)) {
        // self::CreatDir($des);
        // }
        // $file = readdir($dir);
        // if (($file != '.') && ($file != '..')) {
        // if (is_dir($src . $file)) {
        // self::copydir($src . $file, $des . $file);
        // } else {
        // copy($src . $file, $des . $file);
        // }
        // }

        return true;
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
        $_type = array_column($_data, 'type');
        array_multisort($_type, SORT_ASC, $_data);
        return $_data;
    }

    /**
     * 获取目录下的所有子目录,返回相对地址
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
                    self::getsubfoloderbydir($_dir . $file . DS, $_base, $_folderdata);
                } else {
                    // $_folderdata[] = $_dir . $file;
                }
            }
        }
    }

    /**
     *
     * @param unknown $_dir
     * @param string $_base
     * @param unknown $_folderdata
     */
    public static function getsubfilebydir($_dir, $_base = DS, &$_folderdata, $_except = "")
    {
        $_exceptfolder = [];
        $_exceptfile = [];
        if ($_except != "") {
            $_exceptarr = explode("|", $_except);
            foreach ($_exceptarr as $item) {
                $_lastword = substr($item, - 1);
                if ($_lastword == "/" || $_lastword == "*") {
                    $_exceptfolder[] = dirname($item);
                } else {
                    $_exceptfile[] = $item;
                }
            }
        }
        $_dirdata = scandir($_base . $_dir);
        foreach ($_dirdata as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($_base . $_dir . $file) && ! in_array($_dir . $file, $_exceptfolder)) {
                    self::getsubfilebydir($_dir . $file . DS, $_base, $_folderdata);
                } else {
                    if (! in_array($_dir . $file, $_exceptfile)) {
                        $_folderdata[] = $_dir . $file;
                    }
                }
            }
        }
    }

    /**
     * sftp上传目录
     *
     * @param unknown $_ftpconf
     * @param string $_local
     * @param string $_remote
     * @param string $_except
     * @return boolean|multitype:NULL
     */
    public static function sftp_uploadfolder($_ftpconf = [], $_local = "", $_remote = "", $_except = "")
    {
        if (! isset($_ftpconf['host']) || ! isset($_ftpconf['user']) || ! isset($_ftpconf['pwd'])) {
            return "连接参数错误";
        }
        $_conn = @ssh2_connect($_ftpconf['host'], isset($_ftpconf['port']) ? $_ftpconf['port'] : 22);
        if (FALSE === $_conn) {
            return "连接服务器错误";
        }
        // 使用username和password登录
        $_authdata = @ssh2_auth_password($_conn, $_ftpconf['user'], $_ftpconf['pwd']);
        if (FALSE === $_authdata) {
            return "账号密码错误";
        }
        $_sftp = @ssh2_sftp($_conn);
        if (FALSE === $_sftp) {
            return "初始化连接出错";
        }
        $folderdata = [];
        self::getsubfilebydir("", $_local, $folderdata, $_except);
        foreach ($folderdata as $item) {
            $remote_dir = dirname($_remote . $item);

            if(!file_exists('ssh2.sftp://' . $_sftp . $remote_dir)){
                $path_arr = explode(DS, $remote_dir);
                unset($path_arr[0]);
                $_pathstr=DS;
                foreach ($path_arr as $val) {
                    $_pathstr.=$val.DS;
                    @ssh2_sftp_mkdir($_sftp,$_pathstr);
                }
            }
            $_stream = @fopen("ssh2.sftp://{$_sftp}".$_remote.$item, 'w');
            if(FALSE === $_stream){
            return "创建文件错误".$_remote.$item;
            }
            $_data_to_send = @file_get_contents($_local.$item);
            if(FALSE === $_data_to_send){
            return "打开本地文件错误".$_local.$item;
            }
            if (@fwrite($_stream, $_data_to_send) === false){
            return "写入文件错误".$_remote.$item;
            }
            @fclose($_stream);

        }
        return false;
    }

    /**
     * ftp上传目录
     *
     * @param unknown $_ftpconf
     * @param string $_local
     * @param string $_remote
     * @param string $_except
     * @return boolean|multitype:NULL
     */
    public static function ftp_uploadfolder($_ftpconf = [], $_local = "", $_remote = "", $_except = "")
    {
        if (! isset($_ftpconf['host']) || ! isset($_ftpconf['user']) || ! isset($_ftpconf['pwd'])) {
            return "连接参数错误";
        }
        $_conn = @ftp_connect($_ftpconf['host'], isset($_ftpconf['port']) ? $_ftpconf['port'] : 21);
        if (FALSE === $_conn) {
            return "连接服务器错误";
        }
        // 使用username和password登录
        $_authdata = @ftp_login($_conn, $_ftpconf['user'], $_ftpconf['pwd']);
        if (FALSE === $_authdata) {
            ftp_quit($_conn);
            return "账号密码错误";
        }
        $folderdata = [];
        self::getsubfilebydir("", $_local, $folderdata, $_except);

        ftp_chdir($_conn, "/");
        if (@ftp_pasv($_conn, isset($_ftpconf['pasv']) ? $_ftpconf['pasv'] : false)) {
            foreach ($folderdata as $item) {
                $remote_dir = dirname($_remote . $item);
                $path_arr = explode(DS, $remote_dir);
                foreach ($path_arr as $val) {

                    if (@ftp_chdir($_conn, $val) == FALSE) {
                        $tmp = @ftp_mkdir($_conn, $val);
                        @ftp_chdir($_conn, $val);
                    }
                }
                ftp_chdir($_conn, "/");
                ftp_put($_conn, $_remote . $item, $_local . $item, FTP_BINARY);
            }
        }
        ftp_quit($_conn);
        return false;
    }

    /**
     * ftp上传文件
     *
     * @param unknown $_ftpconf
     * @param string $_local
     * @param string $_remote
     * @return boolean
     */
    public static function ftp_uploadfile($_ftpconf = [], $_local = "", $_remote = "")
    {
        if (! isset($_ftpconf['host']) || ! isset($_ftpconf['user']) || ! isset($_ftpconf['pwd'])) {
            return "连接参数错误";
        }
        $_conn = @ftp_connect($_ftpconf['host'], isset($_ftpconf['port']) ? $_ftpconf['port'] : 21);
        if (FALSE === $_conn) {
            return "连接服务器错误";
        }
        // 使用username和password登录
        $_authdata = @ftp_login($_conn, $_ftpconf['user'], $_ftpconf['pwd']);
        if (FALSE === $_authdata) {
            ftp_quit($_conn);
            return "账号密码错误";
        }

        $_ret = false;
        if (@ftp_pasv($_conn, isset($_ftpconf['pasv']) ? $_ftpconf['pasv'] : false)) {
            $_ret = @ftp_put($_conn, $_remote, $_local, FTP_BINARY);
        }
        ftp_quit($_conn);
        return $_ret;
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