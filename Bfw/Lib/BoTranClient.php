<?php
namespace Lib;

use Lib\Bfw;
use Lib\BoReqStatusEnum;
use Lib\Util\HttpUtil;

class BoTranClient
{

    private $_serviceurl;

    private $_url_arr;

    private $_domian;

    private $_servicename;

    private $_servicelang;

    private $_servicekey;

    private $_selectedindex = - 1;

    private $_serviceid = "";

    public function __construct($urlarr, $_domian, $_servicename)
    {
        $this->_url_arr = $urlarr;
        $this->_domian = $_domian;
        $this->_servicename = $_servicename;
        $this->_servicelang = "";
        $this->_servicekey = "";
        $this->_serviceurl = "";
    }

    private function FindRoute($method)
    {
        $_urs = [];
        if (is_array($this->_url_arr)) {
            foreach ($this->_url_arr as $_item) {
                if ($_item["act"] == $method) {
                    $_urs[] = $_item;
                }
            }
            $this->_url_arr = $_urs;
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
                if (isset($_server_arr['serviceurl'])) {
                    $this->_serviceurl = $_server_arr['serviceurl'];
                }
                if (isset($_server_arr['lang'])) {
                    $this->_servicelang = $_server_arr['lang'];
                }
                if (isset($_server_arr['seckey'])) {
                    $this->_servicekey = $_server_arr['seckey'];
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
        if ($this->_serviceurl == "") {
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
        curl_setopt($ch, CURLOPT_URL, $this->_serviceurl); // 设置链接
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
       // die($postdata);
      //  echo $this->_servicename.$method."start to post data to " . $this->_serviceurl;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); // POST数据
        $_begin_time = microtime(true);
        if (WEB_DEBUG) {
            Bfw::Debug("[$this->_servicename][$method]start to post data to " . $this->_serviceurl);
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
            
            Bfw::SLogR(BoReqStatusEnum::BFW_S_FAIL, "url:{$this->_serviceurl},postdata:{$postdata},err:{$_errmsg}", $this->_serviceid);
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
        //echo $response;
        // $ret = json_decode($response, true);
        // }
        
        if (! is_array($ret)) {
            Bfw::SLogR(BoReqStatusEnum::BFW_S_PRO, "url:{$this->_serviceurl},postdata:{$postdata},response:{$response}", $this->_serviceid);
            return array(
                "bo_err" => true,
                "bo_data" => "server protocol wrong"
            );
        }
        if ($ret['bo_err'] === true) {
            Bfw::SLogR(BoReqStatusEnum::BFW_S_ERR, "url:{$this->_serviceurl},postdata:{$postdata},response:{$response}", $this->_serviceid);
            return array(
                "bo_err" => true,
                "bo_data" => $ret['bo_data']
            );
        }
        if (WEB_DEBUG) {
            if (is_array($ret['bo_trace'])) {
                Bfw::Debug("get debug info from server:{$this->_serviceurl}" . Bfw::DebugHtml($ret['bo_trace']['import_file'], $ret['bo_trace']['debug_info'], $ret['bo_trace']['spend_time'], $ret['bo_trace']['log_toserver'], $ret['bo_trace']['totalmem'], "servicedebug"));
            }
        }
        Bfw::SLogR(BoReqStatusEnum::BFW_S_SUC, "url:{$this->_serviceurl},postdata:{$postdata},r esponse:{$response}", $this->_serviceid);
        
        return $ret;
    }

    public function __call($method, $arguments)
    {
      //  var_dump($arguments);
        $this->FindRoute($method);
        $_oldindex = $this->_selectedindex;
        return $this->GoPost($method, $arguments, $_oldindex);
    }

    private function GoPost($_method, $_arguments, $_oldindex)
    {
        $_ret = $this->PostData($_method, $_arguments);
        if ($_ret['bo_err']) {
            $this->FindRoute($_method);
            if ($_oldindex != $this->_selectedindex) {
                // 举报错误
                
                if ($this->_serviceid != "") {
                    $_url = SERVICE_REG_CENTER_URL . "?cont=service&dom=bfw&act=report&serviceid=" . $this->_serviceid;
                    HttpUtil::HttpGet($_url);
                }
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