<?php
namespace Lib\Log;

use Lib\Bfw;
use Lib\BoErrEnum;
use Lib\Registry;
use Lib\Util\HttpUtil;

class LogServer implements BoLogInterface
{

    private static $_instance = null;

    static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new LogServer();
        }
        return self::$_instance;
    }

    public function Log($_word, $_tag, $_level = BoErrEnum::BFW_INFO)
    {
        $_info = &Registry::getInstance()->get("log_server_info");
        $_info[] = array(
            "info" => $_word,
            "tag" => $_tag,
            "level" => $_level,
            "stime" => time(),
            "source" => $_SERVER['SERVER_ADDR'] . "|" . INSTANCE_NAME
        );
        Registry::getInstance()->set("log_server_info", $_info);
    }

    public function __destruct()
    {
        $_log_server_array = Bfw::Config("Log", "server", "System");
        $_log_server_info_array = &Registry::getInstance()->get("log_server_info");
        $i = 0;
        $_weight = array();
        foreach ($_log_server_array as $_item) {
            $_weight[$i] = isset($_item["weight"]) ? $_item["weight"] : 1;
            $i ++;
        }
        $_getindex = Bfw::Routeroll($_weight);
        $_ret = HttpUtil::AsynPost($_log_server_array[$_getindex]['host'], $_log_server_array[$_getindex]['port'], $_log_server_array[$_getindex]['path'], array(
            "log" => serialize($_log_server_info_array)
        ), 3);
        
        if ($_ret['err']) {
            LogFiles::getInstance()->Log($_ret['msg'], "logserverfailed", 3);
        }
    }
}

?>