<?php
namespace Lib;

class BoWiki
{

    private $_pdo = null;

    private function init_db()
    {
        if ($this->_pdo == null) {
            $this->_pdo = new \PDO('sqlite:' . DATA_DIR . 'wiki.db');
        }
        $this->_pdo->exec("CREATE TABLE IF NOT EXISTS wikipage (
            id INTEGER PRIMARY KEY,
            title TEXT,
            cont TEXT,
            classname TEXT,
            usertoken TEXT,
            username TEXT,
            creatortoken TEXT,
            creatorname TEXT,
            isdeleted INTEGER,
            uptime TEXT,
            atime TEXT);CREATE TABLE IF NOT EXISTS wikilog (
            id INTEGER PRIMARY KEY,
            wid INTEGER,
            classname TEXT,
            cont TEXT,
            title TEXT,
            username TEXT,
            usertoken TEXT,
            atime TEXT)");
    }

    public function addwikipage($_classname, $_title, $_cont, $_usertoken)
    {
        $this->init_db();
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "INSERT INTO wikipage (title,cont,classname,usertoken,creatortoken,creatorname,atime,isdeleted,uptime,username) VALUES (:title,:cont,:classname,:usertoken,:creatortoken,:creatorname,:atime,0,:uptime,:username)";
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':title', $_title);
            $stmt->bindParam(':cont', $_cont);
            $stmt->bindParam(':classname', $_classname);
            $stmt->bindParam(':atime', $time);
            $stmt->bindParam(':uptime', $time);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':creatorname', $_username);
            $stmt->bindParam(':creatortoken', $_usertoken);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }

    public function getwikiclass()
    {
        $this->init_db();
        // $time = date('Y-m-d H:i:s');
        $sql = "SELECT classname,title,id FROM wikipage order by classname";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }

    public function getwikipage($_id)
    {
        $this->init_db();
        // $time = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM wikipage where id=:id";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':id', $_id);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }

    public function getwikilogpage($_vid)
    {
        $this->init_db();
        // $time = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM wikilog where id=:id";
        $stmt = $this->_pdo->prepare($sql);
        $_data = [];
        if ($stmt) {
            $stmt->bindParam(':id', $_vid);
            $stmt->execute();
            $_data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        }
        return $_data;
    }

    public function updatewikipage($_id, $_classname, $_title, $_cont, $_usertoken,$_isdeleted)
    {
        $this->init_db();
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "INSERT INTO wikilog (wid,title,cont,classname,usertoken,atime,username)
SELECT id,title,cont,classname,usertoken,atime,username FROM wikipage; UPDATE wikipage SET title=:title,cont=:cont,classname=:classname,isdeleted=:isdeleted,uptime=:uptime,usertoken=:usertoken,username=:username) WHERE id=:id";
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':title', $_title);
            $stmt->bindParam(':cont', $_cont);
            $stmt->bindParam(':classname', $_classname);
            $stmt->bindParam(':isdeleted', $_isdeleted);
            $stmt->bindParam(':usertoken', $_usertoken);
            $stmt->bindParam(':username', $_username);
            $stmt->bindParam(':id', $_id);
            $stmt->bindParam(':uptime', $time);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }

    public function rollbackwikipage($_id, $_vid)
    {
        $_data = $this->getwikilogpage($_vid);
        if (empty($_data)) {
            return false;
        }
        $time = date('Y-m-d H:i:s');
        $_username = "wanbo";
        $sql = "UPDATE wikipage SET title=:title,cont=:cont,classname=:classname,isdeleted=0) WHERE id=:id";
        $stmt = $this->_pdo->prepare($sql);
        if ($stmt) {
            $stmt->bindParam(':title', $_data['title']);
            $stmt->bindParam(':cont', $_data['cont']);
            $stmt->bindParam(':classname', $_data['classname']);

            $stmt->bindParam(':id', $_id);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } else {
            return false;
        }
    }
}
?>