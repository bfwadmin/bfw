<?php
namespace Lib;


/**
 * @author wangbo
 * 路由类
 */
class BoRoute
{


    public static function GetParaByUrl()
    {
        $_cachedata = &Registry::getInstance()->get("sys_path_array_cache_data");
        if (! is_null($_cachedata)) {
            return $_cachedata;
        }
        $_key_arr = array();
        $_key_arr[DOMIAN_NAME] = "";
        $_key_arr[CONTROL_NAME] ="";
        $_key_arr[ACTION_NAME] = "";
        $_pathurl="";
        if(isset($_SERVER['PATH_INFO'])&&$_SERVER['PATH_INFO']!=""){
            $_pathurl=$_SERVER['PATH_INFO'];

        }else{
            if(isset( $_SERVER["QUERY_STRING"])){
                $_pathurl=$_SERVER['QUERY_STRING'];
            }
        }
        if($_pathurl==""){
            $_pathurl="/";
        }
        if($_pathurl!=""){
            $_routedata = &Registry::getInstance()->get("route_data");
            if (! is_null($_routedata)) {
               foreach ( $_routedata as $_iurl => $_furl){
                   if(isset($_furl['url'])){
                       if (preg_match($_iurl, $_pathurl, $match)) {
                           if (isset($_furl["method"])) {
                               if (is_array($_furl["method"])) {
                                   if (! in_array(HTTP_METHOD, $_furl["method"])) {
                                       break;
                                   }
                               } else {
                                   if (HTTP_METHOD != $_furl["method"]) {
                                       break;
                                   }
                               }
                           }
                           for ($i = 1; $i < count($match); $i ++) {
                               $_furl['url'] = str_replace("[{$i}]", $match[$i], $_furl['url']);
                           }
                           if (strstr($_furl['url'], "http://") || strstr($_furl['url'], "https://")) {
                               header("location:" . $_furl['url']);
                               die();
                           }
                           $_pathurl=$_furl['url'];
                           break;
                       }
                   }
               }
            }
            if (PAGE_SUFFIX != "") {
                $_pathurl=str_replace(PAGE_SUFFIX,"",$_pathurl);
            }
            $_patharr = explode("/", ltrim($_pathurl, "/"));
            $_para_start=3;
            if(defined("HOST_HIDE_DOM")){
                $_para_start=2;
                $_key_arr[DOMIAN_NAME] = HOST_HIDE_DOM;
                $_key_arr[CONTROL_NAME] = isset($_patharr[0]) ? $_patharr[0] : "";
                $_key_arr[ACTION_NAME] = isset($_patharr[1]) ? $_patharr[1] : "";
            }else{
                $_key_arr[DOMIAN_NAME] = isset($_patharr[0]) ? $_patharr[0] : "";
                $_key_arr[CONTROL_NAME] = isset($_patharr[1]) ? $_patharr[1] : "";
                $_key_arr[ACTION_NAME] = isset($_patharr[2]) ? $_patharr[2] : "";
            }
            if (count($_patharr) > $_para_start) {
                for ($i = $_para_start; $i < count($_patharr); $i ++) {
                    if (preg_match("/^[a-zA-Z]{1,25}$/", $_patharr[$i])) {
                        $_key_arr[$_patharr[$i]] = isset($_patharr[$i + 1]) ? $_patharr[$i + 1] : "";
                        $i ++;
                    }
                }
            }

            Registry::getInstance()->set("sys_path_array_cache_data", $_key_arr);
        }
        return $_key_arr;
    }

}

?>