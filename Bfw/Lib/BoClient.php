<?php
namespace Lib;

use Lib\Util\HttpUtil;
use Lib\Exception\HttpException;
use Lib\Util\StringUtil;
// import ( "Client." . DOMIAN_VALUE . ".config" );
/**
 *
 * @author wangbo
 *         调用类父类
 */
class BoClient
{

    private $_wherestr = null;

    private $_wherearr = null;

    private $_pagesize = 0;

    private $_page = 0;

    private $_orderstr = null;

    private $_field = "*";

    private $_cachetime = 0;

    private $_cacheasync = false;

    private $_cachemasterkey = "";

    private $_cachedependcy = null;

    private $_cachemaster = false;

    private $_base_serv_url = "";

    private $_base_serv_lang = "";

    private $_base_serv_key = "";

    private $_base_serv_dom = "";

    protected $_serv_url = null;

    protected $_service_remote = SERVICE_REMOTE;

    protected $_callfrombg = false;

    /**
     * 插入新数据
     * Insert(array('title'=>'ss',"content"=>"dd"),false)
     *
     * @param array $_data
     *            插入数据数组
     * @param bool $_returnid
     *            是否返回主键id
     * @return array(err=>bool,data=>data)
     */
    public function Insert($_data, $_returnid = false)
    {
        return $this->client_call("Insert", [
            $_data,
            $_returnid
        ]);
    }

    /**
     * 更新记录
     * Update(array('content','title'),array('title'));
     *
     * @param array $_data
     *            更新的数组
     * @param array $unupdatearray
     *            不必更新的数组
     * @return array(err=>bool,data=>data)
     */
    public function Update($_data, $unupdatearray = null)
    {
        if (is_array($unupdatearray)) {
            foreach ($unupdatearray as $val) {
                $_data[val] = null;
            }
        }
        return $this->client_call("Update", [
            $_data
        ]);
    }

    /**
     * 获取单挑记录
     * One("主键","*");
     *
     * @param string $id
     *            主键
     * @param string $field
     *            需要的字段
     * @return array(err=>bool,data=>data)
     */
    public function One($id, $field = "*")
    {
        return $this->service_call("Single", [
            $field,
            $id
        ]);
    }

    /**
     *
     * @return 获取selet单条记录
     */
    public function SelectOne()
    {
        $this->_pagesize = 1;
        $this->_page = 0;
        $_data = $this->Select(false);
        if ($_data['err']) {
            return $_data;
        }
        if (empty($_data['data']['data'])) {
            return Bfw::RetMsg(true, 'empty');
        }
        return Bfw::RetMsg(false, $_data['data']['data'][0]);
    }

    /**
     * 带缓存的One方法
     * Single(1,"*")
     *
     * @param string $id
     *            主键
     * @param string $_field
     *            需要的字段
     * @return array(err=>bool,data=>data)
     */
    public function Single($id, $_field = "*")
    {
        return $this->client_call("Single", [
            $_field,
            $id
        ]);
    }

    /**
     * 删除
     * Delete("主键");
     *
     * @param string $id
     *            主键
     * @return array(err=>bool,data=>data)
     */
    public function Delete($id)
    {
        return $this->client_call("Delete", [
            $id
        ]);
    }

