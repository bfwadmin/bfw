<?php
/*$_config_arr['Sms']['base'] = [
    'serv' => '210.5.158.31',
    'port' => '9011',
    'query' => '/hy',
    'mobile' => '13482096848',
    'uid' => 'qizhiwenhua',
    'pwd' => '111111',
    'auth' => 'sstz',
    'send' => 'post',
    "url" => 'http://115.29.44.189:8080/sms/smsInterface.do'
];*/
$_config_arr['Sms']['base'] = [
'serv' => '210.5.158.31',
'port' => '9011',
'query' => '/hy',
'mobile' => '13482096848',
'uid' => '133',
'pwd' => '205049',
'auth' => 'sstz',
'send' => 'get',
"url" => 'http://139.196.144.25:18002/send.do'
];
$_config_arr['Sms']['error'] = [
    '0' => '操作成功',
    '-1' => '签权失败',
    '-2' => '未检索到被叫号码',
    '-3' => '被叫号码过多',
    '-4' => '内容未签名',
    '-5' => '内容过长',
    '-6' => '余额不足',
    '-7' => '暂停发送',
    '-8' => '保留',
    '-9' => '定时发送时间格式错误',
    '-10' => '下发内容为空',
    '-11' => '账户无效',
    '-12' => 'Ip地址非法',
    '-13' => '操作频率快',
    '-14' => '操作失败',
    '-15' => '拓展码无效(1-999)',
    '-17' => '未开通状态报告（行业后台可设置开通）',
    '-19' => '未开通上行（行业后台可设置开通）'
];
$_config_arr['Sms']['template'] = [
    "regcode" => "您的短信验证码为:[?],10分钟内有效，您正在注册88艺术会员服务，如非本人操作请忽略该短信。",
    'buyersuccess' => "亲爱的[0]，您购买的作品《[1]》，价格为[2]已支付成功,订单号[3]，客服将尽快联系您并核实信息，感谢您的支持和厚爱！",
    "sellersuccess" => "尊敬的[0]，您的作品《[1]》已被售出，订单号[2]，售价[3]，客服将尽快联系您并核实信息，感谢您的支持和厚爱！",
    "applybidsuccess" => "您好！您申请参拍的标的”[0]“已经通过审核，您的牌号为[1]您可以在拍卖时间参加拍卖了",
    "applyaddbidsuccess" => "您好！您可以参加拍卖了，您的牌号为[0]",
    "applyaddbidsuccesspwd" => "您好！您可以通过[0],密码[1]参加拍卖了"
];

?>