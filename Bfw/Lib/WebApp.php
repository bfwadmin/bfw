<?php
namespace Lib;
use Lib\Util\FileUtil;
// Bfw::import("App.Lang." . DOMIAN_VALUE . "." . LANG);
class WebApp extends WangBo
{

    private static $_instance = null;

    /**
     * 执行action
     *
     * @param string $str            
     */
    private Function ExecuteAction($str)
    {
        include ("data://text/plain," . urlencode($str));
    }
    // 或获得单例
    public static function getInstance()
    {
        if (is_null(self::$_instance) || ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 执行
     *
     * @param string $_controler            
     * @param string $_action            
     * @param string $_domian            
     * @throws Exception
     */
    public function Execute($_controler, $_action, $_domian)
    {
        // 监督者身份
        if (RUN_MODE == "M") {
            $_boins = Core::LoadClass("Lib\\BoMoniter");
            return $_boins->work($_controler, $_action, $_domian);
            // exit();
        }
        // 服务者
        if (RUN_MODE == "S") {
            $_boins = Core::LoadClass("Lib\\BoProvider");
            return $_boins->work($_controler, $_action, $_domian);
            // exit();
        }
        // 消费者
        if (RUN_MODE == "C") {
            if (SHOW_APIDOC && isset($_GET['getapidoc'])) {
                $_bocodeins = Core::LoadClass("Lib\\BoApi");
                $_bocodeins->Show();
                exit();
            }
            if (GEN_CODE && WEB_DEBUG && isset($_GET['gencode'])) {
                $_bocodeins = Core::LoadClass("Lib\\BoCode");
                $_bocodeins->Generate($_domian, isset($_GET['t']) ? $_GET['t'] : '', isset($_GET['o']) ? true : false);
                exit();
            }
          
            if (WEB_DEBUG && isset($_GET['console'])) {
                $_ins = Core::LoadClass("Lib\\BoGui", "console");
                $_ins->Run();
                exit();
            }
            if (WEB_DEBUG) {
                if( isset($_GET['apphandler'])|| isset($_POST['apphandler'])){
                    $_ins = Core::LoadClass("Lib\\BoApp",isset($_GET['apphandler'])?$_GET['apphandler']:$_POST['apphandler']);
                    $_ins->Run();
                    exit();
                }
            }
            if (WEB_DEBUG && isset($_GET['webide'])) {
                $_ins = Core::LoadClass("Lib\\BoGui", "webide");
                $_ins->Run();
                exit();
            }
            if (CONTROL_VALUE == "" && ACTION_VALUE == "" && DOMIAN_VALUE == "") {
                BoRes::View("hello", "System", "v1");
                exit();
            }
            $_boins = Core::LoadClass("Lib\\BoCustomer");
            return $_boins->work($_controler, $_action, $_domian);
        } else {
            die("unknowed runmode");
        }
    }
}
?>