    private function service_call($method, $arguments)
    {
        $_ret = [
            "err" => false,
            "data" => ""
        ];
        $method = str_replace("___", "", $method);

        if (! $this->_callfrombg) {
            $_servicename = str_replace("App\\" . DOMIAN_VALUE . "\\Client\\Client_", "", get_class($this));
            // 调用模式
            static $_config_arr = [];
            if (file_exists(APP_ROOT . DS . "App" . DS . "Config.php")) {
                include APP_ROOT . DS . "App" . DS . "Config.php";
            }
            // $_runservicename = SERVICE_DOMIAN_VALUE . "/" . $_servicename . "/" . str_replace("___", "", $method);
            $_runservicename = DOMIAN_VALUE . "_" . $_servicename . "_" . str_replace("___", "", $method);
            // die($_runservicename);// $_runservicename = $this->_domian . "_" . $this->_servicename . "_" . $method;
            $_cachekey = "calllimit_" . md5($_runservicename);
            // echo ($_runservicename);
            if (isset($_config_arr['Globle']["service"])) {
                $_service_conf = $_config_arr['Globle']["service"];
                if (isset($_service_conf[$_runservicename])) {
                    if (isset($_service_conf[$_runservicename]['limit'])) {
                        if (isset($_service_conf[$_runservicename]['type']) && $_service_conf[$_runservicename]['type'] == "session") {
                            $_cachekey .= SESS_ID;
                        }
                        if (isset($_service_conf[$_runservicename]['para'])) {
                            if (isset($arguments[$_service_conf[$_runservicename]['para']])) {
                                $_cachekey .= var_export($arguments[$_service_conf[$_runservicename]['para']], true);
                            }
                        }
                        // if (isset($_service_conf[$_runservicename]['type']) && $_service_conf[$_runservicename]['type'] != "queue") {
                        $_cachedata = BoCache::Cache($_cachekey); // 1分钟内最大访问次数
                        if (is_numeric($_cachedata) && $_cachedata > 0) {
                            if (time() - $_cachedata < 60 / $_service_conf[$_runservicename]['limit']) {
                                return array(
                                    "err" => true,
                                    "data" => "服务器忙,请稍后再试"
                                );
                            }
                        }
                        BoCache::Cache($_cachekey, time(), 60);
                        // }
                    }
                }
                if (isset($_service_conf[$_runservicename]['type']) && $_service_conf[$_runservicename]['type'] == "queue") {
                    $_queue = Core::LoadClass("Lib\\QUEUE\\" . QUEUE_HANDLER_NAME);
                    if ($_queue) {
                        $_reqid = StringUtil::guid();
                        if (isset($_service_conf[$_runservicename]['once'])) {
                            $_cachekey .= "once";
                            if (isset($arguments[$_service_conf[$_runservicename]['para']])) {
                                $_cachekey .= var_export($arguments[$_service_conf[$_runservicename]['para']], true);
                            }
                            $_oncedata = BoCache::Cache($_cachekey);
                            if (! empty($_oncedata)) {
                                return array(
                                    "err" => false,
                                    "data" => $_oncedata
                                );
                            } else {
                                BoCache::Cache($_cachekey, $_reqid, 0);
                            }
                        }
                        $_obj = serialize($this);
                        $_ret = $_queue->enqueue("service" . $_runservicename, [
                            "reqid" => $_reqid,
                            "oncekey" => $_cachekey,
                            'reqtime' => time(),
                            'reqmethod' => $method,
                            'reqargs' => $arguments,
                            "obj" => $_obj
                        ]);
                        return array(
                            "err" => false,
                            "data" => $_reqid
                        );
                    } else {
                        return array(
                            "err" => true,
                            "data" => "queue init err"
                        );
                    }
                }
                if (isset($_service_conf[$_runservicename]['type']) && $_service_conf[$_runservicename]['type'] == "lock") {
                    $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLER_NAME, $_cachekey);
                    if ($_lock) {
                        try {
                            $_begintime = microtime(true);
                            while (! $_lock->lock()) {
                                usleep(LOCK_WAIT_TIME * 1000000);
                                Bfw::Debug("LOCK WAIT:" . LOCK_WAIT_TIME . "s");
                                if (microtime(true) - $_begintime >= LOCK_TIMEOUT) {
                                    goto client_pass;
                                }
                            }
                            // usleep(10000000);
                            if ($this->_service_remote) {
                                $_ret = call_user_func_array([
                                    $this->Proxy(),
                                    $method
                                ], $arguments);
                            } else {
                                $_ret = call_user_func_array([
                                    $this->Service(),
                                    $method
                                ], $arguments);
                            }
                            $_lock->unlock();
                            return $_ret;
                            client_pass:
                            return array(
                                "err" => true,
                                "data" => "服务器很忙,请稍后再试"
                            );
                        } catch (\Exception $e) {
                            $_lock->unlock();
                            Bfw::LogToFile("lock exception:" . $e->getMessage());
                            return array(
                                "err" => true,
                                "data" => "服务器太忙,请稍后再试"
                            );
                        }
                    } else {
                        return array(
                            "err" => true,
                            "data" => "服务器忙不过来,请稍后再试"
                        );
                    }
                }
            }
        }

