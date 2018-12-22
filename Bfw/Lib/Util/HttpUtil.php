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

    public static function HttpPost($_url, $_postdata)
    {
        // 初始化
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $_url);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $_postdata);
        // 执行命令
        $data = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);
        // 显示获得的数据
        return $data;
    }

    public static function HttpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
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

    /**
     * CURL 上传文件
     * 
     * @param $url 处理上传文件的url            
     * @param array $post_data
     *            post 传递的参数
     * @param int $timeout
     *            请求超时时间
     * @return array|bool
     */
    public static function Upload($url, $post_data = array(), $timeout = 6000)
    {
        $ch = curl_init();
        $headers = array(
            "bfw: 001"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1); // CURL_SSLVERSION_TLSv1
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