<?php
namespace Lib\Util;

/**
 *
 * @author wangbo
 *         加密辅助类
 */
class CryptionUtil
{

    /**
     * 简单加密
     * @param unknown $data
     * @param unknown $key
     */
    public static function simpleencrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i ++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x ++;
        }
        for ($i = 0; $i < $len; $i ++) {
            $str .= chr(ord($data{$i}) + ord($char{$i}));
        }
        echo base64_encode($str);
    }

    /**
     * 简单解密
     * @param unknown $data
     * @param unknown $key
     */
    public static function simpledecrypt($data, $key)
    {
        $char = '';
        $str = '';
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        for ($i = 0; $i < $len; $i ++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x ++;
        }
        for ($i = 0; $i < $len; $i ++) {
            $str .= chr(ord($data{$i}) - ord($char{$i}));
        }
        echo $str;
    }

    /**
     * ecb加密
     * @param unknown $input
     * @param unknown $key
     */
    public static function ecbencrypt($input, $key)
    {
        return strtr(base64_encode(mcrypt_ecb(MCRYPT_DES, $key, $input, MCRYPT_ENCRYPT)), '+/=', '-_,');
    }

    /**
     * ecb解密
     * @param unknown $input
     * @param unknown $key
     */
    public static function ecbdecrypt($input, $key)
    {
        return trim(mcrypt_ecb(MCRYPT_DES, $key, base64_decode(strtr($input, '-_,', '+/=')), MCRYPT_DECRYPT));
    }
}
?>