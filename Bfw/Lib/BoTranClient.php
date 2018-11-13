<?php
namespace Lib;

use Lib\Bfw;
use Lib\BoReqStatusEnum;
use Lib\Util\ArrayUtil;
use Lib\Util\FileUtil;
use Lib\Exception\HttpException;
use Lib\Util\HttpUtil;

class BoTranClient
{

    private $_url;

    private $_url_arr;

    private $_domian;

    private $_servicename;

    private $_servicelang;

    private $_servicekey;

    private $_selectedindex = - 1;

    private $_serviceid = 0;

    public function __construct($urlarr, $servurl, $domian, $servicename, $servicelang, $servicekey)
    {
        $this->_url = $servurl;
        $this->_url_arr = $urlarr;
        $this->_domian = $domian;
        $this->_servicename = $servicename;
        $this->_servicelang = $servicelang;
        $this->_servicekey = $servicekey;
    }

    private function GetConfig($_m)
    {
        $_err_type = 0;
        $_err_str = "";
        if (is_null($this->_url_arr)) {
            
            $_config_file = APP_ROOT . DS . DOMIAN_VALUE.DS . "Config" . . DS . "Client" . DS . $this->_servicename . DS . $_m . ".php";
            // die($_config_file);
            if (! file_exists($_config_file)) {
                try {
                    $fp = fopen(APP_ROOT . DS . "Data" . DS . $this->_servicename . "_" . $_m . "_" . DOMIAN_VALUE, "w");
                    if ($fp) {
                        if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
                            $_data = HttpUtil::HttpGet(SERVICE_REG_CENTER_URL . "act=LookUp&servicename=" . $this->_servicename . "&domain=" . SERVICE_DOMIAN_VALUE . "&methodname=" . $_m);
                            // die(var_export($_data, true));
                            if ($_data['err']) {
                                $_err_type = 3;
                                $_err_str = 'http err,' . $_data['data'];
                            } else {
                                $_json_data = json_decode($_data['data'], true);
                                if (is_null($_json_data)) {
                                    $_err_type = 2;
                                    $_err_str = 'json_err';
                                } else {
                                    if ($_json_data['err']) {
                                        $_err_type = 1;
                                        $_err_str = 'json_err2,' . $_json_data['data'];
                                    } else {
                                        if (! empty($_json_data['data'])) {
                                            $_str = ArrayUtil::printArrayAsPhpCode($_json_data['data'], true);
                                            if (FileUtil::CreatDir(dirname($_config_file))) {
                                                file_put_contents($_config_file, "<?php \$_serv_url =" . $_str . ";");
                                            } else {
                                                $_err_type = 4;
                                                $_err_str = 'create dir err,' . $_config_file;
                                            }
                                        } else {
                                            $_err_type = 6;
                                            $_err_str = 'empty config array';
                                        }
                                    }
                                }
                            }
                            flock($fp, LOCK_UN); // release the lock
                        } else {
                            $_err_type = 6;
                            $_err_str = 'lock err';
                            // echo "1";
                            // usleep(round(rand(0, 100) * 1000));
                        }
                        fclose($fp);
                    } else {
                        $_err_type = 5;
                        $_err_str = 'lock err';
                    }
                } catch (\Exception $e) {
                    throw new HttpException($e);
                }
            }
            
            if ($_err_type != 0) {
                return array(
                    "errno" => $_err_type,
                    "errdata" => $_err_str
                );
            }
            
            $_serv_url = null;
            include_once $_config_file;
            $this->_url_arr = $_serv_url;
            // var_dump($this->_url_arr);
        }
        return array(
            "errno" => $_err_type,
            "errdata" => $_err_str
        );
    }

    private function FindRoute($method)
    {
        if (is_array($this->_url_arr)) {
            if (count($this->_url_arr) != 0) {
                $_getindex = 0;
                
                if (count($this->_url_arr) >= 1) {
                    $_weight = array();
                    $_weight_inpipe = array();
                    $i = 0;
                    
                    foreach ($this->_url_arr as $_item) {
                        if ($i != $this->_selectedindex) {
                            $_weight[$i] = isset($_item["weight"]) ? $_item["weight"] : 1;
                        }
                        $i ++;
                    }
                    if (! empty($_weight_inpipe)) {
                        $this->_selectedindex = Bfw::Routeroll($_weight_inpipe);
                    } else {
                        $this->_selectedindex = Bfw::Routeroll($_weight);
                    }
                }
                // $this->_selectedindex = $_getindex;
                $_server_arr = $this->_url_arr[$this->_selectedindex];
                if (isset($_server_arr['url'])) {
                    $this->_url = $_server_arr['url'];
                }
                if (isset($_server_arr['lang'])) {
                    $this->_servicelang = $_server_arr['lang'];
                }
                if (isset($_server_arr['key'])) {
                    $this->_servicekey = $_server_arr['key'];
                }
                if (isset($_server_arr['dom'])) {
                    $this->_domian = $_server_arr['dom'];
                }
                if (isset($_server_arr['id'])) {
                    $this->_serviceid = $_server_arr['id'];
                }
            }
        }
    }

    private function PostData($method, $arguments)
    {
        if ($this->_url == "") {
            return array(
                "err" => true,
                "data" => "parr wrong"
            );
        }
        // application/json;charset=utf-8
        $header = array(
            "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
            "User-Agent: BFW CLIENT " . VERSION,
            "Accept-Encoding: gzip"
        ); // 定义content-type为xml
           // "Connection: Keep-Alive",
           // "Keep-Alive: 3000"
        $ch = curl_init(); // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $this->_url); // 设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 设置是否返回信息
        curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
        // if ($this->_servicelang != "php") {
        // curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        // }
        curl_setopt($ch, CURLOPT_TIMEOUT, CLIENT_TIMEOUT);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // 设置HTTP头
        curl_setopt($ch, CURLOPT_POST, 1); // 设置为POST方式
        if ($this->_servicelang == "php") {
            $postdata = "key=" . urlencode($this->_servicekey) . "&domianname=" . urlencode($this->_domian) . "&servicename=" . urlencode($this->_servicename) . "&methodname=" . urlencode($method) . "&arg=" . urlencode(serialize($arguments));
        } else {
            $_args = array();
            $i = 1;
            foreach ($arguments as $_arg) {
                $_args["{$i}"] = $_arg;
                $i ++;
            }
            $postdata = "key=" . urlencode($this->_servicekey) . "&domianname=" . urlencode($this->_domian) . "&servicename=" . urlencode($this->_servicename) . "&methodname=" . urlencode($method) . "&arg=" . urlencode(json_encode($_args));
        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        // echo $postdata;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // POST数据
        $_begin_time = microtime(true);
        if (WEB_DEBUG) {
            Bfw::Debug("[$this->_servicename][$method]start to post data to " . $this->_url);
        }
        
        $response = curl_exec($ch);
        // echo $response;
        
        // $response = trim($response, "\xEF\xBB\xBF");
        if (WEB_DEBUG) {
            $_finish_time = microtime(true);
            Bfw::Debug("[$this->_servicename][$method]finish post,spend:" . ($_finish_time - $_begin_time) . "s");
        }
        if (curl_errno($ch)) {
            $_errmsg = curl_error($ch);
            curl_close($ch);
            
            Bfw::SLogR(BoReqStatusEnum::BFW_S_FAIL, "url:{$this->_url},postdata:{$postdata},err:{$_errmsg}", $this->_serviceid);
            return array(
                "bo_err" => true,
                "bo_data" => "[$this->_servicename][$method]" . $_errmsg
            );
            // die("Err:" . curl_error($ch));
        }
        curl_close($ch);
        // if ($this->_servicelang == "php") {
        $ret = Core::LoadClass('Lib\\RPC\\' . RPC_WAY)->unpack($response);
        // } else {
        // echo $response;
        // $ret = json_decode($response, true);
        // }
        
        if (! is_array($ret)) {
            Bfw::SLogR(BoReqStatusEnum::BFW_S_PRO, "url:{$this->_url},postdata:{$postdata},response:{$response}", $this->_serviceid);
            return array(
                "bo_err" => true,
                "bo_data" => "server protocol wrong"
            );
        }
        if ($ret['bo_err'] === true) {
            Bfw::SLogR(BoReqStatusEnum::BFW_S_ERR, "url:{$this->_url},postdata:{$postdata},response:{$response}", $this->_serviceid);
            return array(
                "bo_err" => true,
                "bo_data" => $ret['bo_data']
            );
        }
        if (WEB_DEBUG) {
            if (is_array($ret['bo_trace'])) {
                Bfw::Debug("<p>get debug info from server</p> " . Bfw::DebugHtml($ret['bo_trace']['import_file'], $ret['bo_trace']['debug_info'], $ret['bo_trace']['spend_time'], $ret['bo_trace']['log_toserver']));
            }
        }
        Bfw::SLogR(BoReqStatusEnum::BFW_S_SUC, "url:{$this->_url},postdata:{$postdata},r esponse:{$response}", $this->_serviceid);
        
        return $ret;
    }

    public function __call($method, $arguments)
    {
        $_reterrnumarr = $this->GetConfig($method);
        if ($_reterrnumarr['errno'] != 0) {
            return array(
                "err" => true,
                "data" => "err, {$_reterrnumarr['errdata']}"
            );
        }
        $this->FindRoute($method);
        $_oldindex = $this->_selectedindex;
        // 调用模式
        $_service_mode_arr = Bfw::Config("Service", "config", "System");
        $_runservicename = $this->_domian . "_" . $this->_servicename . "_" . $method;
        $_cachekey = "calllimit_" . md5($_runservicename);
        if (isset($_service_mode_arr[$_runservicename])) {
            if (isset($_service_mode_arr[$_runservicename]['limit'])) {
                
                if (isset($_service_mode_arr[$_runservicename]['type']) && $_service_mode_arr[$_runservicename]['type'] == "session") {
                    $_cachekey .= SESS_ID;
                }
                if (isset($_service_mode_arr[$_runservicename]['para'])) {
                    if (isset($arguments[$_service_mode_arr[$_runservicename]['para']])) {
                        $_cachekey .= var_export($arguments[$_service_mode_arr[$_runservicename]['para']], true);
                    }
                }
                if (isset($_service_mode_arr[$_runservicename]['type']) && $_service_mode_arr[$_runservicename]['type'] != "queue") {
                    $_cachedata = Core::Cache($_cachekey);
                    if (is_numeric($_cachedata) && $_cachedata > 0) {
                        if (time() - $_cachedata < $_service_mode_arr[$_runservicename]['limit']) {
                            return array(
                                "err" => true,
                                "data" => "服务器忙,请稍后再试"
                            );
                        }
                    }
                    Core::Cache($_cachekey, time(), 180);
                }
            }
        }
        
        if (isset($_service_mode_arr[$_runservicename]['type']) && $_service_mode_arr[$_runservicename]['type'] == "queue") {
            $_lock = Core::LoadClass("Lib\\Lock\\" . LOCK_HANDLE_NAME, $_cachekey);
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
                    
                    $_ret = $this->GoPost($method, $arguments, $_oldindex);
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
        return $this->GoPost($method, $arguments, $_oldindex);
    }

    private function GoPost($_method, $_arguments, $_oldindex)
    {
        $_ret = $this->PostData($_method, $_arguments);
        if ($_ret['bo_err']) {
            $this->FindRoute($_method);
            if ($_oldindex != $this->_selectedindex) {
                HttpUtil::HttpGet(SERVICE_REG_CENTER_URL . "act=Report&serviceid=" . $this->_serviceid);
                Bfw::Debug("master server err [{$_ret['bo_data']}],post to backup server");
                $_ret = $this->PostData($_method, $_arguments);
            }
        }
        if (! is_array($_ret['bo_data'])) {
            $_ret['bo_data'] = Bfw::RetMsg(true, $_ret['bo_data']);
        }
        return $_ret['bo_data'];
    }
}

?>