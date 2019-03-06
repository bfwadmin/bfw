<?php
namespace Plugin\Pay\Weijspay;

use Plugin\Pay\BoPayInterface;
use Lib\Bfw;
require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once "lib/WxPay.JsApiPay.php";

class Pay implements BoPayInterface
{

    private $_notify_url;

    private $_return_url;

    private $_notify;

    public function __construct($_notify_url, $_return_url)
    {
        $this->_notify_url = $_notify_url;
        $this->_return_url = $_return_url;
    }

    function GetPara()
    {
        return [];
    }

    function Success()
    {
        $this->_notify->ReplyNotify(false);
        // echo "success"; // 请不要修改或删除
    }

    function Fail()
    {
        $this->_notify->ReplyNotify(false);
        // echo "fail"; // 请不要修改或删除
    }

    function Refund($_orderno, $_total_fee, $_refund_fee, $_by = "trade_no")
    {
        $input = new \WxPayRefund();
        if ($_by == "third_trade_no") {
            $input->SetTransaction_id($_orderno);
        } else {
            $input->SetOut_trade_no($_orderno);
        }
        $input->SetTotal_fee($_total_fee * 100);
        $input->SetRefund_fee($_refund_fee * 100);
        $input->SetOut_refund_no(\WxPayConfig::MCHID . date("YmdHis"));
        $input->SetOp_user_id(\WxPayConfig::MCHID);
        return \WxPayApi::refund($input);
    }

    function Notify()
    {
        $this->_notify = new PayNotify();
        $_data = $this->_notify->Handle(false);
        if ($_data == false) {
            return Bfw::RetMsg(true, "");
        }
        
        if (is_array($this->_notify->_data)) {
            return Bfw::RetMsg(false, array(
                "attach" => isset($this->_notify->_data['attach']) ? $this->_notify->_data['attach'] : '',
                "trade_no" => $this->_notify->_data['out_trade_no'],
                "trade_fee" => $this->_notify->_data['cash_fee'] / 100,
                "third_trade_no" => $this->_notify->_data['transaction_id'],
                "old_data" => $this->_notify->_data
            ));
        }
        return Bfw::RetMsg(true, "");
    }

    function CallBack()
    {
        return Bfw::RetMsg(true, "");
    }

    function Go($out_trade_no, $total_fee, $subject = "", $body = "", $_attachdata = "")
    {
        
        // ①、获取用户openid
        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();
        
        // ②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($subject);
        $input->SetAttach($_attachdata);
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_fee * 100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($body);
        $input->SetNotify_url($this->_notify_url);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = \WxPayApi::unifiedOrder($input);
        // echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        // printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);
        
        $_html = <<<SCRIPT
        <html>
        <head>
        <title>微信支付</title>
        </head>
        <body>
                 <script type="text/javascript">
	    //调用微信JS api 支付
	    function jsApiCall()
	    {
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			 $jsApiParameters,
			function(res){
				//WeixinJSBridge.log(res.err_msg);
				if(res.err_msg == "get_brand_wcpay_request:ok" ) 
				{
			         alert("支付成功");
			         location.href="$this->_return_url";
			     }else{
			         alert("支付失败,请重试");
			             history.back();
			    }
			}
	 	  );
	     }

	      function callpay()
	      {
		   if (typeof WeixinJSBridge == "undefined"){
		      if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		      }else if (document.attachEvent){
		         document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		          document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		      }
		   }else{
		      jsApiCall();
		   }
	    }
        callpay();
	    </script>
        </body>
        </html>

SCRIPT;
        // echo $jsApiParameters;
        echo $_html;
        // echo '<h1>微信 扫一扫</h1><img alt="" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=' . url . '" style="width:200px;height:200px;"/><h2><a href="' . $this->_return_url . '">支付完成</a></h2>';
    }
}

class PayNotify extends \WxPayNotify
{

    public $_data;
    // 查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }
    
    // 重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        $this->_data = $data;
        // Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();
        if (! array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        // 查询订单，判断订单真实性
        if (! $this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
}

?>