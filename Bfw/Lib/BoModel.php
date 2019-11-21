<?php
namespace Lib;

use Lib\Bfw;
use Lib\Db\DbFactory;
use Lib\Util\ArrayUtil;
use Lib\Exception\DbException;

/**
 *
 * @author wangbo
 *         模型类
 */
class BoModel
{

    /**
     * 模型与表的映射，不要前缀
     *
     * @var array
     */
    private $_model_table_map;

    protected $_cacheseconds = 0;

    private $_getrelatefields="";

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
     * 别名
     *
     * @var string
     */
    private $_alias = "";

    /**
     * 条件传值
     *
     * @var array
     */
    private $_wherearr;

    /**
     * union连接数组
     *
     * @var array
     */
    private $_unionarr;

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
     * 是否锁表
     *
     * @var bool
     */
    protected $_locktable = false;

    /**
     * 分页大小
     *
     * @var number
     */
    private $_pagesize;

    protected $_isview = false;

    protected $_cpsql = "";
    // 相关key
    protected $_relatekey = [];

    protected $_fields = [];

    protected $_getrelatedata = false;

    protected $_getrelatemodel = [];

    protected $_relatedatalimit = 10;

    protected $_initrecords = [];

    protected $_relatedatainfo = [];

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
        if ($this->_isview && $this->_cpsql != "") {
            $this->_tablename = " (" . $this->GetTableNameByTag($this->_cpsql) . " ) ";
        } else {
            $_conf = BoConfig::ConfigGet("Config", "App");
            if (isset($_conf['dbmap'])) {
                $this->_model_table_map = $_conf['dbmap'];
            }
            // $this->_tbpre = Bfw::Config("Db", "tbpre");
            $_m_n = str_replace("App\\" . DOMIAN_VALUE . "\\Model\\Model_", "", get_class($this));

            if (isset($this->_model_table_map[$_m_n])) {
                $this->_tablename = $this->_model_table_map[$_m_n];
            } else {
                if ($this->_tbpre == null) {
                    $this->_tbpre = TB_PRE;
                }
                if (isset($_conf['tb_name_ci']) && $_conf['tb_name_ci'] == true) {
                    // $this->_tablename = $this->_tbpre . $_m_n;
                    $_m_n = strtolower($_m_n);
                }
                $this->_tablename = $this->_tbpre . $_m_n;
            }
        }


