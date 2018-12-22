<?php
namespace Lib\Db;

interface BoDbInterface
{

    public function insert($_data, $_tablename, $_returnid);

    public function update($_data, $_tablename,$_key);

    public function delete($_id, $_tablename,$_key);

    public function count($_tablename, $_wherestr, $_wherearr);

    public function listdata($_tablename, $_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount);

    public function single($_id, $_field, $_tablename,$_key,$_islock);

    public function begintrans();

    public function commit();

    public function rollback();

    public function executeNonquery($_sql, $_val);

    public function executereader($_sql, $_val);

    public function setisolevel($_level);

    public function GetDbTable();

    public function GetTableInfo($_tablename);
}

class BoDb
{

  
}
?>