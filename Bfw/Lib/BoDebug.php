<?php
namespace Lib;

use Lib\Registry;

class BoDebug
{
    /**
     * 调试信息输出
     * @param unknown $str
     */
    public static function Info($str){
        if (defined("WEB_DEBUG")) {
            if (WEB_DEBUG) {
                $_info = &Registry::getInstance()->get("debug_info");
                $_info[] = array(
                    microtime(true),
                    $str . ",mem:" . (memory_get_usage() / 1024) . "KB"
                );
                Registry::getInstance()->set("debug_info", $_info);
            }
        }
    }
    
    /**
     * 计时开始
     *
     * @param string $tag
     *            计时标签
     */
    public static function TickStart($tag)
    {
        if (preg_match("/^[A-Za-z]/", $tag)) {
            $_tag = md5($tag);
        } else {
            $_tag = $tag;
        }
        $_ts = microtime(true);
        Registry::getInstance()->set("bo_nowtime_" . $_tag, $_ts);
        Registry::getInstance()->set("bo_nowmemo_" . $_tag, memory_get_usage());
        $_info = &Registry::getInstance()->get("debug_info");
        $_info[] = array(
            $_ts,
            $tag
        );
        Registry::getInstance()->set("debug_info", $_info);
        // LogR($_ts . ":" . $msg);
    }
    
    /**
     * 计时结束
     *
     * @param string $tag
     *            计时标签
     */
    public static function TickStop($tag)
    {
        if (preg_match("/^[A-Za-z]/", $tag)) {
            $_tag = md5($tag);
        } else {
            $_tag = $tag;
        }
        $_te = microtime(true);
        $_ts = &Registry::getInstance()->get("bo_nowtime_" . $_tag);
        $_tm = &Registry::getInstance()->get("bo_nowmemo_" . $_tag);
    
        $_info = &Registry::getInstance()->get("debug_info");
        if ("" == $_ts) {
            $_info[] = array(
                $_te,
                $tag . ',please set TickStart first'
            );
        } else {
            $_info[] = array(
                $_te,
                $tag . ',spend ' . round($_te - $_ts, 3) . "s" . " cost mem:" . ((memory_get_usage() - $_tm) / 1024) . "KB"
            );
        }
        Registry::getInstance()->set("debug_info", $_info);
    
        // LogR($_te . ":" . $msg . ",耗时" . round($_te - $_ts, 3) . "s");
    }

}

?>