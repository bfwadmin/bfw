<?php
namespace Lib\Util;

/**
 * @author wangbo
 * 其他辅助类
 */
class OtherUtil
{

    /**
     * 向asp程序发送数据
     *
     * @param string $_url
     * @param string $_postdata
     * @param string $_encode
     * @return noret
     */
    public static function AspService($_url, $_postdata, $_encode = "gb2312")
    {
        $ret = "";
        try {
            $ch = curl_init();
            // curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/x-www-form-urlencoded;'
            ));
            $retarr = array();
            foreach ($_postdata as $_key => $_val) {
                // $retarr [$_key] = iconv ( "UTF-8", $_encode, $_val );
                $retarr[$_key] = u2gb($_val);
            }
            $data = http_build_query($retarr);
            echo $data;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $ret = curl_exec($ch);
            // $lst ['info'] = curl_getinfo ( $ch );
            curl_close($ch);
        } catch (Exception $e) {
            LogR($e->getMessage());
        }
        return $ret;
    }

    // 向iphone用户推送信息
    /**
     *
     * @param string $deviceid
     * @param string $body
     * @return ret
     */
    public static function PushMsgToIphone($deviceid, $body)
    {
        try {
            $passphrase = "zpw123";
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', APP_ROOT . "/Cert/pk.pem");
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            if (! $fp) {
                return array(
                    'err' => true,
                    'msg' => '无法连接'
                );
            }
            $payload = json_encode($body);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceid) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));
            fclose($fp);
            if (! $result) {
                return array(
                    'err' => true,
                    'msg' => '无法写入'
                );
            } else {
                return array(
                    'err' => false,
                    'msg' => true
                );
            }
        } catch (Exception $e) {
            return array(
                'err' => true,
                'msg' => $e->getMessage()
            );
        }
    }
}

?>