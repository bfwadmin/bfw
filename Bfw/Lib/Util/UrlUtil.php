<?php
namespace Lib\Util;

class UrlUtil
{

    /**
     * 添加url
     * 
     * @param string $_key            
     * @param string $_val            
     * @param string $_url            
     * @return string
     */
    public static function addpara($_key, $_val, $_url)
    {
        if (ROUTETYPE == 2) {
            if (preg_match("/{$_key}\/([^\/]+)/", $_url, $match)) {
                return str_replace($match[0], str_replace($match[1], $_val, $match[0]), $_url);
            } else {
                return rtrim($_url, "/") . "/" . $_key . '/' . $_val;
            }
        }
        if (ROUTETYPE == 1) {
            if (strpos($_url, "?") > 0) {
                parse_str(parse_url($_url)['query'], $parr);
                $_url = str_replace(parse_url($_url)['query'], "", $_url);
                if (isset($parr[$_key])) {
                    $parr[$_key] = $_val;
                }
                return $_url . http_build_query($parr);
                // return rtrim($url, "&") . "&" . $key . '=' . $val;
            } else {
                return $_url . "?" . $_key . '=' . $_val;
            }
        }
        return '';
    }

    /**
     * 获得相对url地址
     *
     * @return mixed|Ambigous <string, unknown>
     */
    public static function getrelativepath()
    {
        if (isset($_SERVER['PHP_SELF'])) {
            return str_replace($_SERVER["SCRIPT_NAME"], "", $_SERVER['PHP_SELF']);
        } else {
            return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "";
        }
    }

    /**
     * 获得当前url前缀 http://example.com
     *
     * @return string
     */
    public static function getbase()
    {
        $_host = "http";
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] === 1 || $_SERVER['HTTPS'] === 'on' || $_SERVER['SERVER_PORT'] == 443) {
                $_host = "https";
            }
        }
        if (isset($_SERVER["HTTP_HOST"])) {
            $_host .= "://" . $_SERVER["HTTP_HOST"];
        }
        if (isset($_SERVER["SERVER_PORT"])) {
            if ($_SERVER["SERVER_PORT"] != 80) {
                $_host .= ':' . $_SERVER["SERVER_PORT"];
            }
        }
        return $_host;
    }

    /**
     * 获取当前url
     *
     * @return string
     */
    public static function geturl()
    {
        $url = self::getbase();
        $url .= isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
        return $url;
    }

    /**
     * 获取ip
     *
     * @return string
     */
    public static function getip()
    {
        $cip = "";
        
        if (! empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (! empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (! empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        // echo $cip;
        return $cip;
    }
}

?>