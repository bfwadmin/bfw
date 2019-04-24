<?php
namespace Lib;

use Lib\Registry;

/**
 *
 * @author wangbo
 *         调试类
 */
class BoDebug
{

    /**
     * 导出提示信息
     *
     * @param unknown $_v
     * @return multitype:unknown
     */
    public static function ExportVar($_v, $_obj)
    {
        $_ret = [];
        foreach ($_v as $_item => $_val) {
            if ($_item != "_debug_cont_file" && $_item != "_debug_g_file" && $_item != "_control_file") {
                $_ret["上下文变量"][$_item] = $_val;
            }
        }

        $_ret["CLASS"] = var_export($_obj, true);
        $_ret["SESSION"] = $_SESSION;
        $_ret["POST"] = $_POST;
        $_ret["GET"] = $_GET;
        $_ret["COOKIE"] = $_COOKIE;
        $_ret["REQUEST"] = $_REQUEST;
        $_ret["SERVER"] = $_SERVER;
        $_ret["ENV"] = $_ENV;
        $_ret["FILES"] = $_FILES;
        $_ret["GLOBALS"] = $GLOBALS;
        return $_ret;
    }

    /**
     * 断点调试路径
     */
    public static function getDebugfile($classpath)
    {
        if (WEB_DEBUG) {
            // 断电调试
            $_debug_file = APP_ROOT . DS . "App" . DS . "file.debug";
            $_debug_arr = [];
            if (file_exists($_debug_file)) {
                $_debug_arr = unserialize(file_get_contents($_debug_file));
            }
            if (in_array($classpath, $_debug_arr)) {
                $classpath = $classpath . ".debug";
            }
        }
        return $classpath;
    }

    /**
     * 调试信息输出
     *
     * @param unknown $str
     */
    public static function Info($str)
    {
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

    public static function DebugJson($_import_info_arr, $_debug_info_arr, $_spendtime, $_log_toserver, $_totalmem, $_file = "debug")
    {
        return "~_~_~_~_~_~" . json_encode([
            'import_info' => $_import_info_arr,
            "debug_info" => $_debug_info_arr,
            "spendtime" => $_spendtime,
            "islogserver" => $_log_toserver,
            "totalmem" => $_totalmem
        ]);
    }

    public static function DebugHtml($_import_info_arr, $_debug_info_arr, $_spendtime, $_log_toserver, $_totalmem, $_file = "debug")
    {
        ob_start();
        BoRes::View($_file, "System", "v1", [
            'import_info' => $_import_info_arr,
            "debug_info" => $_debug_info_arr,
            "spendtime" => $_spendtime,
            "islogserver" => $_log_toserver,
            "totalmem" => $_totalmem
        ]);
        $_cont = ob_get_clean();
        ob_start();
        return $_cont;
    }

    public static function DebugEcho($spendtime, $isajax = false)
    {
        $_import_info = &Registry::getInstance()->get("import_info");
        $_debug_info = &Registry::getInstance()->get("debug_info");
        if (RUN_MODE == "S") {
            return array(
                "import_file" => $_import_info,
                "debug_info" => $_debug_info,
                "spend_time" => $spendtime,
                "log_toserver" => LOG_TO_SERVER,
                "totalmem" => memory_get_usage()
            );
        }
        if ($isajax) {
            echo self::DebugJson($_import_info, $_debug_info, $spendtime, LOG_TO_SERVER, memory_get_usage());
        } else {
            echo self::DebugHtml($_import_info, $_debug_info, $spendtime, LOG_TO_SERVER, memory_get_usage());
        }
    }

    /**
     * 日志记录
     *
     * @param string $word
     * @param string $tag
     *            标签
     * @param string $level
     *            级别，0 一般 1 有风险 2 较严重，3非常严重 4 危机到系统安全
     */
    public static function LogR($word, $tag = "defaut", $level = 0)
    {
        if (! is_string($word)) {
            $word = var_export($word, true);
        }
        if (defined("LOG_HANDLER_NAME")) {
            $_classname = "Lib\\Log\\" . LOG_HANDLER_NAME;
            $_classname::getInstance()->Log($word, $tag, $level);
        } else {
            echo $word;
        }
    }

    /**
     * 检查php文件语法是否正确
     *
     * @param unknown $file_name
     * @param string $error_message
     * @return boolean
     */
    public static function CheckPhpOk($file_name, &$error_message = null)
    {
        $file_content = file_get_contents($file_name);

    }


}

?>