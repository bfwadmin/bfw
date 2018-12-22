<?php
namespace Lib\Db;

use Lib\Bfw;
use Lib\Exception\DbException;
use Lib\BoDebug;
require_once APP_ROOT . DS . "Lib" . DS . "Db" . DS . 'BoDb.php';

class DbSqlite extends BoDb implements BoDbInterface
{

    private $_connection = null;

    private $_connectstr = "";

    private $_username = "";

    private $_password = "";

    private $_intrans = false;

    private $_option = [
        \PDO::ATTR_PERSISTENT => false,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ];

    public function __construct($_connarr = null)
    {
        if (! is_null($_connarr) && is_array($_connarr)) {
        } else {
            $_connarr=Bfw::Config("Db", "localconfig");
        }
        $this->_connectstr ="sqlite:{$_connarr['dbname']}";
        try {
            $this->_connection = new \PDO($this->_connectstr, $this->_username, $this->_password, $this->_option);
            $this->_connection->query('SET NAMES utf8');
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function single($_id, $_field, $_tablename, $_key,$_lock=false)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            
            $sql = "SELECT {$_field} FROM  {$_tablename} WHERE {$_key}=:id";
            BoDebug::Info($sql);
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute(array(
                ':id' => $_id
            ));
            $_ldata = null;
            while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) != false) {
                $_ldata = $row;
            }
            return Bfw::RetMsg(false, $_ldata);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function insert($_data, $_tablename, $_returnid = false)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if (! empty($_data)) {
                // $_tablename = $this->GetTableName();
                $sql_field = "";
                $sql_val = "";
                $val = array();
                foreach ($_data as $_key => $_val) {
                    if ($_val === null) {} else {
                        
                        $sql_field .= "{$_key},";
                        $sql_val .= "?,";
                        $val[] = $_val;
                    }
                }
                if ($sql_field != "") {
                    $sql_field = substr($sql_field, 0, strlen($sql_field) - 1);
                }
                if ($sql_val != "") {
                    $sql_val = substr($sql_val, 0, strlen($sql_val) - 1);
                }
                $sql = "INSERT INTO {$_tablename} ({$sql_field})VALUES ({$sql_val})";
                BoDebug::Info($sql);
                $issuccess = $this->_connection->prepare($sql)->execute($val);
                if ($issuccess) {
                    if ($_returnid) {
                        return Bfw::RetMsg(false, $this->_connection->lastInsertId());
                    } else {
                        return Bfw::RetMsg(false, true);
                    }
                } else {
                    return Bfw::RetMsg(false, false);
                }
            } else {
                return Bfw::RetMsg(true, "数据不能为空");
            }
        } catch (\PDOException $e) {
            // return Bfw::RetMsg(true, $e->getMessage());
            throw new DbException($e->getMessage());
        }
    }

    public function update($_data, $_tablename, $_key)
    {
        try {
            if (! isset($_data[$_key])) {
                return Bfw::RetMsg(true, "主键{$_key}值必须传");
            }
            $_singledata = $this->single($_data[$_key], $_key, $_tablename, $_key);
            if ($_singledata['err']) {
                return $_singledata;
            }
            if ($_singledata['data'] == null) {
                return Bfw::RetMsg(true, "data_null");
            }
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if (! empty($_data)) {
                
                $sql = "";
                $val = array();
                foreach ($_data as $_k => $_val) {
                    
                    if (! is_null($_val)) {
                        if (is_array($_val) && count($_val) >= 2 && is_numeric($_val[1]) && in_array($_val[0], [
                            '-',
                            '+'
                        ])) {
                            $sql .= $_k . "=" . $_k . $_val[0] . $_val[1] . ",";
                        } else {
                            if (strtolower($_k) != $_key) {
                                $sql .= "{$_k}=?,";
                                $val[] = $_val;
                            }
                        }
                    }
                }
                
                if ($sql != "") {
                    $sql = substr($sql, 0, strlen($sql) - 1);
                }
                if (isset($_data[$_key])) {
                    $val[] = $_data[$_key];
                } else {
                    return Bfw::RetMsg(true, "主键{$_key}值必须传");
                }
                $sql = "UPDATE {$_tablename} SET " . $sql . " WHERE {$_key}=?";
                // die($sql.var_export($val,true));
                BoDebug::Info($sql);
                $issuccess = $this->_connection->prepare($sql)->execute($val);
                if ($issuccess) {
                    return Bfw::RetMsg(false, true);
                } else {
                    return Bfw::RetMsg(false, false);
                }
            } else {
                return Bfw::RetMsg(true, "数据不能为空");
            }
        } catch (\PDOException $e) {
            
            throw new DbException($e->getMessage());
        }
    }

    public function delete($_id, $_tablename, $_key)
    {
        try {
            $_singledata = $this->single($_id, $_key, $_tablename, $_key);
            if ($_singledata['err']) {
                return $_singledata;
            }
            if ($_singledata['data'] == null) {
                return Bfw::RetMsg(true, "data_null");
            }
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if (empty($_id)) {
                return Bfw::RetMsg(true, "主键空值");
            }
            $sql = "DELETE FROM  {$_tablename} WHERE {$_key}=:id";
            BoDebug::Info($sql);
            $issuccess = $this->_connection->prepare($sql)->execute(array(
                ':id' => $_id
            ));
            if ($issuccess) {
                return Bfw::RetMsg(false, true);
            } else {
                return Bfw::RetMsg(false, false);
            }
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function count($_tablename, $_wherestr, $_wherearr)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            
            $sql_count = "SELECT count(*) as num FROM {$_tablename} WHERE 1=1 ";
            if ("" != trim($_wherestr) && ! is_null($_wherestr)) {
                
                $sql_count = $sql_count . " AND " . $_wherestr;
            }
            
            $_lcount = 0;
            BoDebug::TickStart($sql_count);
            $stmt = $this->_connection->prepare($sql_count);
            $stmt->execute($_wherearr);
            while (($row = $stmt->fetch(\PDO::FETCH_NUM)) != false) {
                $_lcount = $row[0];
            }
            BoDebug::TickStop($sql_count);
            return Bfw::RetMsg(false, $_lcount);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function listdata($_tablename, $_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            
            $sql = "SELECT {$_field} FROM {$_tablename} WHERE 1=1 ";
            $sql_count = "SELECT count(*) as num FROM {$_tablename} WHERE 1=1 ";
            if ("" != trim($_wherestr) && ! is_null($_wherestr)) {
                $sql = $sql . " AND " . $_wherestr . " " . $_orderby;
                $sql_count = $sql_count . " AND " . $_wherestr;
            } else {
                $sql = $sql . $_orderby;
            }
            if ($_pagesize != null && $_pagesize != "") {
                if ($_page == "" || $_page == null) {
                    $_page = 0;
                }
                $_pagenum = $_pagesize * $_page;
                $sql = $sql . " LIMIT {$_pagenum},{$_pagesize};";
            }
            // BoDebug::Info($sql);
            // echo $sql;
            BoDebug::TickStart($sql);
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute($_wherearr);
            $_ldata = array();
            while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) != false) {
                $_ldata[] = $row;
            }
            BoDebug::TickStop($sql);
            $_lcount = 0;
            if ($_needcount) {
                BoDebug::Info($sql_count);
                $stmt = $this->_connection->prepare($sql_count);
                $stmt->execute($_wherearr);
                while (($row = $stmt->fetch(\PDO::FETCH_NUM)) != false) {
                    $_lcount = $row[0];
                }
            }
            return Bfw::RetMsg(false, array(
                "count" => $_lcount,
                "data" => $_ldata
            ));
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function begintrans()
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if ($this->_intrans) {
                return true;
            } else {
                $this->_intrans = true;
                return $this->_connection->beginTransaction();
            }
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function commit()
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if ($this->_intrans) {
                $this->_intrans = false;
                return $this->_connection->commit();
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function rollback()
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            if ($this->_intrans) {
                $this->_intrans = false;
                return $this->_connection->rollBack();
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function executeNonquery($_sql, $_val)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            BoDebug::Info($_sql);
            $issuccess = $this->_connection->prepare($_sql)->execute($_val);
            if ($issuccess) {
                return Bfw::RetMsg(false, true);
            } else {
                return Bfw::RetMsg(false, false);
            }
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function executereader($_sql, $_val)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            BoDebug::Info($_sql);
            $stmt = $this->_connection->prepare($_sql);
            $stmt->execute($_val);
            $_ldata = array();
            while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) != false) {
                $_ldata[] = $row;
            }
            return Bfw::RetMsg(false, $_ldata);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    public function setisolevel($level)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            $isolevel = "REPEATABLE READ";
            switch ($level) {
                case 1:
                    $isolevel = "READ UNCOMMITTED";
                    break;
                case 2:
                    $isolevel = "READ COMMITTED";
                    break;
                case 3:
                    $isolevel = "REPEATABLE READ";
                    break;
                case 4:
                    $isolevel = "SERIALIZABLE";
                    break;
            }
            return $this->_connection->query("SET SESSION TRANSACTION ISOLATION LEVEL {$isolevel};");
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    /**
     * 获取数据库表
     */
    public function GetDbTable()
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            
            $stmt = $this->_connection->prepare("show table status;");
            $stmt->execute(null);
            $_ldata = array();
            while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) != false) {
                // var_dump($row);
                $_ldata[] = array(
                    "name" => $row['Name'],
                    "memo" => $row['Comment']
                );
                // $_ldata[] = array_values($row)[0];
            }
            return Bfw::RetMsg(false, $_ldata);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    /**
     * 获取表信息
     *
     * @param string $_tablename            
     */
    public function GetTableInfo($_tablename)
    {
        try {
            if (is_null($this->_connection)) {
                return Bfw::RetMsg(true, "数据库连接失败");
            }
            // echo($_tablename);
            
            $stmt = $this->_connection->prepare("show full fields from $_tablename;");
            
            $stmt->execute(null);
            $_ldata = array();
            while (($row = $stmt->fetch(\PDO::FETCH_ASSOC)) != false) {
                $_ldata[] = array(
                    "name" => $row['Field'],
                    "memo" => $row['Comment'],
                    "key" => $row['Key']
                );
                // var_dump($row);
                // $_ldata[] =array_values($row)[0];
            }
            
            return Bfw::RetMsg(false, $_ldata);
        } catch (\PDOException $e) {
            throw new DbException($e->getMessage());
        }
    }

    function __destruct()
    {
        $this->_connection = null;
    }
}

?>