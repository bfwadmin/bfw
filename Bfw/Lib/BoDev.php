<?php
namespace Lib;

/**
 * @author wangbo
 * 开发模式
 */
class BoDev
{

    const LOCAL_DEV = 1;
    const CLOUD_DEV = 2;

    // 注册到监督端
    public function work($_controler, $_action, $_domian)
    {
        if (GEN_CODE && isset($_GET['gencode'])) {
            $_bocodeins = Core::LoadClass("Lib\\BoCode");
            $_bocodeins->Generate($_domian, isset($_GET['t']) ? $_GET['t'] : '', isset($_GET['o']) ? true : false);
            exit();
        }
        if (isset($_GET['console'])) {
            $_ins = Core::LoadClass("Lib\\BoGui", "console");
            $_ins->Run();
            exit();
        }
        if (isset($_GET['webide'])) {
            $_ins = Core::LoadClass("Lib\\BoGui", "webide");
            $_ins->Run();
            exit();
        }
        if (isset($_GET['apphandler']) || isset($_POST['apphandler'])) {
            $_ins = Core::LoadClass("Lib\\BoApp", isset($_GET['apphandler']) ? $_GET['apphandler'] : $_POST['apphandler']);
            $_ins->Run();
            exit();
        }
    }
}
?>