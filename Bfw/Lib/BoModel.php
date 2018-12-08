<?php
namespace Lib;

use Lib\Bfw;
use Lib\Db\DbFactory;
use Lib\Util\ArrayUtil;
use Lib\Exception\DbException;

class BoModel
{

    /**
     * 模型与表的映射，不要前缀
     *
     * @var array
     */
    private $_model_table_map;

    /**
     * 验证数组
     *
     * @var array
     */
    protected $_validate_array = null;

    /**
     * 主键，可在子类重设
     *
     * @var string
     */
    protected $_prikey = "id";

    /**
     * 条件
     *
     * @var string
     */
    private $_wherestr = "";

    /**
     * 条件传值
     *
     * @var array
     */
    private $_wherearr;

    /**
     * 排序语句
     *
     * @var string
     */
    private $_orderstr;

    /**
     * join 数组
     *
     * @var array
     */
    private $_joinarr;

    /**
     * 字段
     *
     * @var string
     */
    private $_fieldstr = "*";

    /**
     * 第几页 0为第一页
     *
     * @var number
     */
    private $_page;

    /**
     * 表名
     *
     * @var string
     */
    protected $_tablename = "";
    /**
     * 前缀
     *
     * @var string
     */
    protected $_tbpre = "";
    /**
     * 分页大小
     *
     * @var number
     */
    private $_pagesize;

    protected $_isview = false;

    /**
     * 数据库处理实例
     *
     * @var object
     */
    protected $_dbhandle = null;

    protected $_olddbhandle = array();

    protected $_oldtablename = array();

    protected $_oldprikey = array();

    protected $_oldisview = array();
    
    

    public function __construct()
    {
        $this->_model_table_map = Bfw::Config("Db", "map");
        $this->_tbpre = Bfw::Config("Db", "tbpre");
        $_m_n = str_replace("App\\" . DOMIAN_VALUE . "\\Model\\Model_", "", get_class($this));
        if (isset($this->_model_table_map[$_m_n])) {
            $this->_tablename = $this->_model_table_map[$_m_n];
        } else {
            if( $this->_tbpre ==null){
                $this->_tbpre = TB_PRE ;
            }
            $this->_tablename = $this->_tbpre . $_m_n;
         
        }
        if ($this->_dbhandle == null) {
            if (isset($this->_connarray)) {
                $this->_dbhandle = DbFactory::GetInstance($this->_connarray);
            } else {
                $this->_dbhandle = DbFactory::GetInstance();
            }
        }
    }

    public function ChangeDb($_table = "", $_prekey = "", $_isview = false, $_connarray = null, $_tablemap = false)
    {
        array_push($this->_oldisview, $this->_isview);
        $this->_isview = $_isview;
        if ($_connarray != null) {
            array_push($this->_olddbhandle, $this->_dbhandle);
            // $this->_olddbhandle = $this->_dbhandle;
            $this->_dbhandle = DbFactory::GetInstance($_connarray);
        }
        
        if ($_table != "") {
            array_push($this->_oldtablename, $this->_tablename);
            // $this->_oldtablename = $this->_tablename;
            if ($_tablemap) {
                $this->_tablename = $this->GetTableName($_table);
            } else {
                $this->_tablename = $_table;
            }
        }
        if ($_prekey != "") {
            array_push($this->_oldprikey, $this->_prikey);
            // $this->_oldprikey = $this->_prekey;
            $this->_prikey = $_prekey;
        }
    }

    public function ChangeBack()
    {
        if (! empty($this->_oldisview)) {
            $this->_isview = array_pop($this->_oldisview);
        }
        
        if (! empty($this->_oldprikey)) {
            $this->_prikey = array_pop($this->_oldprikey);
        }
        if (! empty($this->_oldtablename)) {
            $this->_tablename = array_pop($this->_oldtablename);
        }
        if (! empty($this->_olddbhandle)) {
            $this->_dbhandle = array_pop($this->_olddbhandle);
            // $this->_olddbhandle = null;
        }
    }

    public function __destruct()
    {
        $this->_dbhandle = null;
        if (! empty($this->_olddbhandle)) {
            foreach ($this->_olddbhandle as $_handle) {
                $_handle = null;
            }
        }
    }

