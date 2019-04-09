<?php
namespace App\[[DOM]]\Service;

use Lib\BoService;
use App\[[DOM]]\Model\Model_Sms;
use Lib\Bfw;

/**
 *
 * @author Herry
 *         短信服务
 */
class Service_Sms extends BoService
{

    protected $_model = "Sms";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Sms
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function getkey()
    {
        return "123";
    }

    function Send($_mobile, $_cont, $_plantime = "")
    {
        $_ret = simplexml_load_string($this->sendSms($_mobile, $_cont));
        if ($_ret->resultcode == 0) {
            return Model_Sms::getInstance()->Insert([
                'issuccess' => 1,
                "atime" => time(),
                "mobile" => $_mobile,
                "msg" => $_cont
            ]);
        } else {
            return Bfw::RetMsg(true, $_ret->errordescription);
        }
    }

    private function sendSms($_mobile, $_cont, $_plan = "")
    {
        $_conf = Bfw::Config("Sms", "base");
        $datasend['uid'] = $_conf['uid'];
        $datasend['pw']=$_conf['pwd'];
        $datasend['ms'] = $_cont;
        $datasend['mb'] = $_mobile;
      //  $datasend['ex'] = '';
        $datasend['dm'] = $_plan; // 定时时间，为空表示立即发送。格式YYYY-MM-DD HH:MM:SS
       // $datasend['tm'] = date("YmdHis",time());
        //$datasend['pw'] = md5($_conf['pwd']. $datasend['tm']);
        //var_dump($datasend);
        // 判断url是否合法
                                        // if ($_conf['send'] === 'post')
                                        // return false;
        
        if (function_exists('file_get_contents')) {
            if ($_conf['send'] === 'post') {
                $datasend = http_build_query($datasend);
                $opts = array(
                    'http' => array(
                        'method' => "POST",
                        'header' => "Content-type:application/x-www-form-urlencoded\r\n" . "Content-length:" . strlen($datasend) . "\r\n" . "Cookie: foo=bar\r\n" . "\r\n",
                        'content' => $datasend
                    )
                );
                
                $context = stream_context_create($opts);
                $reponse = file_get_contents($_conf['url'], false, $context);
            } else {
            	$datasend = http_build_query($datasend);
            	//var_dump($_conf['url']."?".$datasend);
                $reponse = file_get_contents($_conf['url']."?".$datasend);
            }
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $_conf['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if ($_conf['send'] === 'post' && $datasend) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $datasend);
            }
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $reponse = curl_exec($ch);
            // 关闭句柄
            curl_close($ch);
        }
        //var_dump($reponse);
       // exit();
        return $reponse;
    }
    
    /*
    private function sendSms($_mobile, $_cont, $_plan = "")
    {
    	$_conf = Bfw::Config("Sms", "base");
    	$datasend['username'] = $_conf['uid'];
    	$datasend['password'] = $_conf['pwd'];
    	$datasend['content'] = $_cont;
    	$datasend['mobile'] = $_mobile;
    	$datasend['planTime'] = $_plan; // 定时时间，为空表示立即发送。格式YYYY-MM-DD HH:MM:SS
    
    	// 判断url是否合法
    	// if ($_conf['send'] === 'post')
    	// return false;
    
    	if (function_exists('file_get_contents')) {
    		if ($_conf['send'] === 'post') {
    			$datasend = http_build_query($datasend);
    			$opts = array(
    					'http' => array(
    							'method' => "POST",
    							'header' => "Content-type:application/x-www-form-urlencoded\r\n" . "Content-length:" . strlen($datasend) . "\r\n" . "Cookie: foo=bar\r\n" . "\r\n",
    							'content' => $datasend
    					)
    			);
    
    			$context = stream_context_create($opts);
    			$reponse = file_get_contents($_conf['url'], false, $context);
    		} else {
    			$reponse = file_get_contents($_conf['url']);
    		}
    	} else {
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $_conf['url']);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		if ($_conf['send'] === 'post' && $datasend) {
    			curl_setopt($ch, CURLOPT_POST, 1);
    			curl_setopt($ch, CURLOPT_HEADER, 0);
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $datasend);
    		}
    		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    		$reponse = curl_exec($ch);
    		// 关闭句柄
    		curl_close($ch);
    	}
    
    	return $reponse;
    }*/
}
?>