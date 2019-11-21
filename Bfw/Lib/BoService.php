<?php
namespace Lib;

use Lib\Bfw;

/**
 *
 * @author wangbo
 *         服务类
 */
class BoService
{

    public $_modelins = null;

    public function __construct()
    {
        if ($this->_modelins == null) {
            if (isset($this->_model) && $this->_model != "") {
                $modelname = "App\\" . DOMIAN_VALUE . "\\Model\\Model_" . $this->_model;
                $this->_modelins = $modelname::getInstance();
            }
        }
    }

    public function __destruct()
    {
        $this->_modelins = null;
    }

    public function Insert($_data, $_returnid = false)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Insert($_data, $_returnid) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function Update($_data)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Update($_data) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function Delete($_id)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Delete($_id) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function Single($_field, $_id)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Single($_field, $_id) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function ListData($_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount = true)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->ListData($_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function Count($_wherestr, $_wherearr)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Count($_wherestr, $_wherearr) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function BeginTrans()
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->BeginTrans() : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function SetIsoLevel($level)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->SetIsoLevel($level) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function Commit()
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->Commit() : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    public function RollBack()
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->RollBack() : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }

    // 执行sql
    // 返回是否执行成功
    protected function ExecuteNonQuery($_sql, $_val)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->ExecuteNonQuery($_sql, $_val) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }
    // 执行sql返回list数组
    protected function ExecuteReader($_sql, $_val)
    {
        if ($this->_modelins != null) {
            return $this->_modelins != null ? $this->_modelins->ExecuteReader($_sql, $_val) : Bfw::RetMsg(true, "model bind empty");
        }
        return Bfw::RetMsg(true, "Service 中没有设置关联model");
    }
    // 检测是否设置
    protected function isNullOrEmpty($p)
    {
        if (is_null($p) || ! isset($p) || $p == "") {
            return true;
        }
        return false;
    }

    /**
     * 算选过滤数组
     *
     * @param array $_filterarr
     * @param array $_data
     * @return unknown[][]
     */
    protected function filterData($_filterarr, &$_data)
    {
        $_ret = [];
        foreach ($_filterarr as $_item) {
            $_sub = [];
            foreach ($_item as $_key) {
                if (isset($_data[$_key])) {
                    $_sub[$_key] = $_data[$_key];
                }
            }
            $_ret[] = $_sub;
        }
        return $_ret;
    }

    /**
     * 判断数据结构
     *
     * @param array $_data
     * @param array $_datastruct
     */
    protected function checkDataStruct($_data, $_datastruct, $_notempty = false)
    {
        foreach ($_datastruct as $_elememt) {
            if (! isset($_data[$_elememt])) {
                return Bfw::RetMsg(true, 0);
            } else {
                if ($_notempty) {
                    if (trim($_data[$_elememt]) == "") {
                        return Bfw::RetMsg(true, 0);
                    }
                }
            }
        }
        return Bfw::RetMsg(false, true);
    }

    public function __call($method, $arguments)
    {
        // if($method.)
        return Bfw::RetMsg(true, $method . " not defined");
    }
}

?>