<?php
// 保证金状态
$_config_arr['Product']['depositstate'] = [
    "1" => "保证金冻结",
    "2" => "保证金已退还",
    "3" => "保证金作为首款支付给卖家",
    "4" => "保证金作为违约金支付给卖家"
];

// 标的状态
$_config_arr['Product']['state'] = [
    "0" => "未结束",
    "1" => "成交",
    "2" => "无人竞价流拍",
    "3" => "未达到保留价流拍"
];