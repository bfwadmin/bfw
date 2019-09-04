<?php
namespace Lib\Util;

/**
 * @author wangbo
 * 数组辅助类
 */
class ArrayUtil
{

    static function ArrayReplace($_arr, $_replace)
    {
        if (is_array($_arr)) {
            foreach ($_arr as &$item) {
                $item = $_replace;
            }
        }
        return $_arr;
    }

    static function Toxml($arr, $dom = 0, $item = 0)
    {
        if (! $dom) {
            $dom = new \DOMDocument("1.0");
        }
        if (! $item) {
            $item = $dom->createElement("root");
            $dom->appendChild($item);
        }
        foreach ($arr as $key => $val) {
            $itemx = $dom->createElement(is_string($key) ? $key : "item");
            $item->appendChild($itemx);
            if (! is_array($val)) {
                $text = $dom->createTextNode($val);
                $itemx->appendChild($text);
            } else {
                self::Toxml($val, $dom, $itemx);
            }
        }
        return $dom->saveXML();
    }

    /**
     * 数组排序筛选ArrayUtil::Select($user, ["name","ddd"],["name"=>SORT_ASC,"ddd"=>SORT_ASC])
     *
     * @param array $_array
     * @param array $_fields
     * @param string $_orderby
     * @return mixed|Ambigous <NULL, unknown>
     */
    public static function Select(array &$_array, array $_fields, $_orderby = null)
    {
        if (is_array($_array)) {
            $_ret = null;
            $i = 0;
            $_sortitem = null;
            foreach ($_array as $_item) {
                if (is_array($_orderby)) {
                    foreach ($_orderby as $_sitem => $_sp) {
                        $_sortitem[$_sitem][$i] = $_item[$_sitem];
                    }
                }

                $_iret = null;
                foreach ($_fields as $_i) {

                    if (isset($_item[$_i])) {
                        $_iret[$_i] = $_item[$_i];
                    }
                }
                $_ret[] = $_iret;
                $i ++;
            }
            if (is_array($_orderby)) {
                $_para = null;
                foreach ($_orderby as $_sitem => $_sp) {
                    $_para[] = $_sortitem[$_sitem];
                    $_para[] = $_sp;
                }
                $_para[] = &$_ret;

                call_user_func_array("array_multisort", $_para);
                return array_pop($_para);
            } else {
                return $_ret;
            }
        }
    }

    /**
     * 获取多级数组中某个键名值(废弃勿用)
     *
     * @param array $_array
     * @param object $_para
     * @return void|multitype:
     */
    public static function GetVal(array &$_array, $_para)
    {
        if (is_callable($_para)) {
            return array_map($_para, [
                $_array
            ])[0];
        } else {
            $_arraylevel = explode(".", $_para);
            if (count($_arraylevel) > 0) {
                foreach ($_array as $_item) {
                    if (isset($_item[0])) {
                        if (count($_arraylevel) > 1) {} else {
                            return;
                        }
                    }
                }
            }
        }
    }

    public static function ConvertToOne(&$_arr)
    {
        $ret = array();
        if (is_array($_arr)) {
            foreach ($_arr as $_a) {
                if (is_array($_a)) {
                    $_at = array_values($_a);
                    $ret[$_at[0]] = $_at[1];
                }
            }
        }

        return $ret;
    }

    /**
     * 二维转一维数组
     * @param unknown $_arr
     * @return multitype:NULL
     */
    public static function MutiToOne(&$_arr)
    {
        $ret = array();
        if (is_array($_arr)) {
            foreach ($_arr as $_a) {
                if (is_array($_a)) {
                    array_push($ret, array_values($_a));
                }
            }
        }
        return $ret;
    }

