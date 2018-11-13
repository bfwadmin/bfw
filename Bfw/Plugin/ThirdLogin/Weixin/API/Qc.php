<?php
class Qc
{

    private $AppID = 'wx45f3390ee32f2e60';

    private $AppSecret = '405449dc636170722e9a27901cae3c9a';

    private $callback = 'http://www.88art.com/Cms/Member/Login/thirdname/2';
    // 回调地址
    function GotoAuth()
    {
        $state = md5(uniqid(rand(), TRUE));
        $_SESSION["wx_state"] = $state; // 存到SESSION
        $callback = urlencode($this->callback);
        $_url= "https://open.weixin.qq.com/connect/qrconnect?appid=" . $this->AppID . "&redirect_uri={$callback}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
        header("location:{$_url}");
    }

    function CallBackData()
    {
        
        if ($_GET['state'] != $_SESSION["wx_state"]) {
            return null;
        }
        
        $json = $this->http_request('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->AppID . '&secret=' . $this->AppSecret . '&code=' . $_GET['code'] . '&grant_type=authorization_code');
        $token = json_decode($json, 1);
        if (isset($token->errcode)) {
            return [
                'err' => true,
                "data" => $token->errmsg
            ];
        }
        $json = $this->http_request('https://api.weixin.qq.com/sns/userinfo?access_token=' . $token['access_token'] . '&openid=' . $token['openid'] . '&lang=zh_CN');
        $user_info = json_decode($json, 1);
        if (isset($user_info->errcode)) {
            return [
                'err' => true,
                "data" => $user_info->errmsg
            ];
        }
        return $user_info;
    }

    private function http_request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $_response = curl_exec($ch);
        curl_close($ch);
        return $_response;
    }
}

?>