    /**
     * 插入一条数据
     *
     * @param array $_data            
     * @param string $_returnid
     *            是否返回主键id
     */
    public function Insert($_data, $_returnid = false)
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->insert($_data, $this->_tablename, $_returnid) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 更新一条记录
     *
     * @param array $_data            
     */
    public function Update($_data)
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->update($_data, $this->_tablename, $this->_prikey) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 删除一条记录
     *
     * @param
     *            string or number $_id 主键
     */
    public function Delete($_id)
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->delete($_id, $this->_tablename, $this->_prikey) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 返回一条数据
     *
     * @param string $_field
     *            字段
     * @param
     *            numnber or string $_id 主键
     */
    public function Single($_field, $_id)
    {
        try {
            return $this->_dbhandle->single($_id, $_field, $this->_tablename, $this->_prikey);
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 分页加载数据列表
     *
     * @param string $_field
     *            字段
     * @param string $_wherestr
     *            条件
     * @param array $_wherearr
     *            条件传值
     * @param number $_pagesize
     *            分页大小
     * @param number $_page
     *            第几页
     * @param string $_orderby
     *            排序
     * @param bool $_needcount
     *            是否获取总数，便于分页
     */
    public function ListData($_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount = true)
    {
        try {
            return $this->_dbhandle->listdata($this->_tablename, $_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount);
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 获取总数
     *
     * @param string $_wherestr            
     * @param array $_wherearr            
     * @return number
     */
    public function Count($_wherestr, $_wherearr)
    {
        try {
            return $this->_dbhandle->count($this->_tablename, $_wherestr, $_wherearr);
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 开始进行事务处理
     *
     * @return bool
     */
    public function BeginTrans()
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->begintrans() : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 设置事务隔离级别
     *
     * @param number $level            
     * @return bool
     */
    public function SetIsoLevel($level)
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->setisolevel($level) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 事务提交
     *
     * @return bool
     */
    public function Commit()
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->commit() : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 事务回滚
     *
     * @return bool
     */
    public function RollBack()
    {
        try {
            return ! $this->_isview ? $this->_dbhandle->rollback() : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 执行sql语句
     *
     * @param string $_sql            
     * @param array $_val            
     * @return RetMsg
     */
    public function ExecuteNonQuery($_sql, $_val = null)
    {
        try {
            return $this->_dbhandle->executeNonquery(str_replace("[T]", $this->_tablename, $_sql), $_val);
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     *
     * @param string $_sql            
     * @param array $_val            
     * @return RetMsg
     */
    public function ExecuteReader($_sql, $_val = null)
    {
        try {
            return $this->_dbhandle->executereader(str_replace("[T]", $this->_tablename, $_sql), $_val);
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 当前页
     *
     * @param number $_n            
     * @return BoModel
     */
    function PageNum($_n)
    {
        $this->_page = $_n;
        return $this;
    }

    /**
     * 分页大小
     *
     * @param number $_n            
     * @return BoModel
     */
    function PageSize($_n)
    {
        $this->_pagesize = $_n;
        return $this;
    }

    /**
     *
     * @param string $_str
     *            字段
     * @return BoModel
     */
    function Field($_str)
    {
        $this->_fieldstr = $_str;
        
        return $this;
    }

    /**
     * 条件
     *
     * @param string $_str
     *            条件
     * @param array $_arr
     *            传值
     * @return BoModel
     */
    function Where($_str, $_arr)
    {
        $this->_wherestr = $_str;
        $this->_wherearr = $_arr;
        return $this;
    }

    /**
     * join操作
     *
     * @param string $_model
     *            模型名称
     * @param string $_jointype
     *            join类型 分为left与inner
     * @param string $_on
     *            on条件
     * @return BoModel
     */
    function Join($_model, $_jointype = "left", $_on)
    {
        $this->_joinarr[] = array(
            "model" => $_model,
            "type" => $_jointype,
            "on" => $_on
        );
        return $this;
    }

    /**
     * 从对象
     *
     * @param string $_m            
     * @return BoModel
     */
    function From($_m)
    {
        $this->_tablename = $_m;
        return $this;
    }

    /**
     *
     * @param 排序 $_str            
     * @return BoModel
     */
    function OrderBy($_str)
    {
        $this->_orderstr = $_str;
        return $this;
    }

    /**
     * 输出数据
     *
     * @return RetMsg
     */
    function Select()
    {
        $_sql = "select " . $this->_fieldstr . " from ";
        $_sql .= $this->_tablename . " ";
        if (is_array($this->_joinarr)) {
            foreach ($this->_joinarr as $_item) {
                $_sql .= " " . $_item['type'] . ' join ' . " " . $this->GetTableName($_item['model']);
                $_sql .= " on " . $_item['on'];
            }
        }
        if (! empty($this->_wherestr)) {
            $_sql .= " where " . $this->_wherestr;
        }
        if (! empty($this->_orderstr)) {
            $_sql .= " order by " . $this->_orderstr;
        }
        if (! empty($this->_page) && ! empty($this->_pagesize)) {
            $_sql .= " limit " . $this->_page . "," . $this->_pagesize;
        }
        // return $this->GetTableNameByTag($_sql);
        return $this->ExecuteReader($this->GetTableNameByTag($_sql), $this->_wherearr);
    }

    /**
     * 输出数量
     *
     * @return RetMsg
     */
    function Total()
    {
        $_sql = "select count(*) as num from ";
        
        $_sql .= $this->_tablename . " ";
        
        if (is_array($this->_joinarr)) {
            foreach ($this->_joinarr as $_item) {
                $_sql .= " " . $_item['type'] . " join " . $this->GetTableName($_item['model']);
                $_sql .= " on " . $_item['on'];
            }
        }
        if (! empty($this->_wherestr)) {
            $_sql .= " where " . $this->_wherestr;
        }
        // return $this->GetTableNameByTag($_sql);
        $_ret = $this->ExecuteReader($this->GetTableNameByTag($_sql), $this->_wherearr);
        if ($_ret['err']) {
            return $_ret;
        }
        
        return Bfw::RetMsg(false, $_ret['data'][0]['num']);
    }

    /**
     *
     * @param array $_data
     *            验证
     * @return RetMsg
     */
    function Validate(&$_data)
    {
        return ArrayUtil::Validate($_data, $this->_validate_array, $this);
    }

    /**
     * 模型名转表名
     *
     * @param string $_m            
     * @return string
     */
    private function GetTableName($_m)
    {
        // $_m = str_replace("App\\Model\\" . DOMIAN_VALUE . "\\M", "", $_m);
        if (isset($this->_model_table_map[$_m])) {
            return $this->_model_table_map[$_m];
        }
        
        return TB_PRE . $_m;
    }

    /**
     * 解析sql表名标签
     *
     * @param string $_m            
     * @return string
     */
    private function GetTableNameByTag($_m)
    {
        if (preg_match_all("/\\[([\\w]*?)\\]/", $_m, $match)) {
            for ($i = 0; $i < count($match[0]); $i ++) {
                $_m = str_replace($match[0][$i], $this->GetTableName($match[1][$i]), $_m);
            }
        }
        return $_m;
    }

    public function __call($method, $arguments)
    {
        return BfW::RetMsg(true, $method . " not defined");
    }
}

?>