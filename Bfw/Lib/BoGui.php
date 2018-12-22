<?php
namespace Lib;

class BoGui
{

    private $_mode = "";

    public function __construct($_mode = "console")
    {
        $this->_mode = $_mode;
    }

    private function uploadapp($_name,$_to_temp=false)
    {
        set_time_limit(0); // 无时间限制
        $_ba = new BoApp();
        if ($_ba->upload($_name,$_to_temp)) {
            echo "ok";
        } else {
            echo "fali";
        }
    }

    private function getapp($_name, $localname = "", $_dbinfo,$_from_temp=false)
    {
        set_time_limit(0); // 无时间限制
        $_ba = new BoApp();
        if ($_ba->download($_name, $localname, $_dbinfo,$_from_temp)) {
            echo "ok";
        } else {
            echo "fali";
        }
    }

    private function login($_uname, $_pwd)
    {
        $_ba = new BoApp();
        $_ret = $_ba->login($_uname, $_pwd);
        if ($_ret) {
            Core::Cache("app_server_uid", $_ret,0);
            echo "login success";
        } else {
            echo "fali";
        }
    }

    private function register($_uname, $_pwd)
    {
        $_ba = new BoApp();
        $_ret = $_ba->register($_uname, $_pwd);
        if ($_ret) {
            Core::Cache("app_server_uid", $_ret, 0);
            echo "register success";
        } else {
            echo "fali";
        }
    }

    private function initapp($_appname, $_dbinfo)
    {
        $_bocodeins = Core::LoadClass("Lib\\BoCode");
        return $_bocodeins->InitApp($_appname, $_dbinfo);
    }

    private function addcont($_appname, $_contname)
    {
        $_bocodeins = Core::LoadClass("Lib\\BoCode");
        return $_bocodeins->AddCont($_appname, $_contname);
    }

    public function Run()
    {
        if (isset($_GET['login'])) {
            if (isset($_GET['login'])) {
                $loginpara = explode("|", $_GET['login']);
                if (count($loginpara) >= 2) {
                    $this->login($loginpara[0], $loginpara[1]);
                } else {
                    echo "wrong";
                }
                exit();
            }
        }
        if (isset($_GET['register'])) {
            if (isset($_GET['register'])) {
                $loginpara = explode("|", $_GET['register']);
                if (count($loginpara) >= 2) {
                    $this->register($loginpara[0], $loginpara[1]);
                } else {
                    echo "wrong";
                }
                exit();
            }
        }
        if (isset($_GET['uploadapp'])) {
            $_uid = Core::Cache("app_server_uid");
            if ($_uid != "") {
                $this->uploadapp($_GET['uploadapp'],isset($_GET['totemp'])?true:false);
            } else {
                echo "login first";
            }
            exit();
        }
        if (isset($_GET['initapp'])) {
            if (isset($_GET['dbinfo'])) {
                $dbpara = explode("|", $_GET['dbinfo']);
                if(isset($_GET['tempid'])){
                    $this->getapp($_GET['tempid'], $_GET['initapp'], $dbpara,true);
                }else{
                    if ($this->initapp($_GET['initapp'], $dbpara)) {
                        echo "ok";
                    } else {
                        echo "fail";
                    }
                }
               
            } else {
                echo "dbconfig wrong";
            }
            exit();
        }
        if (isset($_GET['addcont'])) {
            if (isset($_GET['addcont'])) {
                $para = explode("|", $_GET['addcont']);
                if (count($para) >= 2) {
                    if ($this->addcont($para[0], $para[1])) {
                        echo "ok";
                    } else {
                        echo "fail";
                    }
                }
            } else {
                echo "cont wrong";
            }
            exit();
        }
        if (isset($_GET['getapp']) ) {
            $_uid = Core::Cache("app_server_uid");
            if ($_uid != "") {
                if (isset($_GET['dbinfo'])) {
                    $dbpara = explode("|", $_GET['dbinfo']);
                    if (count($dbpara) == 4) {
                        $this->getapp($_GET['getapp'],  isset($_GET['appname'])?$_GET['appname']:$_GET['getapp'], $dbpara,isset($_GET['appname'])?true:false);
                    } else {
                        echo "dbconf wrong";
                    }
                }
            } else {
                echo "login first";
            }
            
            exit();
        }
        $_bocodeins = Core::LoadClass("Lib\\BoCheck");
        if ($_bocodeins::CheckDir()) {
            if ($this->_mode == "console") {
                Core::V("console", "System", "v1");
                exit();
            }
            $dirArr = scandir(APP_DIR);
            foreach ($dirArr as $key => $item) {
                if ($item == "." || $item == "..") {
                    unset($dirArr[$key]);
                }
            }
            Core::V("webide", "System", "v1", [
                "app_list_data" => $dirArr
            ]);
            exit();
        }
    }
}
?>