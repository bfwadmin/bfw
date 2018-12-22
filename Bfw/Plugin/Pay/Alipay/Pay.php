<?php
namespace App\Plugin\Pay\Alipay;

use App\Plugin\Pay\BoPayInterface;
use Lib\Bfw;
require_once ("lib/alipay_submit.class.php");
require_once ("lib/alipay_notify.class.php");

class Pay implements BoPayInterface
{

    private $_alipay_config;

    private $_delimiter = "||||||";

    public function __construct($_notify_url, $_return_url)
    {
        $this->_alipay_config['partner'] = '2088121971130340';
        $this->_alipay_config['seller_id'] = $this->_alipay_config['partner'];
        $this->_alipay_config['key'] = 'pc3zz0fftsfqklqdo6caua0613sp0yus';
        $this->_alipay_config['notify_url'] = $_notify_url;
        $this->_alipay_config['return_url'] = $_return_url;
        $this->_alipay_config['sign_type'] = strtoupper('MD5');
        $this->_alipay_config['input_charset'] = strtolower('utf-8');
        $this->_alipay_config['cacert'] = getcwd() . '\\cacert.pem';
        $this->_alipay_config['transport'] = 'http';
        $this->_alipay_config['payment_type'] = "1";
        $this->_alipay_config['service'] = "create_direct_pay_by_user";
        $this->_alipay_config['anti_phishing_key'] = "";
        $this->_alipay_config['exter_invoke_ip'] = "";
    }
    function GetPara(){
        return [];
    }
    
    function Success()
    {
        echo "success"; // 请不要修改或删除
    }

    function Fail()
    {
        echo "fail"; // 请不要修改或删除
    }

    function Notify()
    {
        
        // 计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->_alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if ($verify_result) { // 验证成功 // 商户订单号
                              // $out_trade_no = $_POST['out_trade_no'];
                              // 支付宝交易号
                              // $trade_no = $_POST['trade_no'];
                              // 交易状态
                              // $trade_status = $_POST['trade_status'];
            if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $_body_arr = explode($this->_delimiter, $_POST['body']);
                return Bfw::RetMsg(false, array(
                    "attach" => isset($_body_arr[1]) ? $_body_arr[1] : "",
                    "trade_no" => $_POST['out_trade_no'],
                    "trade_fee" => $_POST['total_fee'],
                    "third_trade_no" => $_POST['trade_no'],
                    "old_data" => $_POST
                ));
            } else {
                return Bfw::RetMsg(true, 'status wrong');
            }
        } else {
            return Bfw::RetMsg(true, 'verify wrong');
        }
    }

    function CallBack()
    {
        
        // 计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($this->_alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        
        if ($verify_result) { // 验证成功 // 商户订单号
                              // $out_trade_no = $_POST['out_trade_no'];
                              // 支付宝交易号
                              // $trade_no = $_POST['trade_no'];
                              // 交易状态
                              // $trade_status = $_POST['trade_status'];
            $_serverdata = array_merge($_POST, $_GET);
            if ($_serverdata['trade_status'] == 'TRADE_FINISHED' || $_serverdata['trade_status'] == 'TRADE_SUCCESS') {
                $_body_arr = explode($this->_delimiter, $_serverdata['body']);
                return Bfw::RetMsg(false, array(
                    "attach" => isset($_body_arr[1]) ? $_body_arr[1] : "",
                    "trade_no" => $_serverdata['out_trade_no'],
                    "trade_fee" => $_serverdata['total_fee'],
                    "third_trade_no" => $_serverdata['trade_no'],
                    "old_data" => $_serverdata
                ));
            } else {
                return Bfw::RetMsg(true, "");
            }
        } else {
            return Bfw::RetMsg(true, "");
        }
    }

    function Go($out_trade_no, $total_fee, $subject = "", $body = "", $_attachdata = "")
    {
        $parameter = array(
            "service" => $this->_alipay_config['service'],
            "partner" => $this->_alipay_config['partner'],
            "seller_id" => $this->_alipay_config['seller_id'],
            "payment_type" => $this->_alipay_config['payment_type'],
            "notify_url" => $this->_alipay_config['notify_url'],
            "return_url" => $this->_alipay_config['return_url'],
            "anti_phishing_key" => $this->_alipay_config['anti_phishing_key'],
            "exter_invoke_ip" => $this->_alipay_config['exter_invoke_ip'],
            "out_trade_no" => $out_trade_no,
            "subject" => $subject,
            "total_fee" => $total_fee,
            "body" => $body . $this->_delimiter . $_attachdata,
            "_input_charset" => trim(strtolower($this->_alipay_config['input_charset']))
        );
        // return var_dump($parameter);
        // 建立请求
        $alipaySubmit = new \AlipaySubmit($this->_alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "get", "确认");
        echo $html_text;
    }
}

?>