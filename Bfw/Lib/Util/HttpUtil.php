<?php
namespace Lib\Util;

class HttpUtil
{

    /**
     * 异步发送，忽略服务的回应
     *
     * @param string $host
     * @param number $port
     * @param string $path
     * @param array $data
     * @param number $timeout
     * @return array
     */
    public static function AsynPost($host, $port, $path, $data, $timeout)
    {
        $sock = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (! $sock) {
            return array(
                "err" => true,
                "msg" => "$errstr($errno)"
            );
        }
    
        $_data = http_build_query($data);
        $out = "POST {$path} HTTP/1.1\r\n";
        $out .= "Host: {$host}\r\n";
        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-length: " . strlen($_data) . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "\r\n";
        $out .= "{$_data}\r\n";
        fwrite($sock, $out);
        fclose($sock);
        return array(
            "err" => false,
            "msg" => ""
        );
    }
    public static function HttpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
		if(stripos($url,"https://")!==FALSE){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $_errmsg = curl_error($ch);
            curl_close($ch);
            return array(
                "err" => true,
                "data" => $_errmsg
            );
        }
        curl_close($ch);
        return array(
            "err" => false,
            "data" => $output
        );
    }
}

?>