        // 如果开启自动创建数据库
        if (DB_FROM_MODEL&&! empty($this->_fields)) {

            if (isset($this->_connarray) && $this->_connarray['dbtype'] == 'DbMysql') {
                $_sql = "SELECT COLUMN_NAME,COLUMN_TYPE,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,IS_NULLABLE,COLUMN_DEFAULT, COLUMN_COMMENT  FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA='{$this->_connarray['dbname']}' and table_name  = '{$this->_tablename}'";
                BoDebug::Info("show table field {$_sql}");
                $_columndata = $this->ExecuteReader($_sql, null);
                // var_dump($_columndata);

                $_prisql = "SELECT k.column_name FROM information_schema.table_constraints t JOIN information_schema.key_column_usage k USING (constraint_name,table_schema,table_name) WHERE t.constraint_type='PRIMARY KEY' AND t.table_schema='{$this->_connarray['dbname']}' AND t.table_name='{$this->_tablename}'";

                $_pridata = $this->ExecuteReader($_prisql, null);
                // var_dump($_pridata);
                $_prikey = [];
                if (! $_pridata['err'] && ! empty($_pridata['data'])) {
                    foreach ($_pridata['data'] as $item) {
                        $_prikey[] = $item['column_name'];
                    }
                }

                if (! $_columndata['err'] && ! empty($_columndata['data'])) {
                    BoDebug::Info("compare table field");
                    $_alertsql = "";
                    $_localprikey = [];
                    // 进行对比
                    foreach ($this->_fields as $item) {
                        if (isset($item['prikey'])) {
                            $_localprikey[] = $item['name'];
                        }
                        $_findcol = false;
                        foreach ($_columndata['data'] as $citem) {
                            if ($citem['COLUMN_NAME'] == $item['name']) {
                                $_findcol = $citem;
                                break;
                            }
                        }
                        // 如果找到了
                        if ($_findcol) {
                            if ($_findcol['DATA_TYPE'] != $item['type']) {
                                if ($item['type'] == "int" || $item['type'] == "varchar") {
                                    $_alertsql .= " MODIFY COLUMN `{$item['name']}`  {$item['type']}({$item['length']}) " . (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . " ,";
                                } else {
                                    $_alertsql .= " MODIFY COLUMN `{$item['name']}`  {$item['type']} " . (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . " ,";
                                }
                            }
                        } else {
                            // 没找到就是新增
                            if ($item['type'] == "int" || $item['type'] == "varchar") {
                                $_alertsql .= " ADD COLUMN `{$item['name']}`  {$item['type']}({$item['length']}) NULL " . (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                            } else {
                                $_alertsql .= " ADD COLUMN `{$item['name']}`  {$item['type']} NULL " . (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                            }
                        }
                        // DROP PRIMARY KEY,
                        // ADD PRIMARY KEY (`id`, `testa`);
                    }
                    if (! empty($_prikey) && $_prikey != $_localprikey) {
                        $_alertsql .= " DROP PRIMARY KEY, ADD PRIMARY KEY (" . implode(",", $_localprikey) . "); ";
                    }
                    if ($_alertsql != "") {

                        $_alertsql = rtrim($_alertsql, ",");
                        $_alertsql = " ALTER TABLE `{$this->_tablename}` " . $_alertsql;
                        BoDebug::Info("alert table {$_alertsql}");
                        $_ret = $this->ExecuteNonQuery($_alertsql, null);
                        if ($_ret['err']) {
                            BoDebug::Info("alert table result:" . $_ret['data']);
                        }
                    }
                } else {
                    $_createsql = "  CREATE TABLE `$this->_tablename` ( ";
                    $_createprikey = "";

                    foreach ($this->_fields as $item) {
                        $_auto_inc = false;
                        if (isset($item['prikey'])) {
                            $_createprikey = $item['name'];
                        }
                        if (isset($item['autoinc'])) {
                            $_auto_inc = $item['autoinc'];
                        }
                        if ($item['type'] == "int" || $item['type'] == "varchar") {
                            $_createsql .= " `{$item['name']}`  {$item['type']}({$item['length']}) NOT NULL " . ($_auto_inc ? "AUTO_INCREMENT" : "") . " ";
                            if ($_auto_inc) {
                                $_createsql .= (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                            } else {
                                $_createsql .= (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                            }
                        } else {

                            $_createsql .= " `{$item['name']}`  {$item['type']} NOT NULL " . ($_auto_inc ? "AUTO_INCREMENT" : "") . " ";

                            if ($_auto_inc) {
                                $_createsql .= (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                            } else {
                                if ($item['type'] != "text") {
                                    $_createsql .= (isset($item['default']) ? "DEFAULT '{$item['default']}'" : "") . "" . (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                                } else {
                                    $_createsql .= (isset($item['comment']) ? "COMMENT '{$item['comment']}'" : "") . ",";
                                }
                            }
                        }
                    }
                    if ($_createprikey != "") {
                        $_createsql .= "PRIMARY KEY (`{$_createprikey}`)";
                    }
                    $_createsql .= ")ENGINE = INNODB;";

                    BoDebug::Info("create table {$_createsql}");
                    $_ret = $this->ExecuteNonQuery($_createsql, null);
                    if ($_ret['err']) {
                        BoDebug::Info("create table result:" . $_ret['data']);
                    }
                    if (! empty($this->_initrecords)) {
                        if (is_array($this->_initrecords)) {

                            foreach ($this->_initrecords as $record) {
                                $ret = $this->Insert($record);
                                BoDebug::Info("init table record:" . $ret['data']);
                            }
                        }
                    }
                }
            }
        }
    }

    private function connect(){
        if ($this->_dbhandle == null) {
            if (isset($this->_connarray)) {
                $this->_dbhandle = DbFactory::GetInstance($this->_connarray);
            } else {
                $this->_dbhandle = DbFactory::GetInstance();
            }
        }
    }

    /**
     * 重置变量，防止单例问题
     */
    public function reset()
    {
        $this->_joinarr = [];
        $this->_wherearr = [];
        $this->_unionarr = [];
        $this->_wherestr = "";
        $this->_orderstr = "";
        $this->_fieldstr = "*";
        $this->_getrelatemodel = [];
        $this->_relatedatalimit = 10;
        $this->_getrelatedata = false;
        $this->_getrelatefields="";
        return $this;
    }

    /**
     * 关联其他模型一起输出
     *
     * @param array $_model
     *            模型
     * @param int $_limit
     *            一对多加载数据条数
     */
    public function Relate($_model = [], $_limit = 10,$_field="")
    {
        $this->_getrelatemodel = $_model;
        $this->_relatedatalimit = $_limit;
        $this->_getrelatedata = true;
        $this->_getrelatefields=$_field;
        return $this;
    }

    /**
     * in查询
     *
     * @param 字段 $_field
     * @param 字段值数组 $_arr
     * @return \Lib\RetMsg
     */
    public function In($_field, $_arr)
    {
        if (is_string($_arr)) {
            $_arr = explode(",", $_arr);
        }
        if (is_array($_arr) && ! empty($_arr)) {
            $_strarr = array_fill(0, count($_arr), "?");
            $_instr = implode(",", $_strarr);
            return $this->Where("{$_field} in ({$_instr})", $_arr)->Select();
        }
        return Bfw::RetMsg(true, "err,in 参数无值");
    }

    private function relatesql()
    {
        if ($this->_getrelatedata && ! empty($this->_relatekey)) {
            // 筛选相关数据
            $_sel_keys = [];
            if (! empty($this->_getrelatemodel)) {

                foreach ($this->_relatekey as $item) {
                    if (in_array($item['model'], $this->_getrelatemodel)) {
                        $_sel_keys[] = $item;
                    }
                }
            } else {
                $_sel_keys = $this->_relatekey;
            }

            $_aliaschar = [
                "b",
                "c",
                "d",
                "e",
                "f",
                "i",
                "j",
                "k",
                "l",
                "m",
                "n"
            ];
            $i = 0;
            foreach ($_sel_keys as $item) {
                $this->Alias("a");
                if ($item['way'] == "1:1") {
                    $this->Join($item['model'], "{$_aliaschar[$i]}.{$item['foreignkey']}=a.{$item['key']}", "b", "left");
                }
                if ($item['way'] == "1:n") {
                    if($this->_getrelatefields!=""){
                        $item['foreignfield']=$this->_getrelatefields;
                    }
                    $this->_relatedatainfo[] = $item;
                }
                if ($item['way'] == "n:n") {
                    if($this->_getrelatefields!=""){
                        $item['foreignfield']=$this->_getrelatefields;
                    }
                    $this->_relatedatainfo[] = $item;
                }
                $i ++;
            }
        }
    }

    private function getrelatedata(&$_data)
    {
        foreach ($_data as &$item) {

            // var_dump($this->_relatedatainfo);
            foreach ($this->_relatedatainfo as $relateinfo) {
                if (isset($item[$relateinfo["key"]])) {
                    if (! isset($relateinfo['foreignfield'])) {
                        $relateinfo['foreignfield'] = "*";
                    }
                    // 如果是多对多
                    $_subdata = [
                        'err' => true,
                        "data" => "no data"
                    ];
                    if ($relateinfo['way'] == "n:n") {
                        if (isset($relateinfo['midmodel'])) {
                            $_d = Core::LoadClass("{$relateinfo['dom']}\\Model\\Model_{$relateinfo['model']}");
                            $_d = Core::LoadClass("{$relateinfo['dom']}\\Model\\Model_{$relateinfo['midmodel']}");

                            $_subdata = $_d->Cache($this->_cacheseconds)->ExecuteReader("select nk.{$relateinfo['foreignfield']} from [{$relateinfo['midmodel']}] as ny left join [{$relateinfo['model']}] as nk on nk.{$relateinfo['foreignkey']} =ny.{$relateinfo['midforeignkey']} where ny.{$relateinfo["midkey"]}=? limit 0,{$this->_relatedatalimit};", [
                                $item[$relateinfo["key"]]
                            ]);
                        }
                    }
                    if ($relateinfo['way'] == "1:n") {
                        $_d = Core::LoadClass("{$relateinfo['dom']}\\Model\\Model_{$relateinfo['model']}");
                        $_subdata = $_d->Cache($this->_cacheseconds)->ExecuteReader("select {$relateinfo['foreignfield']} from [{$relateinfo['model']}] where {$relateinfo["foreignkey"]}=? limit 0,{$this->_relatedatalimit};", [
                            $item[$relateinfo["key"]]
                        ]);
                        // $relateinfo['midmodel']
                    }

                    // if($relateinfo['way']=="1:1"){
                    // $_subdata=$this->ExecuteReader("select {$relateinfo['foreignfield']} from [{$relateinfo['model']}] where {$relateinfo["foreignkey"]}=? limit 0,1;",[$item[$relateinfo["key"]]]);
                    // //$relateinfo['midmodel']
                    // }
                    if (! $_subdata['err']) {
                        if (isset($relateinfo['dataformate'])) {
                            // 转换成一维数组,
                            if ($relateinfo['dataformate'] == "onedim") {
                                $_arr = [];

                                foreach ($_subdata['data'] as $items) {
                                    $sitems = array_values($items);
                                    if (isset($sitems[0])) {
                                        $_arr[] = $sitems[0];
                                    }
                                }
                                $item["relate_" . $relateinfo["model"]] = $_arr;
                                // return;
                            }
                            // 拼接字符串 必须保证字段只有一个
                            if ($relateinfo['dataformate'] == "string") {
                                $_arr = [];
                                foreach ($_subdata['data'] as $items) {
                                    $sitems = array_values($items);
                                    if (isset($sitems[0])) {
                                        $_arr[] = $sitems[0];
                                    }
                                }
                                $item["relate_" . $relateinfo["model"]] = implode(",", $_arr);
                                // return;
                            }
                        } else {
                            $item["relate_" . $relateinfo["model"]] = $_subdata['data'];
                        }
                    }
                }
            }
        }
    }

    /**
     * 切换数据库源，分布式
     *
     * @param string $_table
     * @param string $_prekey
     * @param string $_isview
     * @param string $_connarray
     * @param string $_tablemap
     */
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

    /**
     * 数据连接切换回来
     */
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
            $this->connect();
            return ! $this->_isview ? $this->_dbhandle->insert($_data, $this->_tablename, $_returnid) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 插入一条数据，有就更新,主键不能为数据库自增
     *
     * @param array $_data
     *
     *
     */
    public function InsertUpdate($_data)
    {
        try {
            $this->connect();
            return ! $this->_isview ? $this->_dbhandle->insertupdate($_data, $this->_tablename, $this->_prikey) : Bfw::RetMsg(true, "view not supported");
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
            $this->connect();
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
            $this->connect();
            return ! $this->_isview ? $this->_dbhandle->delete($_id, $this->_tablename, $this->_prikey) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 批量更新
     *
     * @param array $_wherestr
     * @param array $_wherearr
     * @param array $_data
     */
    private function MUpdate($_wherestr, $_wherearr, $_data)
    {
        try {
            $this->connect();
            return ! $this->_isview ? $this->_dbhandle->mutiupdate($_wherestr, $_wherearr, $this->_tablename, $_data) : Bfw::RetMsg(true, "view not supported");
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 批量删除
     *
     * @param array $_wherestr
     * @param array $_wherearr
     */
    private function MDelete($_wherestr, $_wherearr)
    {
        try {
            $this->connect();
            return ! $this->_isview ? $this->_dbhandle->mutidelete($_wherestr, $_wherearr, $this->_tablename) : Bfw::RetMsg(true, "view not supported");
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
            $_data = [
                'err' => true,
                "data" => "no data"
            ];
            // 如果锁表 关联查询和 缓存失效
            if ($this->_locktable) {
                $this->connect();
                return $this->_dbhandle->single($_id, $_field, $this->_tablename, $this->_prikey, $this->_locktable);
            }
            if ($this->_getrelatedata) {
                // 如果是关联查询，那么lock就失效
                $_getdata = $this->Field($_field)
                    ->PageNum(0)
                    ->PageSize(1)
                    ->Where("{$this->_prikey}=?", [
                    $_id
                ])
                    ->Select();
                $_data = [
                    'err' => $_getdata['err'],
                    "data" => $_getdata['data'][0]
                ];
            } else {

                $_cacheable = false;
                $_cachekey = "";
                if ($this->_cacheseconds > 0) {
                    $_cachekey = "modelcache_" . md5(get_class($this) . "Single" . $this->_cacheseconds.$_field . $_id);
                    $_cacheval = BoCache::Cache($_cachekey);


                    if (! empty($_cacheval)) {
                        return unserialize($_cacheval);
                    } else {
                        $_cacheable = true;
                    }
                }
                $this->connect();
                $_data = $this->_dbhandle->single($_id, $_field, $this->_tablename, $this->_prikey, $this->_locktable);

                if ($_cacheable) {
                   // var_dump($_data);
                  //  echo $_cachekey;
                    BoCache::Cache($_cachekey, serialize($_data), $this->_cacheseconds);
                }
            }
            return $_data;
        } catch (DbException $e) {
            return Bfw::RetMsg(true, $e->getMessage());
        }
    }

    /**
     * 缓存 时间
     *
     * @param int $_expireseconds
     */
    function Cache($_expireseconds)
    {
        $this->_cacheseconds = $_expireseconds;
        return $this;
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
            $_cacheable = false;
            $_cachekey = "";
            if ($this->_cacheseconds > 0) {
                $_cachekey = "modelcache_" . md5(get_class($this) . "ListData" . $_field . $_wherestr . var_export($_wherearr, true) . $_pagesize . $_page . $this->_cacheseconds . $_orderby . var_export($_needcount, true));
                $_cacheval = BoCache::Cache($_cachekey);
                if (! empty($_cacheval)) {
                    return unserialize($_cacheval);
                } else {
                    $_cacheable = true;
                }
            }
            $this->connect();
            $_data = $this->_dbhandle->listdata($this->_tablename, $_field, $_wherestr, $_wherearr, $_pagesize, $_page, $_orderby, $_needcount);
            $this->relatesql();

            if (! empty($this->_relatedatainfo)) {
                $this->getrelatedata($_data['data']['data']);
            }

            if ($_cacheable) {
                BoCache::Cache($_cachekey, serialize($_data), $this->_cacheseconds);
            }
            return $_data;
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

            $_cacheable = false;
            $_cachekey = "";
            if ($this->_cacheseconds > 0) {
                $_cachekey = "modelcache_" . md5(get_class($this) . "Count" . $this->_cacheseconds . $_wherestr . var_export($_wherearr, true));
                $_cacheval = BoCache::Cache($_cachekey);
                if (! empty($_cacheval)) {
                    return unserialize($_cacheval);
                } else {
                    $_cacheable = true;
                }
            }
            $this->connect();
            $_data = $this->_dbhandle->count($this->_tablename, $_wherestr, $_wherearr);

            if ($_cacheable) {
                BoCache::Cache($_cachekey, serialize($_data), $this->_cacheseconds);
            }
            return $_data;
            // return
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
            $this->connect();
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
            $this->connect();
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
            $this->connect();
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
            $this->connect();
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
            $this->connect();
            $_sql = str_replace("[T]", $this->_tablename, $_sql);
            $_sql = $this->GetTableNameByTag($_sql);
            return $this->_dbhandle->executeNonquery($_sql, $_val);
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

            $_sql = str_replace("[T]", $this->_tablename, $_sql);
            $_sql = $this->GetTableNameByTag($_sql);
            $_cacheable = false;
            $_cachekey = "";

            if ($this->_cacheseconds > 0) {
                $_cachekey = "modelcache_" . md5(get_class($this) . "ExecuteReader" . $this->_cacheseconds . $_sql . var_export($_val, true));
                $_cacheval = BoCache::Cache($_cachekey);
                if (! empty($_cacheval)) {
                    return unserialize($_cacheval);
                } else {
                    $_cacheable = true;
                }
            }
            $this->connect();
            $_data = $this->_dbhandle->executereader($_sql, $_val);

            if ($_cacheable) {
                BoCache::Cache($_cachekey, serialize($_data), $this->_cacheseconds);
            }

            return $_data;
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
     * 锁表
     *
     * @return \Lib\BoModel
     */
    function Lock()
    {
        $this->_locktable = true;
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
    function Join($_model, $_on, $_alias, $_jointype = "left")
    {
        $this->_joinarr[] = array(
            "model" => $_model,
            "type" => $_jointype,
            "on" => $_on,
            "alias" => $_alias
        );
        return $this;
    }

    /**
     * 表的union操作
     *
     * @param string $_sql
     * @return \Lib\BoModel
     */
    function Union($_sql, $_isall = false)
    {
        $this->_unionarr[] = [
            $_sql,
            $_isall
        ];
        return $this;
    }

    function Alias($_str)
    {
        $this->_alias = $_str;
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
     * 批量删除
     */
    function MutiDelete()
    {
        return $this->MDelete($this->_wherestr, $this->_wherearr);
    }

    /**
     * 批量更新
     *
     * @param unknown $_data
     */
    function MutiUpdate($_data)
    {
        return $this->MUpdate($this->_wherestr, $this->_wherearr, $_data);
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
     * @return RetMsg ['err'=>bool,'data'=>[数组]]
     */
    function Select()
    {
        $this->relatesql();
        $_sql = "select " . $this->_fieldstr . " from ";

        $_sql .= $this->_tablename . " ";
        if ($this->_alias != "") {
            $_sql .= " as " . $this->_alias . " ";
        }
        if (is_array($this->_joinarr)) {
            foreach ($this->_joinarr as $_item) {
                $_sql .= " " . $_item['type'] . ' join ' . " " . $this->GetTableName($_item['model']) . " as " . $_item['alias'];
                $_sql .= " on " . $_item['on'];
            }
        }

        if (is_array($this->_unionarr)) {
            foreach ($this->_unionarr as $_item) {
                if (count($_item) == 2) {
                    if ($_item[1]) {
                        $_sql .= ' union  all ' . $_item[0];
                    } else {
                        $_sql .= ' union ' . $_item[0];
                    }
                }
            }
        }
        if (! empty($this->_wherestr)) {
            $_sql .= " where " . $this->_wherestr;
        }
        if (! empty($this->_orderstr)) {
            $_sql .= " order by " . $this->_orderstr;
        }
        if ($this->_page >= 0 && $this->_pagesize > 0) {
            $_sql .= " limit " . $this->_page * $this->_pagesize . "," . $this->_pagesize;
        }

        $_data = $this->ExecuteReader($_sql, $this->_wherearr);

        if (! empty($this->_relatedatainfo)) {
            $this->getrelatedata($_data['data']);
        }

        return $_data;
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
                $_sql .= " " . $_item['type'] . ' join ' . " " . $this->GetTableName($_item['model']) . " as " . $_item['alias'];
                $_sql .= " on " . $_item['on'];
            }
        }

        if (is_array($this->_unionarr)) {
            foreach ($this->_unionarr as $_item) {
                if (count($_item) == 2) {
                    if ($_item[1]) {
                        $_sql .= ' union  all ' . $_item[0];
                    } else {
                        $_sql .= ' union ' . $_item[0];
                    }
                }
            }
        }
        if (! empty($this->_wherestr)) {
            $_sql .= " where " . $this->_wherestr;
        }
        // return $this->GetTableNameByTag($_sql);
        $_ret = $this->ExecuteReader($_sql, $this->_wherearr);
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
        if (isset($this->_model_table_map[$_m])) {
            return $this->_model_table_map[$_m];
        }
        // $this->_tbpre = Bfw::Config("Db", "tbpre");
        if ($this->_tbpre == null) {
            $this->_tbpre = TB_PRE;
        }
        return $this->_tbpre . $_m;
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
        // if($method)
        if (substr($method, 0, 6) == "FindBy") {
            $_suff = substr($method, 6);
            if (count($arguments) == 2 && preg_match("/([A-Za-z]+)(And|Or)([A-Za-z]+)$/", $_suff, $match)) {
                $this->_wherestr = "  " . $match[1] . "=?  " . $match[2] . "  " . $match[3] . "=? ";
                $this->_wherearr = $arguments;
                die($this->_wherestr);
                return $this->Select();
            } else {
                return BfW::RetMsg(true, $method . " not defined");
            }
        }
        return BfW::RetMsg(true, $method . " not defined");
    }
}

?>