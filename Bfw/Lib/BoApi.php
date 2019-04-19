<?php
namespace Lib;

use Lib\Util\FileUtil;

/**
 * @author wangbo
 * 接口api显示
 */
class BoApi
{

    private function getVal($str, $tag)
    {
        if (preg_match("/<{$tag}>(.*?)<\/{$tag}>/", $str, $mat)) {
            return $mat[1];
        }
        return "";
    }

    public function Show()
    {
        $_filelist = FileUtil::getFileListByDir(APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "Controler" . DS);
        $_readmefile = APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "readme";
        $_readmedata = "";
        if (file_exists($_readmefile)) {
            $_readmedata = file_get_contents($_readmefile);
        }
        $_con_act_array = array();
        foreach ($_filelist as $_file) {
            $_control_name = str_replace(".php", "", $_file);
            Core::ImportClass("App." . DOMIAN_VALUE . ".Controler." . $_control_name);
            $_control_name_dll = "App\\" . DOMIAN_VALUE . "\\Controler\\" . $_control_name;
            $r = new \reflectionclass($_control_name_dll);
            $_cont_act_a = [];
            $_mdoc = str_replace("*", "</br>", str_replace("*/", "", str_replace("/**", "", $r->getDocComment())));
            $_title = $this->getVal($_mdoc, "t");
            $_desc = $this->getVal($_mdoc, "d");
            $_cont_act_a[] = [
                "doccomment" => [
                    $_title,
                    $_desc
                ]
            ];
            foreach ($r->getmethods() as $key => $methodobj) {
                if ($methodobj->ispublic()) {
                    if ($methodobj->name != "__get") {
                        $_doc = str_replace("*", "</br>", str_replace("*/", "", str_replace("/**", "", $methodobj->getDocComment())));
                        $_title = $this->getVal($_doc, "t");
                        $_desc = $this->getVal($_doc, "d");
                        $_cont_act_a[] = array(
                            $methodobj->name,
                            $_title,
                            $_desc,
                            $_doc
                        );
                    }
                }
            }
            $_con_act_array[str_replace("Controler_", "", $_control_name)] = $_cont_act_a;
        }
        BoRes::View("apilist", "System", "v1", [
            'con_act_array' => $_con_act_array,
            "readmedata" => $_readmedata
        ]);
    }
}
?>