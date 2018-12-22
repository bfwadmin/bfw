<?php
namespace Lib;

use Lib\Util\HttpUtil;
use Lib\Exception\HttpException;
// import ( "Client." . DOMIAN_VALUE . ".config" );
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
        if ($this->_service_remote) {
            return $this->Proxy()->Insert($_data, $_returnid);
        } else {
            return $this->Service()->Insert($_data, $_returnid);
        }
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
        
        if ($this->_service_remote) {
            return $this->Proxy()->Update($_data);
        } else {
            return $this->Service()->Update($_data);
        }
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
        if ($this->_service_remote) {
            return $this->Proxy()->Single($field, $id);
        } else {
            return $this->Service()->Single($field, $id);
        }
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
        if ($this->_cachetime > 0) {
            $_cacheexpire = $this->checkCacheExpire();
            $_keyname = md5(get_class($this) . $_field . $id);
            $_cacheval = Core::Cache($_keyname);
            if ($this->_cacheasync && ! $this->_callfrombg) {
                $this->updateCacheAsync([
                    'key' => $_keyname,
                    "cachetime" => $this->_cachetime,
                    "obj" => serialize($this),
                    "method" => "Single",
                    'arg' => serialize([
                        $id,
                        $_field
                    ]),
                    'rpckey' => CACHE_DEPENDCY_KEY,
                    'cachedependcy' => $this->_cachedependcy != null ? 'on' : 'off'
                ]);
            }
            if ($_cacheval == null || $_cacheexpire) {
                $_data = $this->One($id, $_field);
                if ($_data != null && ! $_data['err']) {
                    $this->cacheMasterUpdate();
                    if ($this->_cacheasync) {
                        Core::Cache($_keyname, $_data, 0);
                    } else {
                        Core::Cache($_keyname, $_data, $this->_cachetime);
                    }
                    // Core::Cache($_keyname, $_data, $this->_cachetime);
                }
                
                return $_data;
            } else {
                return $_cacheval;
            }
        } else {
            return $this->One($id, $_field);
        }
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
        if ($this->_service_remote) {
            return $this->Proxy()->Delete($id);
        } else {
            return $this->Service()->Delete($id);
        }
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
        if ($this->_service_remote) {
            return $this->Proxy()->ListData($field, $wherestr, $wherearr, $pagesize, $page, $orderby, $needcount);
        } else {
            return $this->Service()->ListData($field, $wherestr, $wherearr, $pagesize, $page, $orderby, $needcount);
        }
    }

    public function Count($wherestr = null, $wherearr = null)
    {
        if ($this->_service_remote) {
            return $this->Proxy()->Count($wherestr, $wherearr);
        } else {
            return $this->Service()->Count($wherestr, $wherearr);
        }
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
                    // Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                } elseif ($this->_cachedependcy['type'] == 'service' && is_array($this->_cachedependcy['source'])) {
                    // Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                } elseif ($this->_cachedependcy['type'] == 'cache') {
                    // Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
                }
            }
        }
    }

    private function checkCacheExpire()
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
        if ($_go) {
            if (is_array($this->_cachedependcy) && isset($this->_cachedependcy['type']) && isset($this->_cachedependcy['source'])) {
                if ($this->_cachedependcy['type'] == 'file') {
                    $_filefullname = APP_ROOT . DS . 'Data' . DS . $this->_cachedependcy['source'];
                    if (! file_exists($_filefullname)) {
                        Bfw::LogR($_filefullname . Bfw::Config("Sys", "cache", 'System')['dependcy_not_found']);
                    } else {
                        $_filemtimeval = filemtime($_filefullname);
                        if (Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre") != $_filemtimeval) {
                            Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre", $_filemtimeval, 0);
                            $_cacheexpire = true;
                        }
                    }
                } elseif ($this->_cachedependcy['type'] == 'service' && is_array($this->_cachedependcy['source'])) {
                    $_ret = call_user_func_array(array(
                        Core::LoadClass("App\\Client\\" . DOMIAN_VALUE . '\Client_' . $this->_cachedependcy['source'][0]),
                        $this->_cachedependcy['source'][1]
                    ), $this->_cachedependcy['source'][2]);
                    
                    if ($_ret['err']) {
                        Bfw::LogR($this->_cachedependcy['source'][0] . $_ret['data']);
                    } else {
                        if (Core::Cache(CACHE_DEPENDCY_PRE . md5(var_export($this->_cachedependcy['source'], true)) . "_pre") != $_ret['data']) {
                            Core::Cache(CACHE_DEPENDCY_PRE . md5(var_export($this->_cachedependcy['source'], true)) . "_pre", $_ret['data'], 0);
                            $_cacheexpire = true;
                        }
                    }
                } elseif ($this->_cachedependcy['type'] == 'cache') {
                    $_depencycacheval = Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy['source']);
                    if ($_depencycacheval == null) {
                        Bfw::LogR($this->_cachedependcy['source'] . Bfw::Config("Sys", "cache", 'System')['dependcy_not_found']);
                    } else {
                        if (Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre") != $_depencycacheval) {
                            Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy['source'] . "_pre", $_depencycacheval, 0);
                            $_cacheexpire = true;
                        }
                    }
                }
            }
        }
        return $_cacheexpire;
    }

    public function Total()
    {
        if ($this->_cachetime > 0) {
            $_cacheexpire = $this->checkCacheExpire();
            $_keyname = md5(get_class($this) . $this->_wherestr . serialize($this->_wherearr));
            $_cacheval = Core::Cache($_keyname);
            if ($this->_cacheasync && ! $this->_callfrombg) {
                $this->updateCacheAsync([
                    'key' => $_keyname,
                    "cachetime" => $this->_cachetime,
                    "obj" => serialize($this),
                    "method" => "Total",
                    'arg' => serialize([]),
                    'rpckey' => CACHE_DEPENDCY_KEY,
                    'cachedependcy' => $this->_cachedependcy != null ? 'on' : 'off'
                ]);
            }
            if ($_cacheval == null || $_cacheexpire) {
                $_data = $this->Count($this->_wherestr, $this->_wherearr);
                if ($_data != null && ! $_data['err']) {
                    $this->cacheMasterUpdate();
                    if ($this->_cacheasync) {
                        Core::Cache($_keyname, $_data, 0);
                    } else {
                        Core::Cache($_keyname, $_data, $this->_cachetime);
                    }
                }
                return $_data;
            } else {
                return $_cacheval;
            }
        } else {
            return $this->Count($this->_wherestr, $this->_wherearr);
        }
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
        if ($this->_cachetime > 0) {
            $_cacheexpire = $this->checkCacheExpire();
            $_keyname = md5(get_class($this) . $this->_field . $this->_wherestr . serialize($this->_wherearr) . $this->_pagesize . $this->_page . $this->_orderstr . serialize($_withtotal));
            $_cacheval = Core::Cache($_keyname);
            if ($this->_cacheasync && ! $this->_callfrombg) {
                $this->updateCacheAsync([
                    'key' => $_keyname,
                    "cachetime" => $this->_cachetime,
                    "obj" => serialize($this),
                    "method" => "Select",
                    'arg' => serialize([
                        $_withtotal
                    ]),
                    'rpckey' => CACHE_DEPENDCY_KEY,
                    'cachedependcy' => $this->_cachedependcy != null ? 'on' : 'off'
                ]);
            }
            if ($_cacheval == null || $_cacheexpire) {
                $_data = $this->ListData($this->_field, $this->_wherestr, $this->_wherearr, $this->_pagesize, $this->_page, $this->_orderstr, $_withtotal);
                if ($_data != null && ! $_data['err']) {
                    $this->cacheMasterUpdate();
                    if ($this->_cacheasync) {
                        Core::Cache($_keyname, $_data, 0);
                    } else {
                        Core::Cache($_keyname, $_data, $this->_cachetime);
                    }
                }
                return $_data;
            } else {
                return $_cacheval;
            }
        } else {
            return $this->ListData($this->_field, $this->_wherestr, $this->_wherearr, $this->_pagesize, $this->_page, $this->_orderstr, $_withtotal);
        }
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
        Core::Cache(CACHE_DEPENDCY_PRE . $this->_cachedependcy, time(), 0);
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
        return new BoTranClient($this->_serv_url, DOMIAN_VALUE, $_servicename);
    }

    private function getserviceinfo($_servicename)
    {
        Bfw::import("Lib.Core");
        $_key = md5("dom_" . DOMIAN_VALUE . $_servicename);
        $_data = Core::Cache($_key);
        if (empty($_data)) {
            $_url = SERVICE_REG_CENTER_URL . "?dom=" . DOMIAN_VALUE . "&act=get&cont=service&sername=" . $_servicename . "&notifyurl=" . urlencode(SERVICE_NOTIFY_URL);
      
            $_data = HttpUtil::HttpGet($_url);
            if ($_data['err']) {
                throw new HttpException('get service list http err,' . $_data['data']);
            } else {
                
                $_json_data = json_decode($_data['data'], true);
                if(empty($_json_data)){
                    throw new HttpException('get service list http err,empty data' );
                }
                Core::Cache($_key, $_json_data, 0);
                return $_json_data;
            }
        } else {
            return $_data;
        }
    }

    private function updateCacheAsync($_data)
    {
        HttpUtil::AsynPost(SERVER_NAME, SERVER_PORT, $_SERVER['SCRIPT_NAME'] . "?updatecache=1", $_data, 2);
    }

    private function callMethod($method, $arguments)
    {
        $_servicename = str_replace("App\\" . DOMIAN_VALUE . "\\Client\\Client_", "", get_class($this));
        $_service_mode_arr = Bfw::Config("Client", "mode", "System");
        $_servicename = SERVICE_DOMIAN_VALUE . "/" . $_servicename . "/" . str_replace("___", "", $method);
        if (isset($_service_mode_arr[$_servicename]) && $_service_mode_arr[$_servicename] === "Queue") {
            $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLE_NAME, "C_" . $_servicename . "_" . $method . "_" . SERVICE_DOMIAN_VALUE);
            if ($_lock) {
                try {
                    do {
                        Bfw::Debug("lock wait");
                        usleep(LOCK_WAIT_TIME);
                    } while (! $_lock->lock());
                    if ($this->_service_remote) {
                        return call_user_func_array(array(
                            $this->Proxy(),
                            str_replace("___", "", $method)
                        ), $arguments);
                    } else {
                        return call_user_func_array(array(
                            $this->Service(),
                            str_replace("___", "", $method)
                        ), $arguments);
                    }
                    usleep(LOCK_WAIT_TIME);
                    $_lock->unlock();
                } catch (\Exception $e) {
                    $_lock->unlock();
                    Bfw::LogToFile("C_lock exception:" . $e->getMessage());
                    return Bfw::RetMsg(true, "lock exception " . LOCK_HANDLE_NAME . ":" . $e->getMessage());
                }
            } else {
                return Bfw::RetMsg(true, "init " . LOCK_HANDLE_NAME . " lock err");
            }
        } else {
            if ($this->_service_remote) {
                return call_user_func_array(array(
                    $this->Proxy(),
                    str_replace("___", "", $method)
                ), $arguments);
            } else {
                return call_user_func_array(array(
                    $this->Service(),
                    str_replace("___", "", $method)
                ), $arguments);
            }
        }
    }

    public function RunBg($_istrue = true)
    {
        $this->_callfrombg = $_istrue;
    }

    public function __call($method, $arguments)
    {
        if ($this->_cachetime > 0) {
            $_cacheexpire = $this->checkCacheExpire();
            $_keyname = md5(get_class($this) . $method . serialize($arguments));
            $_cacheval = Core::Cache($_keyname);
            if ($this->_cacheasync && ! $this->_callfrombg) {
                $this->updateCacheAsync([
                    'key' => $_keyname,
                    "cachetime" => $this->_cachetime,
                    "obj" => serialize($this),
                    "method" => $method,
                    'arg' => serialize($arguments),
                    'rpckey' => CACHE_DEPENDCY_KEY,
                    'cachedependcy' => $this->_cachedependcy != null ? 'on' : 'off'
                ]);
            }
            if ($_cacheval == null || $_cacheexpire) {
                $_data = $this->callMethod($method, $arguments);
                if ($_data != null && ! $_data['err']) {
                    $this->cacheMasterUpdate();
                    if ($this->_cacheasync) {
                        Core::Cache($_keyname, $_data, 0);
                    } else {
                        Core::Cache($_keyname, $_data, $this->_cachetime);
                    }
                    // Core::Cache($_keyname, $_data, $this->_cachetime);
                }
                return $_data;
            } else {
                return $_cacheval;
            }
        } else {
            return $this->callMethod($method, $arguments);
        }
    }
}

?>