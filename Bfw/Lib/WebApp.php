<?php
namespace Lib;

// Bfw::import("App.Lang." . DOMIAN_VALUE . "." . LANG);
/**
 *
 * @author wangbo
 *         框架运行开始
 */
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

        //生成通讯账号密码
        if(isset($_GET['genratessh'])){
            $_keypath=DATA_DIR.DS."bfwssh.php";
            if(file_exists($_keypath)){
                echo "已经生成过";
            }else{
                if(file_put_contents($_keypath, "<?php \$_sshconfig=['name'=>'sddfd','pwd'=>'dddd'];?>")){
                    echo "生成成功";
                }
            }
            return;
        }
        // 开发者身份
        if (RUN_MODE == "D") {
            $_boins = Core::LoadClass("Lib\\BoDev");
            return $_boins->work($_controler, $_action, $_domian);
            // exit();
        }
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
                if (APIDOC_PWD == ""&&APIDOC_UNAME=="") {
                    $_bocodeins = Core::LoadClass("Lib\\BoApi");
                    $_bocodeins->Show();
                } else {
                    $_sesskey = SESS_ID . "\\api_doc_login{$_domian}";
                    if (BoCache::Cache($_sesskey) != "ok") {
                        if (IS_AJAX_REQUEST) {
                            if (BoReq::PostVal("username") == APIDOC_UNAME && BoReq::PostVal("password") == APIDOC_PWD) {
                                BoCache::Cache($_sesskey, "ok", 1800);
                                die("ok");
                            } else {
                                die(BoConfig::Config("Sys", "webapp", "System")['user_pwd_wrong']);
                            }
                        }
                        BoRes::View("login", "System", "v1", [
                            'refer' => URL
                        ]);
                        die();
                    } else {
                        BoCache::Cache($_sesskey, "ok", 1800);
                        $_bocodeins = Core::LoadClass("Lib\\BoApi");
                        $_bocodeins->Show();
                    }
                }
                exit();
            }
            if (CONTROL_VALUE == "" && ACTION_VALUE == "" && DOMIAN_VALUE == "") {
                BoRes::View("hello", "System", "v1");
                exit();
            }
            //路由转化
            if(isset($_GET['getbfwsoaactionlinkurl'])){
                $_urls=explode("|", base64_decode($_GET['getbfwsoaactionlinkurl']));
                if(count($_urls)>=4){
                    BoRes::Redirect(BoRes::Actlink($_urls[1],$_urls[2],$_urls[3],$_urls[0]));
                    return;
                }
            }

            $_boins = Core::LoadClass("Lib\\BoCustomer");
            return $_boins->work($_controler, $_action, $_domian);
        } else {
            die("unknowed runmode");
        }
    }
}
?>