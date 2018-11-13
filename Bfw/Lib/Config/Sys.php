<?php
// 店铺状态
$_config_arr['Sys']['validate'] = [
    "input_array_empty" => "输入数组不能为空"
];
$_config_arr['Sys']['form'] = [
    "token_empty" => "页面开启校验，请设置校验值",
    "token_wrong" => "页面过期，请刷新后重试"
];
$_config_arr['Sys']['auth'] = [
    "user_not_logined" => "请登录后访问",
    "user_no_power" => "对不起，您没有权限",
    "user_access_err" => "登录异常，请重新登录"
];
$_config_arr['Sys']['webapp'] = [
    "class_not_found" => "找不到类",
    "action_not_found" => "找不动作器 ",
    "con_act_format_wrong" => "动作器控制器命名错误",
    "key_wrong" => "通信秘钥不正确",
    "view_not_found" => "视图不存在",
    "run_mode_disallow" => "运行模式不匹配",
    "controler_not_allow"=>"不允许访问此控制器",
    "ip_disallow"=>"该ip不允许访问",
    "device_disallow"=>'访问终端不匹配'
];
$_config_arr['Sys']['cache'] = [
    "dependcy_not_found" => "缓冲依赖找不到"
];