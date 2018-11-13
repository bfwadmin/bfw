<?php
namespace bfw\web\db;
require_once APP_ROOT . "/Lib/" . 'BoDb.php';

class DbMssql extends BoDb implements BoDbInterface
{

    protected $_connection = null;
    
    protected $_host = "127.0.0.1";

    protected $_dbname = "test";

    protected $_username = "sa";

    protected $_password = "";

    protected $_charset = "utf8";

    protected $_persistent = false;

    public function __construct()
    {
        try {
            $this->_connection = mssql_connect($this->_host, $this->_username, $this->_password);
            mssql_select_db($this->_dbname, $this->_connection);
            // var_dump($this->_connection);
        } catch (Exception $e) {
            // die($e->getMessage());
            LogR($e->getMessage());
        }
    }

    public function single($_id, $_field, $_tablename)
    {
        try {
            
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            
            $sql = "SELECT {$_field} FROM  {$_tablename} WHERE id=:id";
            $_ldata = null;
            $query_result = mssql_query($sql, $this->_connection);
            $Num = mssql_num_rows($query_result);
            for ($i = 0; $i < $Num; $i ++) {
                $row = mssql_fetch_array($query_result);
                $_ldata = $row;
            }
            mssql_free_result($query_result);
            
            return $this->RetMsg(false, $_ldata);
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function insert($_data, $_tablename, $_returnid = false)
    {
        // 以下待优化
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
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
                $issuccess = $this->_connection->prepare($sql)->execute($val);
                if ($issuccess) {
                    if ($_returnid) {
                        return $this->RetMsg(false, $this->_connection->lastInsertId());
                    } else {
                        return $this->RetMsg(false, true);
                    }
                } else {
                    return $this->RetMsg(false, false);
                }
            } else {
                return $this->RetMsg(true, "数据不能为空");
            }
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function update($_data, $_tablename)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            if (! empty($_data)) {
                
                $sql = "";
                $val = array();
                foreach ($_data as $_key => $_val) {
                    
                    if ($_val === null) {} else {
                        if (strtolower($_key) != "id") {
                            $sql .= "{$_key}=?,";
                            $val[] = $_val;
                        }
                    }
                }
                
                if ($sql != "") {
                    $sql = substr($sql, 0, strlen($sql) - 1);
                }
                if (isset($_data['id'])) {
                    $val[] = $_data['id'];
                } else {
                    return $this->RetMsg(true, "主键id值必须传");
                }
                $sql = "UPDATE {$_tablename} SET " . $sql . " WHERE id=?";
                
                $issuccess = $this->_connection->prepare($sql)->execute($val);
                if ($issuccess) {
                    return $this->RetMsg(false, true);
                } else {
                    return $this->RetMsg(false, false);
                }
            } else {
                return $this->RetMsg(true, "数据不能为空");
            }
        } catch (Exception $e) {
            
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function delete($_id, $_tablename)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            
            $sql = "DELETE FROM  {$_tablename} WHERE id=:id";
            $issuccess = $this->_connection->prepare($sql)->execute(array(
                ':id' => $_id
            ));
            if ($issuccess) {
                return $this->RetMsg(false, true);
            } else {
                return $this->RetMsg(false, false);
            }
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function listdata($_tablename, $_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
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
            
            $stmt = $this->_connection->prepare($sql);
            $stmt->execute($_wherearr);
            $_ldata = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $_ldata[] = $row;
            }
            $_lcount = 0;
            $stmt = $this->_connection->prepare($sql_count);
            $stmt->execute($_wherearr);
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $_lcount = $row[0];
            }
            return $this->RetMsg(false, array(
                "count" => $_lcount,
                "data" => $_ldata
            ));
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function begintrans()
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            $this->_connection->beginTransaction();
        } catch (PDOException $e) {
            LogR($e->getMessage());
        }
    }

    public function commit()
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            $this->_connection->commit();
        } catch (PDOException $e) {
            LogR($e->getMessage());
        }
    }

    public function rollback()
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            $this->_connection->rollBack();
        } catch (PDOException $e) {
            LogR($e->getMessage());
        }
    }

    public function executeNonquery($_sql, $_val)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            $issuccess = $this->_connection->prepare($_sql)->execute($_val);
            if ($issuccess) {
                return $this->RetMsg(false, true);
            } else {
                return $this->RetMsg(false, false);
            }
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function executereader($_sql, $_val)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
            }
            
            $stmt = $this->_connection->prepare($_sql);
            $stmt->execute($_val);
            $_ldata = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $_ldata[] = $row;
            }
            return $this->RetMsg(false, $_ldata);
        } catch (Exception $e) {
            LogR($e->getMessage());
            return $this->RetMsg(true, $e->getMessage());
        }
    }

    public function setisolevel($isolevel)
    {
        try {
            if (is_null($this->_connection)) {
                return $this->RetMsg(true, "数据库连接失败");
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
            $this->_connection->query("SET SESSION TRANSACTION ISOLATION LEVEL {$isolevel};");
        } catch (PDOException $e) {
            LogR($e->getMessage());
        }
    }
}

?>