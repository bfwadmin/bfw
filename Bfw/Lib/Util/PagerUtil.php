<?php
namespace Lib\Util;

use Lib\Bfw;
/**
 * @author wangbo
 * 分页辅助类
 */
class PagerUtil
{

    /**
     * @param number $sums
     * @param number $page
     * @param number $pagesize
     * @param number $shownum
     * @param string $pageurlpattern
     * @param string $_pageid
     * @return multitype:number Ambigous <number, unknown> multitype:multitype:string NULL  multitype:string number boolean NULL  multitype:string
     */
    public static function _GenPageData($sums = 0, $page = 0, $pagesize = 10, $shownum = 3, $pageurlpattern="",$_pageid="page")
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
                    "url" => self::GetPageUrl( 0, $_pageid,$pageurlpattern)
                );
            if($page>=1)
                $_everypageData[] = array(
                    "urltype" => "prev",
                    "url" => self::GetPageUrl($page-1,$_pageid, $pageurlpattern)
                );

            for ($i = $startpage; $i <= $endpage; $i ++) {
                if ($i == $page)
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => self::GetPageUrl($i,$_pageid, $pageurlpattern),
                        "pagenum" => $i + 1,
                        "current" => true
                    );
                else
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => self::GetPageUrl($i,$_pageid, $pageurlpattern),
                        "pagenum" => $i + 1,
                        "current" => false
                    );
            }

            if ($endpage < $pages)

                if ($page < $pages)
                    $_everypageData[] = array(
                        "urltype" => "last",
                        "url" => self::GetPageUrl($pages,$_pageid, $pageurlpattern)
                    );
                if($page+1<=$pages)
                    $_everypageData[] = array(
                        "urltype" => "next",
                        "url" => self::GetPageUrl( $page+1,$_pageid, $pageurlpattern)
                    );

        }
        $_pageData['pagedata'] = $_everypageData;
        return $_pageData;
    }

    // 1、表示是否分页动态url 2、静态分页
    private static function GetPageUrl($_page, $_pageid,$_pageurlpattern)
    {
        if($_pageurlpattern==""){
            return Bfw::ACLINK(CONTROL_VALUE,ACTION_VALUE,$_pageid."=".$_page,DOMIAN_VALUE);
        }else {
            return str_replace("[page]", $_page, $_pageurlpattern);
        }

    }
}
?>