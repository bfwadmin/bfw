<?php
namespace Lib;

class BoJob
{

    private $_pdo = null;

    const UNSERVICENUM = 3;

    private function init_db()
    {
        if ($this->_pdo == null) {
            $this->_pdo = new \PDO('sqlite:' . DATA_DIR . 'job.db');
        }
        $this->_pdo->exec("CREATE TABLE IF NOT EXISTS job (
            id INTEGER PRIMARY KEY,
            username TEXT,
            usertoken TEXT,
            title TEXT,
            cont TEXT,
            fromtoken TEXT,
            fromname TEXT,
            state TEXT,
            startime TEXT,
            endtime TEXT,
            memo TEXT,
            atime TEXT)");
    }

    public function listjob($_usertoken, $_state = 'going')
    {
        $this->init_db();
        $sql = "SELECT * FROM job where usertoken=:usertoken and state=:state";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':state', $_state);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }

    public function updatejob($_id, $_title, $_cont, $_starttime, $_endtime, $_usertoken, $_state)
    {
        $this->init_db();
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "UPDATE wikipage SET title=:title,cont=:cont,classname=:classname) WHERE id=:id and fromtoken=:usertoken";
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':memo', $time);
            $stmt->bindParam(':title', $_title);
            $stmt->bindParam(':atime', $time);
            $stmt->bindParam(':cont', $_cont);
            $stmt->bindParam(':startime', $_starttime);
            $stmt->bindParam(':endtime', $_endtime);
            $stmt->bindParam(':fromtoken', $_usertoken);
            $stmt->bindParam(':fromname', $_username);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }

    public function addjob($_title, $_cont, $_starttime, $_endtime, $_usertoken)
    {
        $this->init_db();
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "INSERT INTO job (username,usertoken,memo,title,atime,cont,startime,endtime,fromtoken,fromname,state) VALUES (:username,:usertoken,:memo,:title,:atime,:cont,:startime,:endtime,:fromtoken,:fromname,'going')";
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':memo', $time);
            $stmt->bindParam(':title', $_title);
            $stmt->bindParam(':atime', $time);
            $stmt->bindParam(':cont', $_cont);
            $stmt->bindParam(':startime', $_starttime);
            $stmt->bindParam(':endtime', $_endtime);
            $stmt->bindParam(':fromtoken', $_usertoken);
            $stmt->bindParam(':fromname', $_username);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }
}
?>