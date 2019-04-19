<?php
namespace Lib\Util;

use Lib\Bfw;
use Lib\BoCache;
use Lib\Core;

/**
 * @author wangbo
 * html辅助类
 */
class HtmlUtil
{

    private static function ArrayToOption($_attr)
    {
        $_out = "";
        if (is_array($_attr)) {
            foreach ($_attr as $_a => $_v) {
                if (is_array($_v)) {

                    if ($_a === "style") {
                        foreach ($_v as $_k1 => $_v1) {
                            $_out .= $_k1 . ':' . $_v1 . ';';
                        }
                    }
                }
                if ($_a === "checked") {
                    if ($_v != "") {
                        $_out .= $_a . '=' . '"' . $_v . '" ';
                    }
                } else {
                    $_out .= $_a . '=' . '"' . $_v . '" ';
                }
            }
        }
        return $_out;
    }

    /**
     * 添加class
     *
     * @param array $_option
     * @param string $_classname
     * @return unknown
     */
    public static function AddClass($_option, $_classname)
    {
        $_option['class'] = isset($_option['class']) ? "\"" . str_replace("\"", "", $_option['class']) . " " . $_classname . "\"" : '"' . $_classname . '"';
        return $_option;
    }

    /**
     * 移除class
     *
     * @param array $_option
     * @param string $_classname
     * @return unknown
     */
    public static function RemoveClass($_option, $_classname)
    {
        $_option['class'] = isset($_option['class']) ? str_replace($_classname, "", $_option['class']) : '""';
        return $_option;
    }

    /**
     * 添加css样式
     *
     * @param array $_option
     * @param string $_style
     * @return unknown
     */
    public static function AddStyle($_option, $_style)
    {
        $_option['style'] = isset($_option['style']) ? "\"" . str_replace("\"", "", $_option['class']) . " " . $_style . "\"" : '"' . $_style . '"';
        return $_option;
    }

    /**
     * 移除css样式
     *
     * @param array $_option
     * @param array $_stylename
     * @return string
     */
    public static function RemoveStyle($_option, $_stylename)
    {
        if (isset($_option['style'])) {
            $_stylearr = explode(";", $_option['style']);
            foreach ($_stylename as $_style) {
                if (isset($_stylearr[$_style])) {
                    unset($_stylearr[$_style]);
                }
            }
            $_option['style'] = implode(";", $_stylearr);
        }
        return $_option;
    }

    /**
     * 加载css
     *
     * @param string $_path
     * @param array $_option
     * @return string
     */
    public static function ImportCss($_path, $_option = null)
    {
        return '<link ' . self::ArrayToOption($_option) . ' href="' . str_replace("@weburl", STATIC_FILE_PATH, $_path) . '" rel="stylesheet" type="text/css" />';
    }

    /**
     * 加载js文件
     *
     * @param string $_path
     * @param array $_option
     * @return string
     */
    public static function ImportJs($_path, $_option = null)
    {
        return '<script ' . self::ArrayToOption($_option) . ' type="text/javascript" src="' . str_replace("@weburl", STATIC_FILE_PATH, $_path) . '"></script>';
    }

    /**
     * 提前加载js文件
     *
     * @param string $_path
     * @param array $_option
     * @return string
     */
    public static function PreJs($_path, $_option = null)
    {
        Core::S("pre_js_filelist", Core::G("pre_js_filelist") . '<script ' . self::ArrayToOption($_option) . ' type="text/javascript" src="' . str_replace("@weburl", STATIC_FILE_PATH . DOMIAN_VALUE, $_path) . '"></script>');
    }

    /**
     * 提前 加载css
     *
     * @param string $_path
     * @param array $_option
     * @return string
     */
    public static function Precss($_path, $_option = null)
    {
        Core::S("pre_css_filelist", Core::G("pre_css_filelist") . '<link ' . self::ArrayToOption($_option) . ' href="' . str_replace("@weburl", STATIC_FILE_PATH . DOMIAN_VALUE, $_path) . '" rel="stylesheet" type="text/css" />');
    }

    /**
     * 输出button
     *
     * @param string $_name
     * @param string $_val
     * @param array $_option
     * @return string
     */
    public static function Button($_name, $_val, $_option = null)
    {
        return '<button  ' . self::ArrayToOption($_option) . ' name="' . $_name . '" >' . $_val . '</button>';
    }

    /**
     * 输出input标签
     *
     * @param string $_name
     * @param string $_val
     * @param array $_option
     * @return string
     */
    public static function Input($_name, $_val, $_option = null)
    {
        return '<input ' . self::ArrayToOption($_option) . ' value="' . $_val . '" name="' . $_name . '" />';
    }

    /**
     * 输出textarea
     *
     * @param string $_name
     * @param string $_val
     * @param array $_option
     * @return string
     */
    public static function Textarea($_name, $_val, $_option = null)
    {
        return '<textarea ' . self::ArrayToOption($_option) . '  " name="' . $_name . '" >' . $_val . '</textarea>';
    }

