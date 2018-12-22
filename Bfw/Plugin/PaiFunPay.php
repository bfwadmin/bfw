<?php
class PaiFunPay {
	private $appid = 1;
	private $secrkey = "dfdfdDd1231!@";
	private $payurl = "http://pay.paifun.net/FrontPay.aspx";
	public function __construct($_appid, $_secrkey) {
		$this->appid = $_appid;
		$this->secrkey = $_secrkey;
	}
	public function GoTOPay($amount, $ordernum) {
		$signdata = md5 ( $amount . $ordernum . $this->secrkey );
		header ( 'Location: ' . $this->payurl . '?appid=' . $this->appid . '&ordernumber=' . $ordernum . '&paymoney=' . $amount . '&signdata=' . $signdata );
	}
	public function VerfyData($postdata) {
		if (isset ( $postdata ['payedmoney'] ) && isset ( $postdata ['ordernumber'] ) && isset ( $postdata ['paysuccess'] ) && isset ( $postdata ['signdata'] )&&isset ( $postdata ['payfrom'] )&&isset ( $postdata ['paynum'] )) {
			$payedmoney = $postdata ['payedmoney'];
			$ordernumber = $postdata ['ordernumber'];
			$paysuccess = $postdata ['paysuccess'];
			$signdata = $postdata ['signdata'];
			$payfrom = $postdata ['payfrom'];
			$paynum = $postdata ['paynum'];
			if (md5 ( $payedmoney . $ordernumber . $paysuccess . $this->secrkey.$payfrom.$paynum ) == $signdata) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function __destruct() {
	}
}

?>