<?php
namespace Lib;


use Lib\Util\StringUtil;

/**
 * @author wangbo
 * 版本管理
 */
class BoVersion
{


    private $_version_pdo = null;



    private function initversion_db($_appname)
    {
        if ($this->_version_pdo == null) {
            $this->_version_pdo = new \PDO('sqlite:' . DATA_DIR . $_appname . '_appversion.db');
        }
        $this->_version_pdo->exec("CREATE TABLE IF NOT EXISTS appversion (
            id INTEGER PRIMARY KEY,
            appname TEXT,
            memo TEXT,
            username TEXT,
            usertoken TEXT,
            versionpath TEXT,
            atime TEXT);CREATE TABLE IF NOT EXISTS commitlog (
            id INTEGER PRIMARY KEY,
            appname TEXT,
            memo TEXT,
            file TEXT,
            username TEXT,
            usertoken TEXT,
            actiontype TEXT,
            atime TEXT)");
    }


    public function commitlog($_appname, $_file, $_type, $_memo, $_usertoken)
    {
        $this->initversion_db($_appname);
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "INSERT INTO commitlog (username,appname,memo,usertoken,atime,actiontype,file) VALUES (:username,:appname,:memo,:usertoken,:atime,:actiontype,:file)";
        $stmt = $this->_version_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':appname', $_appname);
            $stmt->bindParam(':atime', $time);
            $stmt->bindParam(':memo', $_memo);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':actiontype', $_type);
            $stmt->bindParam(':file', $_file);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }

    public function getcommitlog($_appname, $_pagesize, $_pagenum)
    {
        $this->initversion_db($_appname);
        $sql = "SELECT * FROM commitlog where appname=:appname";
        $stmt = $this->_version_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':appname', $_appname);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }

    public function getappversion($_appname)
    {
        $this->initversion_db($_appname);
        // $time = date('Y-m-d H:i:s');
        $sql = "SELECT atime,memo,id FROM appversion where appname=:appname";
        $stmt = $this->_version_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':appname', $_appname);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }


    public function addappversion($_appname, $_memo, $_usertoken)
    {
        $this->initversion_db($_appname);
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "INSERT INTO appversion (username,appname,memo,usertoken,atime,versionpath) VALUES (:username,:appname,:memo,:usertoken,:atime,:versionpath)";
        $stmt = $this->_version_pdo->prepare($sql);
        if ($stmt) {
            $token = StringUtil::guid();
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':appname', $_appname);
            $stmt->bindParam(':atime', $time);
            $stmt->bindParam(':memo', $_memo);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':versionpath', $_username);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }
    public function setappversion($_appname, $_v, $_usertoken)
    {
        $this->initversion_db($_appname);
        $sql = "SELECT versionpath FROM appversion where id=:id";
        $stmt = $this->_version_pdo->prepare($sql);
        // $_data = [];
        if ($stmt) {
            $stmt->bindParam(':id', $_v);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return [
                'err' => false,
                "data" => $_data[0]['versionpath']
            ];
        } else {
            return [
                'err' => true,
                "data" => "not exist"
            ];
        }
    }

}
?>