        if ($this->_service_remote) {
            $_ret = call_user_func_array([
                $this->Proxy(),
                $method
            ], $arguments);
        } else {
            $_ret = call_user_func_array([
                $this->Service(),
                $method
            ], $arguments);
        }
        return $_ret;
    }

    /**
     * 获取分页数据
     * ListData("获取字段","where条件","where值", "分页大小", "当前页")
     * ListData("*","title=?",array("good"), 10, 0);
     *
     * @param string $field
     *            字段
     * @param string $wherestr
     *            where条件
     * @param array $wherearr
     *            条件数组
     * @param number $pagesize
     *            分页大小
     * @param number $page
     *            当前页
     * @param string $orderby
     *            排序
     * @return array(err=>bool,data=>data)
     */
    public function ListData($field, $wherestr = null, $wherearr = null, $pagesize = null, $page = 0, $orderby, $needcount)
    {
        return $this->client_call("ListData", [
            $field,
            $wherestr,
            $wherearr,
            $pagesize,
            $page,
            $orderby,
            $needcount
        ]);
    }

    public function Count($wherestr = null, $wherearr = null)
    {
        $_ret= $this->client_call("Count", [
            $wherestr,
            $wherearr
        ]);
        $this->ClearLastdata();
        return $_ret;
    }

    /**
     * 字段筛选
     * $this->Field("id,name");
     *
     * @param string $field
     *            字段
     * @return BoClient
     */
    public function Field($field)
    {
        $this->_field = $field;
        return $this;
    }

    private function cacheMasterUpdate()
    {
        if ($this->_cachemaster) {
            if (is_array($this->_cachedependcy) && isset($this->_cachedependcy['type']) && isset($this->_cachedependcy['source'])) {
                if ($this->_cachedependcy['type'] == 'file') {
                    // BoCache::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                } elseif ($this->_cachedependcy['type'] == 'service' && is_array($this->_cachedependcy['source'])) {
                    // BoCache::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                } elseif ($this->_cachedependcy['type'] == 'cache') {
                    // 依赖cache的操作结果，如果本身是cache 每次检查，如果是写入操作的话，就要等client操作完再清空缓存
                    if (isset($this->_cachedependcy['method'])) {
                        if ($this->_cachedependcy['method'] == "read") {
                            // 如果是读
                        }

                        if ($this->_cachedependcy['method'] == "write") {
                            // 如果是写
                        }
                    }

                    // BoCache::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                }
            }
        }
    }

    private function checkCacheExpire($_keyname)
    {
        $_cacheexpire = false;
        $_go = false;
        if ($this->_cacheasync) {
            if ($this->_callfrombg) {
                if (! $this->_cachemaster) {
                    $_go = true;
                }
            }
        } else {
            if (! $this->_cachemaster) {
                $_go = true;
            }
        }
        if (! $this->_cachemaster) {
            if (is_array($this->_cachedependcy) && isset($this->_cachedependcy['type']) && isset($this->_cachedependcy['source'])) {
                if ($this->_cachedependcy['type'] == 'file') {
                    $_filefullname = APP_ROOT . DS . 'Data' . DS . $this->_cachedependcy['source'];
                    if (! file_exists($_filefullname)) {
                        BoDebug::LogR($_filefullname . BoConfig::Config("Sys", "cache", 'System')['dependcy_not_found']);
                    } else {
                        $_filemtimeval = filemtime($_filefullname);
                        if (BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre") != $_filemtimeval) {
                            BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre", $_filemtimeval, 0);
                            $_cacheexpire = true;
                        }
                    }
                } elseif ($this->_cachedependcy['type'] == 'service' && is_array($this->_cachedependcy['source'])) {
                    $_ret = call_user_func_array(array(
                        Core::LoadClass("App\\Client\\" . DOMIAN_VALUE . '\Client_' . $this->_cachedependcy['source'][0]),
                        $this->_cachedependcy['source'][1]
                    ), $this->_cachedependcy['source'][2]);

                    if ($_ret['err']) {
                        BoDebug::LogR($this->_cachedependcy['source'][0] . $_ret['data']);
                    } else {
                        if (BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . md5(var_export($this->_cachedependcy['source'], true)) . "_pre") != $_ret['data']) {
                            BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . md5(var_export($this->_cachedependcy['source'], true)) . "_pre", $_ret['data'], 0);
                            $_cacheexpire = true;
                        }
                    }
                } elseif ($this->_cachedependcy['type'] == 'cache') {
                    if (! is_array($this->_cachedependcy['source'])) {
                        $_sourcearr = explode(",", $this->_cachedependcy['source']);
                    } else {
                        $_sourcearr = $this->_cachedependcy['source'];
                    }
                    foreach ($_sourcearr as $_source) {
                        $_depencycacheval = BoCache::Cache(CACHE_DEPENDCY_PRE . $_source);
                        if ($_depencycacheval == null) {
                            BoCache::Cache(CACHE_DEPENDCY_PRE . $_source, time(), 0);
                            // Bfw::LogR($this->_cachedependcy['source'] . BoConfig::Config("Sys", "cache", 'System')['dependcy_not_found']);
                        } else {
                            if (BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . $_source . "_pre") != $_depencycacheval) {
                                BoCache::Cache($_keyname . CACHE_DEPENDCY_PRE . $_source . "_pre", $_depencycacheval, 0);
                                $_cacheexpire = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $_cacheexpire;
    }

    public function Total()
    {
        $_ret=$this->client_call("Count", [
            $this->_wherestr,
            $this->_wherearr
        ]);
        $this->ClearLastdata();
        return $_ret;
    }

    /**
     * where条件
     * $this->Where("id=?",array(1))
     *
     * @param string $wherestr
     *            条件语句
     * @param array $wherearr
     *            填充数组
     * @return BoClient
     */
    public function Where($wherestr = "", $wherearr = null)
    {
        $this->_wherestr = $wherestr;
        $this->_wherearr = $wherearr;
        return $this;
    }

    /**
     * 数据返回
     *
     * @param bool $_withtotal
     *            是否返回count数据，默认为true
     * @return array(err=>bool,data=>data)
     */
    public function Select($_withtotal = true)
    {
        $_ret= $this->client_call("ListData", [
            $this->_field,
            $this->_wherestr,
            $this->_wherearr,
            $this->_pagesize,
            $this->_page,
            $this->_orderstr,
            $_withtotal
        ]);
        $this->ClearLastdata();
        return $_ret;
    }

    /**
     * 数据缓存时间
     *
     * @param number $seconds
     *            秒
     * @param bool $async
     *            是否异步
     * @param string $_dependcy缓存依赖
     * @param bool $_ismaster
     *            是否是依赖主
     * @return BoClient
     */
    public function Cache($_seconds, $_async = false, $_dependcy = null, $_ismaster = false)
    {
        $this->_cachetime = $_seconds;
        $this->_cacheasync = $_async;
        $this->_cachedependcy = $_dependcy;
        $this->_cachemaster = $_ismaster;
        return $this;
    }

    public function CacheMaster($key)
    {
        $this->_cachemasterkey = $key;

        return $this;
    }

    public function ClearLastdata()
    {
        $this->_wherestr = null;

        $this->_wherearr = null;

        $this->_pagesize = 0;

        $this->_page = 0;

        $this->_orderstr = null;

        $this->_field = "*";

        $this->_cachetime = 0;

        $this->_cacheasync = false;

        $this->_cachemasterkey = "";

        $this->_cachedependcy = null;

        $this->_cachemaster = false;

        $this->_base_serv_url = "";

        $this->_base_serv_lang = "";

        $this->_base_serv_key = "";

        $this->_base_serv_dom = "";

        $this->_serv_url = null;

        $this->_service_remote = SERVICE_REMOTE;

        $this->_callfrombg = false;
    }

    public function ResetCache($_isdepency = true)
    {
        if ($this->_cachedependcy) {}

        //
        // $_keyname = "";
        // if ($_isdepency) {
        // $_keyname = CACHE_DEPENDCY_PRE . $this->_cachedependcy;
        // } else {
        // $_keyname = md5(get_class($this) . $this->_field . $this->_wherestr . serialize($this->_wherearr) . $this->_pagesize . $this->_page . $this->_orderstr . serialize($_withtotal));
        // }
        BoCache::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
        // Core::DelC($_keyname);
    }

    /**
     * 分页 大小设置
     *
     * @param number $p
     *            分页大小
     * @return BoClient
     */
    public function PageSize($p)
    {
        $this->_pagesize = $p;
        return $this;
    }

    /**
     * 当前页数设置
     *
     * @param number $p
     *            页数
     * @return BoClient
     */
    public function PageNum($p)
    {
        $this->_page = $p;
        return $this;
    }

    /**
     * 降序排序
     * $this->DescBy("id");
     *
     * @param string $f
     *            字段
     * @return BoClient
     */
    public function DescBy($f)
    {
        $this->_orderstr = " order by {$f} desc";
        return $this;
    }

    /**
     * 升序排序
     * $this->AscBy("id");
     *
     * @param string $f
     *            字段
     * @return BoClient
     */
    public function AscBy($f)
    {
        $this->_orderstr = " order by {$f} asc";
        return $this;
    }

    /**
     * 获取本地服务类
     */
    public function Service()
    {
        // $_servicename = str_replace("App\\Client\\" . DOMIAN_VALUE . "\\Client_", "", get_class($this));
        $_servicename = str_replace(DOMIAN_VALUE, SERVICE_DOMIAN_VALUE, str_replace("Client", "Service", get_class($this)));
        return $_servicename::getInstance();
    }

    /**
     * 代理请求
     *
     * @return BoTranClient
     */
    public function Proxy()
    {
        include_once APP_ROOT . '/Lib/BoTranClient.php';
        $_servicename = str_replace("App\\" . DOMIAN_VALUE . "\\Client\\Client_", "", get_class($this));
        if (empty($this->_serv_url)) {
            $this->_serv_url = $this->getserviceinfo($_servicename);
        }

        return BoTranClient::getInstance()->Init($this->_serv_url, DOMIAN_VALUE, $_servicename);
    }

    private function getserviceinfo($_servicename)
    {
        $_key = md5("dom_" . DOMIAN_VALUE . $_servicename);
        $_data = BoCache::Cache($_key);
        if (empty($_data)) {
            $_url = SERVICE_REG_CENTER_URL . "?dom=" . DOMIAN_VALUE . "&act=get&cont=service&sername=" . $_servicename . "&notifyurl=" . urlencode(SERVICE_NOTIFY_URL);

            $_data = HttpUtil::HttpGet($_url);
            if ($_data['err']) {
                throw new HttpException('get service list http err,' . $_data['data']);
            } else {

                $_json_data = json_decode($_data['data'], true);
                if (empty($_json_data)) {
                    throw new HttpException('get service list http err,empty data');
                }
                BoCache::Cache($_key, $_json_data, 0);
                return $_json_data;
            }
        } else {
            return $_data;
        }
    }

    public function RunBg($_istrue = true)
    {
        $this->_callfrombg = $_istrue;
    }

    private function checkcacheMaster($_cacheval, $_data)
    {
        if ($this->_cachemaster) {
            if (is_array($this->_cachedependcy) && isset($this->_cachedependcy['type']) && isset($this->_cachedependcy['source']) && isset($this->_cachedependcy['method'])) {
                if ($this->_cachedependcy['type'] == "cache") {
                    if ($this->_cachedependcy['method'] == "read") {
                        // 如果是读
                        if ($_cacheval != $_data) {
                            if (! is_array($this->_cachedependcy['source'])) {
                                $_sourcearr = explode(",", $this->_cachedependcy['source']);
                            } else {
                                $_sourcearr = $this->_cachedependcy['source'];
                            }

                            foreach ($_sourcearr as $_source) {
                                BoCache::Cache(CACHE_DEPENDCY_PRE . $_source, time(), 0);
                            }
                        }
                    }
                    if ($this->_cachedependcy['method'] == "write") {
                        // 如果是写
                        if (! is_array($this->_cachedependcy['source'])) {
                            $_sourcearr = explode(",", $this->_cachedependcy['source']);
                        } else {
                            $_sourcearr = $this->_cachedependcy['source'];
                        }
                        foreach ($_sourcearr as $_source) {
                            BoCache::Cache(CACHE_DEPENDCY_PRE . $_source, time(), 0);
                        }
                    }
                }
            }
        }
    }

    private function client_call($method, $arguments)
    {
        if ($this->_cachetime > 0) {
            $_keyname = md5(var_export($this->_cacheasync, true) . get_class($this) . $method . serialize($arguments));
            $_cacheexpire = $this->checkCacheExpire($_keyname);
            $_cacheval = BoCache::Cache($_keyname);
            // if ($this->_cacheasync && ! $this->_callfrombg) {
            // $_listdata = &Registry::getInstance()->get("cache_list_forsend");
            // $_go = false;
            // if (is_array($_listdata)) {
            // if (! isset($_listdata[$_keyname])) {
            // $_go = true;
            // }
            // } else {
            // $_go = true;
            // }
            // if ($_go) {
            // $_listdata[$_keyname] = [
            // 'key' => $_keyname,
            // "cachetime" => $this->_cachetime,
            // "obj" => $this,
            // "method" => $method,
            // 'arg' => $arguments,
            // 'rpckey' => CACHE_DEPENDCY_KEY,
            // 'cachedependcy' => $this->_cachedependcy != null ? 'on' : 'off'
            // ];
            // Registry::getInstance()->set("cache_list_forsend", $_listdata);
            // }
            // }
            if ($_cacheval == null || $_cacheexpire || $this->_callfrombg) {
                $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLER_NAME, $_keyname);
                $_data = null;
                if ($_lock) {
                    try {
                        if ($_lock->lock()) {
                            $_data = $this->service_call($method, $arguments);
                            if ($_data != null && ! $_data['err']) {
                                if ($this->_cacheasync) {
                                    BoCache::Cache($_keyname, $_data, 0);
                                    BoCache::Cache($_keyname . "expire", 1, $this->_cachetime);
                                } else {
                                    BoCache::Cache($_keyname, $_data, $this->_cachetime);
                                }
                                $this->checkcacheMaster($_cacheval, $_data);
                            } else {
                                if ($this->_cacheasync) {
                                    $_data = $_cacheval;
                                }
                            }
                            $_lock->unlock();
                        }
                    } catch (\Exception $e) {
                        $_lock->unlock();
                    }
                }

                return $_data;
            } else {
                $_reqgo = false;
                if ($this->_cacheasync) {
                    if (BoCache::Cache($_keyname . "expire") == null) {
                        $_reqgo = true;
                    }
                }
                if ($this->_cachemaster || $_reqgo || $_cacheexpire) {
                    $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLER_NAME, $_keyname);
                    $_data = null;
                    if ($_lock) {
                        try {
                            if ($_lock->lock()) {
                                $_data = $this->service_call($method, $arguments);
                                if ($_data != null && ! $_data['err']) {
                                    if ($this->_cacheasync) {
                                        BoCache::Cache($_keyname, $_data, 0);
                                        BoCache::Cache($_keyname . "expire", 1, $this->_cachetime);
                                    } else {
                                        BoCache::Cache($_keyname, $_data, $this->_cachetime);
                                    }
                                }
                                $this->checkcacheMaster($_cacheval, $_data);
                                $_lock->unlock();
                            }
                        } catch (\Exception $e) {
                            $_lock->unlock();
                        }
                    }
                }
                return $_cacheval;
            }
        } else {
            return $this->service_call($method, $arguments);
        }
    }

    public function __call($method, $arguments)
    {
        return $this->client_call($method, $arguments);
    }
}

?>