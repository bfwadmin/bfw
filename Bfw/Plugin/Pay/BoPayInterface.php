<?php
namespace Plugin\Pay;

interface BoPayInterface
{

    public function Go($out_trade_no, $total_fee, $subject = "", $body = "", $_attachdata = "");

    public function Notify();

    public function Success();

    public function Fail();
    
    public function GetPara();
}

?>