    public static function Validate(&$_sarr, &$_varr, &$_obj)
    {
        if (isset($_varr)) {
            if (is_array($_varr)) {
                foreach ($_varr as $_rule) {

                    switch ($_rule[1]) {
                        case "chinese":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^[\\u4e00-\\u9fa5],{0,}$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "money":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^([0-9]+)(.[0-9]{1,2})?$/", $_sarr[$_rule[0]]) || $_sarr[$_rule[0]] == 0) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "identity":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^\\d{15}|\\d{}18$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "url":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^http://([\\w-]+\\.)+[\\w-]+(/[\\w-./?%&=]*)?$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "username":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^[a-zA-Z]\\w{5,17}$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }
                            break;
                        case "len":
                            if (! isset($_sarr[$_rule[0]]) || strlen($_sarr[$_rule[0]]) < $_rule[2]|| strlen($_sarr[$_rule[0]]) > $_rule[3]) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[4]
                                );
                            }
                            break;
                        case "phone":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^1[0-9]{10}$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }
                            break;
                        case "in":
                            if (! isset($_sarr[$_rule[0]]) || ! in_array($_sarr[$_rule[0]], $_rule[2])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[3]
                                );
                            }
                            break;
                        case "require":
                            if (! isset($_sarr[$_rule[0]]) || trim($_sarr[$_rule[0]]) === '') {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }
                            break;
                        case "regex":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match($_rule[2][0], $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[3]
                                );
                            }
                            break;
                        case "qq":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^[1-9][0-9]{4,}$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "email":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/^\\w+(-|\\.|\\w+)*@\\w+((\\.\\w+)+)$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }

                            break;
                        case "number":
                            if (! isset($_sarr[$_rule[0]]) || ! preg_match("/[0-9]+$/", $_sarr[$_rule[0]])) {
                                return array(
                                    "err" => true,
                                    "data" => $_rule[2]
                                );
                            }
                            break;
                        default:
                            $_ret = $_obj->$_rule[1]($_sarr[$_rule[0]]);
                            if ($_ret) {
                                return $_ret;
                            }
                            break;
                    }
                }
            }
        }
        return array(
            "err" => false
        );
    }

    /**
     *
     * @param stirng $c
     * @param unknown $k
     * @return multitype:Ambigous <>
     */
    public static function ListClassOneArray($c, $k)
    {
        $ret = array();
        foreach ($c as $in) {
            $_temp = self::CtA($in);
            $ret[] = $_temp[$k];
        }
        return $ret;
    }

    public static function ListClassArray($c, $_map = null)
    {
        $ret = array();
        foreach ($c as $in) {
            $ret[] = self::CtA($in, $_map);
        }
        return $ret;
    }

    /**
     * // class转arary
     *
     * @param class $stdclassobject
     * @param string $_map
     * @return array
     */
    public static function CtA($stdclassobject, $_map = null)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;

        foreach ($_array as $key => $value) {

            $value = (is_array($value) || is_object($value)) ? class_to_array($value) : $value;
            if (is_array($_map)) {
                if (isset($_map[$key])) {
                    $_tempk = $_map[$key];
                    $array[$_tempk] = $value;
                } else {
                    $array[$key] = $value;
                }
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * 获取字符串分割后数组的第index个
     *
     * @param string $str
     * @param string $dem
     * @param string $index
     * @return string
     */
    public static function GetStrArrayEle($str, $dem, $index)
    {
        $ret = "";
        $_strarray = explode($dem, $str);
        if (count($_strarray) >= $index) {
            $ret = $_strarray[$index - 1];
        }
        return $ret;
    }

    /**
     * Print an array (recursive) as PHP code (can be pasted into a php file and it will work).
     *
     * @param array $array
     * @param boolean $return
     *            (whether to return or print the output)
     * @return string|boolean (string if $return is true, true otherwise)
     */
    public static function printArrayAsPhpCode($array, $return = false)
    {
        if (count($array) == 0) {
            if (! $return) {
                print "array()";
                return true;
            } else {
                return "array()";
            }
        }
        $string = "array(";
        if (array_values($array) === $array) {
            $no_keys = true;
            foreach ($array as $value) {
                if (is_int($value)) {
                    $string .= "$value, ";
                } elseif (is_array($value)) {
                    $string .= self::printArrayAsPhpCode($value, true) . ",\n";
                } elseif (is_string($value)) {
                    $string .= "$value', ";
                } else {
                    // trigger_error("Unsupported type of \$value, in index $key.");
                }
            }
        } else {
            $string .= "\n";
            foreach ($array as $key => $value) {
                $no_keys = false;
                if (is_int($value)) {
                    $string .= "\"$key\" => $value,\n";
                } elseif (is_array($value)) {
                    $string .= "\"$key\" => " . self::printArrayAsPhpCode($value, true) . ",\n";
                } elseif (is_string($value)) {
                    $string .= "\"$key\" => '$value',\n";
                } else {
                    // trigger_error("Unsupported type of \$value, in index $key.");
                }
            }
        }
        $string = substr($string, 0, strlen($string) - 2); // Remove last comma.
        if (! $no_keys) {
            $string .= "\n";
        }
        $string .= ")";
        if (! $return) {
            print $string;
            return true;
        } else {
            return $string;
        }
    }
}

?>