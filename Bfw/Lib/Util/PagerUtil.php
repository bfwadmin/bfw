<?php
namespace Lib\Util;

use Lib\Bfw;
class PagerUtil
{

    public static function _GenPageData($sums = 0, $page = 0, $pagesize = 10, $shownum = 3, $url = URL, $pageid = "page")
    {
        $page = intval($page);
        $_pageData = array();
        $_everypageData = array();
        $_pageData['totalsum'] = $sums;
        $_pageData['currentpage'] = $page + 1;
        
        $pages = ceil(($sums - 0.5) / $pagesize) - 1;
        $pages = $pages >= 0 ? $pages : 0;
        $_pageData['totalpage'] = $pages;
        
        if ($pages > 0) {
            $prepage = ($page > 0) ? $page - 1 : 0;
            $nextpage = ($page < $pages) ? $page + 1 : $pages;
            $startpage = ($page >= $shownum) ? $page - $shownum : 0;
            $endpage = ($page + $shownum <= $pages) ? $page + $shownum : $pages;
            if ($page > 0)
                $_everypageData[] = array(
                    "urltype" => "first",
                    "url" => self::GetPageUrl($url, 0, $pageid)
                );
            if($page>=1)
                $_everypageData[] = array(
                    "urltype" => "prev",
                    "url" => self::GetPageUrl($url, $page-1, $pageid)
                );
            
            for ($i = $startpage; $i <= $endpage; $i ++) {
                if ($i == $page)
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => self::GetPageUrl($url, $i, $pageid),
                        "pagenum" => $i + 1,
                        "current" => true
                    );
                else
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => self::GetPageUrl($url, $i, $pageid),
                        "pagenum" => $i + 1,
                        "current" => false
                    );
            }
            
            if ($endpage < $pages)
                
                if ($page < $pages)
                    $_everypageData[] = array(
                        "urltype" => "last",
                        "url" => self::GetPageUrl($url, $pages, $pageid)
                    );
                if($page+1<=$pages)
                    $_everypageData[] = array(
                        "urltype" => "next",
                        "url" => self::GetPageUrl($url, $page+1, $pageid)
                    );
 
        }
        $_pageData['pagedata'] = $_everypageData;
        return $_pageData;
    }
    
    // 1、表示是否分页动态url 2、静态分页
    private static function GetPageUrl($_url, $_page, $_pageid = "page")
    {
        return Bfw::ACLINK(CONTROL_VALUE,ACTION_VALUE,$_pageid."=".$_page,DOMIAN_VALUE);
//         if ($routetype == 1||$routetype ==0) {
//             $url_parts = parse_url($_url);
//             if (isset($url_parts['query'])) {
//                 parse_str($url_parts['query'], $queryarr);
//                 $_url = str_replace($url_parts['query'], "", $_url);
//                 $queryarr[$_pageid] = $_page;
//                 $_url = $_url . http_build_query($queryarr);
//             } else {
//                 $_url = $_url . "?" . $_pageid . "=" . $_page;
//             }
//         }
//         if ($routetype == 2) {
//             $_url = Bfw::ACLINK(CONTROL_VALUE,ACTION_VALUE,$_pageid."=".$_page,DOMIAN_VALUE);
           // die($_url);
//             if (PAGE_SUFFIX != "") {
//                 $_url=rtrim($_url,PAGE_SUFFIX);
//             }
//             $_url = str_replace("index.php/", "", $_url);
//             if (preg_match("/{$_pageid}\/(\d+)/", $_url, $match)) {
//                 $_url = str_replace($match[0], str_replace($match[1], $_page, $match[0]), $_url);
//             } else {
//                 if ($_url[strlen($_url) - 1] == "/") {
//                     //die();
//                     $_url = Bfw::ACLINK(CONTROL_VALUE,ACTION_VALUE,$_pageid."=".$_page,DOMIAN_VALUE);
//                 } else {
//                     $_url = $_url . "/{$_pageid}/" . $_page;
//                 }
//             }
//             if (PAGE_SUFFIX != "") {
//                 $_url=$_url.PAGE_SUFFIX;
//             }
      //  }
       // return $_url;
    }
}
?>