<?php

class Pager
{

    public $pager_class = "pager";

    public $current_class = "current";

    public $page_prev = "page-prev";

    public $page_next = "page-next";

    public $page_home_text = "首页";

    public $page_last_text = "尾页";

    public $page_next_text = "下一页";

    public $page_prev_text = "上一页";

    public $page_mode = 2;
    
        
    
    public function __construct($pagetempfile=""){
        
    }
    // 1、表示是否分页动态url 2、静态分页
    public function GetPageUrl($url, $p, $page_id = "page")
    {
        
        // $page_patt = "/page=(\d)+/"
        if ($this->page_mode == 1) { // $preg = ;
            $url_parts = parse_url($url);
            
            if (isset($url_parts['query'])) {
                parse_str($url_parts['query'], $queryarr);
                $url = str_replace($url_parts['query'], "", $url);
                $queryarr[$page_id] = $p;
                $url = $url . http_build_query($queryarr);
            } else {
                $url = $url . "?" . $page_id . "=" . $p;
            }
            
            // $paramArg = array('id'=>true,'name'=>true,'sa'=>true,'ss'=>true);
            
            /*
             * if (preg_match ( $page_patt, $url )) { $url = preg_replace (
             * $page_patt, "page=" . $p, $url ); } else { if (substr ( $url,
             * strlen ( $url ) - 1 ) == "&") { $url = $url . "page=" . $p; }
             * else { if (! strstr ( $url, '?' )) { $url = $url . "?page=" . $p;
             * } else { $url = $url . "&page=" . $p; } } }
             */
        }
        if ($this->page_mode == 2) {
            if (preg_match("/{$page_id}\/(\d+)/", $url, $match)) {
                $url = str_replace($match[0], str_replace($match[1], $p, $match[0]), $url);
            } else {
                if ($url[strlen($url) - 1] == "/") {
                    $url = $url . "{$page_id}/" . $p;
                } else {
                    $url = $url . "/{$page_id}/" . $p;
                }
            }
            // $url = $p == 0 ? $url . "index.html" : $url . "index_" . $p . ".html";
        }
        return $url;
    }

    /**
     *
     * @param 记录总数 $sums            
     * @param 当前页面 $page            
     * @param 页面大小 $pagesize            
     * @param 页面url $url            
     * @return string
     */
    public function start($sums, $page = 0, $pagesize = 10, $url, $routetype=1)
    {
        $this->page_mode = $routetype;
        $pagenavgation = "";
        $pages = ceil(($sums - 0.5) / $pagesize) - 1;
        
        $pages = $pages >= 0 ? $pages : 0;
        if ($pages > 0) {
            $prepage = ($page > 0) ? $page - 1 : 0;
            $nextpage = ($page < $pages) ? $page + 1 : $pages;
            $shownum = 3;
            $startpage = ($page >= $shownum) ? $page - $shownum : 0;
            $endpage = ($page + $shownum <= $pages) ? $page + $shownum : $pages;
            $pagenavgation = "<DIV class='{$this->pager_class}'>";
            /*
             * if($page ==0) $pagenavgation = "<SPAN
             * class='{$this->current_class}'>" .$this->page_home_text .
             * "</SPAN>"; else $pagenavgation = "<A class='{$this->page_prev}'
             * href='" . $this->GetPageUrl ( $url, 0 ) .
             * "'><SPAN>{$this->page_home_text}</SPAN></A>";
             */
            if ($page > 0)
                $pagenavgation = $pagenavgation . "<A class='{$this->page_prev}' href='" . $this->GetPageUrl($url, 0) . "'><SPAN>{$this->page_home_text}</SPAN></A>";
            
            for ($i = $startpage; $i <= $endpage; $i ++) {
                if ($i == $page)
                    $pagenavgation = $pagenavgation . "<SPAN class='{$this->current_class}'>" . ($i + 1) . "</SPAN>";
                else
                    $pagenavgation = $pagenavgation . ' <a href="' . $this->GetPageUrl($url, $i) . '">' . ($i + 1) . '</a> ';
            }
            if ($endpage < $pages)
                $pagenavgation = $pagenavgation . "...";
            if ($page < $pages)
                $pagenavgation = $pagenavgation . "<A 
class='{$this->page_next}' href='" . $this->GetPageUrl($url, $pages) . "'><SPAN>{$this->page_last_text}</SPAN></A>";
            $pagenavgation = $pagenavgation . "</DIV>";
        }
        return $pagenavgation;
    }
}
?>