<?php
namespace Lib;
use Lib\Util\FileUtil;

class BoApi 
{
    public function Show(){
        $_filelist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "Controler" . DS);
        $_con_act_array = array();
        foreach ($_filelist as $_file) {
            $_control_name = str_replace(".php", "", $_file);
            Bfw::import("App." . DOMIAN_VALUE . ".Controler." . $_control_name);
            $_control_name_dll = "App\\" . DOMIAN_VALUE . "\\Controler\\" . $_control_name;
            $r = new \reflectionclass($_control_name_dll);
            $_cont_act_a = [];
            foreach ($r->getmethods() as $key => $methodobj) {
                if ($methodobj->ispublic()) {
                    if ($methodobj->name != "__get") {
                        $_cont_act_a[] = array(
                            $methodobj->name,
                            str_replace("/**", "", $methodobj->getDocComment())
                        );
                    }
                }
            }
            $_con_act_array[str_replace("Controler_", "", $_control_name)] = $_cont_act_a;
        }
        Core::V("apilist", "System", "v1", [
            'con_act_array' => $_con_act_array
        ]);
    }
}
?>