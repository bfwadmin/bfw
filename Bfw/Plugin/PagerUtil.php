<?php
namespace Plugin;
class PagerUtil
{

    protected $_routetype = 1;

    public function _GenPageData($sums = 0, $page = 0, $pagesize = 10, $shownum = 3, $url = URL, $pageid = "page", $routetype = 1)
    {
        $this->_routetype = $routetype;
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
                    "urltype" => "prev",
                    "url" => $this->GetPageUrl($url, 0, $pageid)
                );
            for ($i = $startpage; $i <= $endpage; $i ++) {
                if ($i == $page)
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => $this->GetPageUrl($url, $i, $pageid),
                        "pagenum" => $i + 1,
                        "current" => true
                    );
                else
                    $_everypageData[] = array(
                        "urltype" => "page",
                        "url" => $this->GetPageUrl($url, $i, $pageid),
                        "pagenum" => $i + 1,
                        "current" => false
                    );
            }
            if ($endpage < $pages)
                
                if ($page < $pages)
                    $_everypageData[] = array(
                        "urltype" => "next",
                        "url" => $this->GetPageUrl($url, $pages, $pageid)
                    );
        }
        $_pageData['pagedata'] = $_everypageData;
        return $_pageData;
    }
    
    // 1、表示是否分页动态url 2、静态分页
    private function GetPageUrl($_url, $_page, $_pageid = "page")
    {
        if ($this->_routetype == 1) {
            $url_parts = parse_url($_url);
            if (isset($url_parts['query'])) {
                parse_str($url_parts['query'], $queryarr);
                $_url = str_replace($url_parts['query'], "", $_url);
                $queryarr[$_pageid] = $_page;
                $_url = $_url . http_build_query($queryarr);
            } else {
                $_url = $_url . "?" . $_pageid . "=" . $_page;
            }
        }
        if ($this->_routetype == 2) {
            if (preg_match("/{$_pageid}\/(\d+)/", $_url, $match)) {
                $_url = str_replace($match[0], str_replace($match[1], $_page, $match[0]), $_url);
            } else {
                if ($_url[strlen($_url) - 1] == "/") {
                    $_url = $_url . "{$_pageid}/" . $_page;
                } else {
                    $_url = $_url . "/{$_pageid}/" . $_page;
                }
            }
        }
        return $_url;
    }
}
?>