    /**
     * 输出html tag标签
     *
     * @param string $_name
     * @param string $_html
     * @param array $_option
     * @return string
     */
    public static function Tag($_name, $_html, $_option = null)
    {
        return '<' . $_name . ' ' . self::ArrayToOption($_option) . '>' . $_html . '<' . $_name . '>';
    }

    /**
     * 输出图片标签
     *
     * @param string $_src
     * @param array $_option
     * @return string
     */
    public static function Img($_src, $_option = null)
    {
        return '<img ' . self::ArrayToOption($_option) . ' src="' . str_replace("@weburl", IMG_FILE_PATH, $_src) . '" alt="' . $_alt . '" />';
    }

    /**
     * 添加js代码
     *
     * @param string $_code
     * @param string $_dependmodule
     *            依赖js
     * @param string $_relatedcss
     *            依赖css
     * @param array $_option
     * @return string
     */
    public static function Script($_code, $_dependmodule = "", $_relatedcss = "", $_option = null)
    {
        return '<script ' . self::ArrayToOption($_option) . ' type="text/javascript" >$(document).ready(function(){$.Bfw.loadjs("' . $_dependmodule . '","' . $_relatedcss . '",function(){' . $_code . '})});</script>';
    }

    /**
     * 输出js配置
     *
     * @param array $_conifg
     * @param array $_option
     * @return string
     */
    public static function Config($_conifg, $_option = null)
    {
        return '<script ' . self::ArrayToOption($_option) . ' type="text/javascript" >var _bfw_config=' . json_encode($_conifg) . ';</script>';
    }

    /**
     * 添加css代码
     *
     * @param string $_code
     * @param array $_option
     * @return string
     */
    public static function Style($_code, $_option = null)
    {
        return '<style  ' . self::ArrayToOption($_option) . ' type=\"text/css\" >' . $_code . '</style>';
    }

    /**
     * 返回token input hidden
     *
     * @return string
     */
    public static function TokenInput()
    {
        return "<input type='hidden' name='" . FORM_TOKEN_NAME . "' value='" . Bfw::GetTokenVal() . "' />";
    }

    /**
     * html去义化
     *
     * @param string $text
     * @return string
     */
    public static function Decode($text)
    {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }

    /**
     * 恢复html
     *
     * @param string $text
     * @return string
     */
    public static function Encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, "utf-8");
    }

    /**
     * Radio(名称，数据源，选择值，属性)
     * 返回radio标签
     *
     * @param string $name
     * @param array $arr
     * @param string $val
     * @param array $_option
     * @return string
     */
    public static function Radio($name, $arr, $val = "", $_option = null)
    {
        if (is_array($arr)) {
            $string = '';
            foreach ($arr as $key => $value) {
                if ($key == $val) {
                    $string .= '<label><input type="radio" name="' . $name . '"
				          value="' . $key . '" checked=true ' . self::ArrayToOption($_option) . ' /> ' . $value . '</label>';
                } else {
                    $string .= '<label><input type="radio" name="' . $name . '"
				          value="' . $key . '" ' . self::ArrayToOption($_option) . '  />' . $value . '</label>';
                }
            }
            return $string;
        }
    }

    /**
     * 多选框
     * Checkbox(名称,数据源,选择值，属性)
     *
     * @param string $name
     * @param array $arr
     * @param array $val
     * @param array $_option
     * @return string
     */
    public static function Checkbox($name, $arr, $val = array(), $_option = null)
    {
        if (is_array($arr)) {
            $string = '';
            foreach ($arr as $key => $value) {
                if (in_array($key, $val)) {
                    $string .= '<label><input type="checkbox" name="' . $name . '"
				value="' . $key . '" checked=true ' . self::ArrayToOption($_option) . '/><span>' . $value . '</span></label>';
                } else {
                    $string .= '<label><input type="checkbox" name="' . $name . '"
				value="' . $key . '"  ' . self::ArrayToOption($_option) . ' /><span>' . $value . '</span></label>';
                }
            }
            return $string;
        }
    }

    /**
     * 下拉列表Option(名称，数据源，选择值，属性))
     *
     * @param string $name
     * @param array $arr
     * @param string $val
     * @param array $_option
     * @return string
     */
    public static function Option($name, $arr, $val = "", $_option = null)
    {
        if (is_array($arr)) {
            $html = '';
            $html .= '<select  ' . self::ArrayToOption($_option) . '  name="' . $name . '">';
            foreach ($arr as $key => $value) {
                if ($key == $val) {

                    $html .= '<option value="' . $key . '" selected >' . $value . '</option>';
                } else {
                    $html .= '<option value="' . $key . '" >' . $value . '</option>';
                }
            }
            $html .= '</select>';
            return $html;
        }
    }

    /**
     * BeginForm(名称, form提交对象, 提交方式，属性)
     *
     * @param string $name
     * @param string $action
     * @param string $method
     * @param array $_option
     * @return string
     */
    public static function BeginForm($name = "bfw_form", $action = "", $method = 'post', $_option = null)
    {
        return "<form method='" . $method . "' name='" . $name . "'  action='" . $action . "' " . self::ArrayToOption($_option) . ">";
    }

    /**
     * 结束form
     *
     * @return string
     */
    public static function EndForm()
    {
        return "</form>";
    }
}

?>