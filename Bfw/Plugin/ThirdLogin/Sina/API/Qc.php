<?php
require dirname(__FILE__) . '/saetv2.ex.class.php';

class Qc
{

    private $appid = "56753265";

    private $appsecret = '8e988aa5d1b4e437219ca8895394b5ce';
    
    private $callback = 'http://www.88art.com/Cms/Member/Login/thirdname/3';
   // private $callback = 'http://www.88art.com/loginband/';

    private $style = '4';

    function GotoAuth()
    {
        $o = new \SaeTOAuthV2($this->appid, $this->appsecret);
        $code_url = $o->getAuthorizeURL($this->callback);
        header("location:{$code_url}");
    }

    function CallBackData()
    {
        $userinfo=null;
        $o = new \SaeTOAuthV2($this->appid, $this->appsecret);
        
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = $this->callback;
            try {
                $token = $o->getAccessToken('code', $keys);
                $c = new \SaeTClientV2($this->appid, $this->appsecret, $token['access_token']);
                $user_message = $c->show_user_by_id($token['uid']); // 根据ID获取用户等基本信息
                $userinfo['headimgurl'] = $user_message['avatar_large'];
                $userinfo['nickname'] = $user_message['name'];
                $userinfo['openid'] = $user_message['idstr'];
            } catch (OAuthException $e) {}
        }
        
        return $userinfo;
    }
}

?>