<?php

/**
 * 去除分页时的第一页的回调函数;
 * @param unknown $v
 * @return boolean
 */
function deletePage1($v) {
	if (strstr ( $v, "=1" )) {
		return false;
	}
	return true;
}
class Spider {
	/**
	 * 网站编码
	 *
	 * @var unknown
	 */
	var $charset = "utf-8";
	/**
	 * 列表页正则
	 *
	 * @var unknown
	 */
	var $listPattern = "";
	var $contentPatterns = "";
	/**
	 * 分页正则
	 *
	 * @var unknown
	 */
	var $pagerPattern = "";
	
	/**
	 * 要抓取的网站地址;
	 *
	 * @var unknown
	 */
	var $URI = "";
	/**
	 * 抓取的内容页url
	 */
	var $detailURI = "";
	/**
	 * 用于调试信息的分页;
	 * @var unknown
	 */
	var $debugPager;
	/**
	 * 网站路径
	 *
	 * @var unknown
	 */
	
	var $hostPath = "";
	/**
	 * 一次处理的内容页个数
	 *
	 * @var unknown
	 */
	var $maxSize = 5;
	var $debugContent = ""; // 调试下的内容页信息;
	/**
	 * 抓取的拍卖公告;
	 *
	 * @var unknown
	 */
	var $auctionList = array ();
	var $result;
	var $listurl = array ();
	
	/**
	 *
	 * @param unknown $this->URI        	
	 * @param unknown $this->listPattern        	
	 * @param unknown $this->detailPattern        	
	 */
	function __construct($URI) {
		$this->URI = $URI;
		$this->charset = $this->getCharset ();
		$this->hostPath = $this->getHostPath ();
	}
	function debug() {
		$listPage = $this->getContents ( $this->URI );
		
		if ($this->charset != "UTF-8") {
			
			$listPage = iconv ( $this->charset, "UTF-8//ignore", $listPage ); // 防止转换错误的字符中止;
		}
		
		$this->getListUrl ();
		
		if (count ( $this->listurl ) > 1) {
			$url=$this->listurl[0];
			$detailPage = $this->getContents ( $url );
		if ($this->charset != "UTF-8") {
				$detailPage = iconv ( $this->charset, "UTF-8//ignore", $detailPage );
			}
			
			$this->getResult ( $detailPage );
			$this->debugContent=$this->result;
			
		}
		$this->debugPager=$this->getPager ( $listPage );
		
		//print_r($this);
		
	}
	
	/**
	 * 从列表页得到分页的url;
	 *
	 * @param unknown $listPage        	
	 * @return multitype:
	 */
	function getPager($listPage) {
		$matches = array ();
		// echo $this->pagerPattern;
		// echo $listPage;
		preg_match ( $this->pagerPattern, $listPage, $matches );
		if (count ( $matches ) > 0) {
			$pagerUrlTemp = $this->striplinks ( $matches [1] );
			$pagerUrl = $this->expandlinks ( $pagerUrlTemp, $this->URI );
			$pagerUrl = array_unique ( array_filter ( $pagerUrl, "deletePage1" ) );
			return $pagerUrl;
		}
		
		return array ();
	}
	/**
	 * 获得当前分页中的文章列表;
	 */
	/*
	 * function getListUrl() { $listPage = $this->getContents ( $this->URI ); // $snoopy->results; $matches = array (); if ($this->charset != "UTF-8") { $listPage = iconv ( $this->charset, "UTF-8//ignore", $listPage ); // 防止转换错误的字符中止; } preg_match ( $this->listPattern, $listPage, $matches ); $isContinue = false; // 程序是否进行跳转再次进行抓取; if (count ( $matches ) > 0) { $listUrlTemp = $this->striplinks ( $matches [1] ); $listUrl = $this->expandlinks ( $listUrlTemp, $this->URI ); $this->listurl = $listUrl; } } }
	 */
	function getListUrl() {
		$listPage = $this->getContents ( $this->URI ); // $snoopy->results;
		$matches = array ();
		if ($this->charset != "UTF-8") {
			
			$listPage = iconv ( $this->charset, "UTF-8//ignore", $listPage ); // 防止转换错误的字符中止;
		}
		
		// 从列表页根据正则提出列表页内容，再进一步提出url
		
		$patternTemp = explode ( "[next]", $this->listPattern );
		if (count ( $patternTemp ) > 1) 		// 需要进一步处理;
		{
			$listPattern = $patternTemp [0];
			$urlPattern = $patternTemp [1];
			preg_match ( $listPattern, $listPage, $matches );
			if (count ( $matches ) > 0) {
				$content = $matches [0];
				
				$matches = array ();
				preg_match_all ( $urlPattern, $content, $matches );
				
				if (count ( $matches ) > 0) {
					$listUrlTemp = array_values ( (array_unique ( $matches [0] )) );
					$this->listurl = $this->expandlinks ( $listUrlTemp, $this->URI );
				}
			}
		} else 		// 不需要进一步处理
		{
			preg_match ( $this->listPattern, $listPage, $matches );
			$isContinue = false; // 程序是否进行跳转再次进行抓取;
			if (count ( $matches ) > 0) {
				$listUrlTemp = $this->striplinks ( $matches [1] );
				$listUrl = $this->expandlinks ( $listUrlTemp, $this->URI );
				$this->listurl = $listUrl;
			}
		}
	}
	
	/**
	 * 启动采集器
	 *
	 * @param unknown $listUrl        	
	 * @return boolean
	 */
	function runSpider($listUrl) {
		$isContinue = false; // 程序是否进行跳转再次进行抓取;
		$maxSize = $this->maxSize;
		$length = count ( $listUrl );
		/*
		 * echo "<pre/>"; print_r ( $listUrl ); exit ();
		 */
		
		/*
		 * if ($this->end > $length) { $this->end = $length; // 防止溢出; }
		 */
		/* echo "<pre/>";
		$this->debug();
		exit; */
		
		if ($length > 1) {
			$isContinue = true;
		}
		
		for($i = 0; $i < $maxSize; $i ++) {
			$detailPage = $this->getContents ( $listUrl [$i] ); // $snoopy->results;
			$this->detailURI = $listUrl [$i];
			if ($this->charset != "UTF-8") {
				$detailPage = iconv ( $this->charset, "UTF-8//ignore", $detailPage );
			}
			
			$this->getResult ( $detailPage );
		}
		return $isContinue;
	}
	function getAuctionTime($detailContent) {
		// $datePattern='/([\d]{4}年\s{0,5}[\d]{1,2}月\s{0,5}[\d]{1,2}日)([\D]{0,30})([\d]{1,2})(时|分|点)?/i';
		$datePattern = '/(([\d]{4}年\s{0,5}[\d]{1,2}月\s{0,5}[\d]{1,2}日)([\D]{0,30})([\d]{1,2})(:[\d]{0,2})?(时|分|点)?)/i';
		$date = "";
		$matches = array ();
		preg_match ( $datePattern, $detailContent, $matches );
		if (count ( $matches ) > 0) {
			$date = $matches [1];
			
			$date = preg_replace ( $datePattern, '\2\4\5时', $date );
			
			$date = strip_tags ( $date );
			// $date=$this->chinese2Number($date);
			if (strstr ( $date, ":00" )) {
				$date = str_replace ( ":00", "", $date );
			}
			$date = str_replace ( "分", "时", $date );
			$date = str_replace ( "点", "时", $date );
			
			$patterns = array ();
			$patterns [0] = '/\s/';
			$patterns [1] = '/年/';
			$patterns [2] = '/月/';
			$patterns [3] = '/日/';
			$patterns [4] = '/时/';
			$patterns [5] = '/上午/';
			$patterns [6] = '/下午/';
			
			$replacements = array ();
			
			$replacements [0] = '';
			$replacements [1] = '-';
			$replacements [2] = '-';
			$replacements [3] = ' ';
			$replacements [4] = ':00';
			$replacements [5] = '';
			$replacements [6] = '';
			
			$date = preg_replace ( $patterns, $replacements, $date );
			
			$hour = date ( "G", strtotime ( $date ) );
			
			if ($hour < 8)
				$date = date ( "Y-m-d H:i", strtotime ( $date ) + 60 * 60 * 12 );
		}
		
		return $date;
	}
	
	/**
	 * 根据正则表达式从内容页获取相应内容;
	 */
	function getResult($detailPage) {
		$results = "";
		$patterns = str_replace ( array (
				"\r\n",
				"\n",
				"\r" 
		), '/hhf/', $this->contentPatterns );
		
		$patternArray = array_filter ( explode ( "/hhf/", $patterns ) ); // 将空数组过滤掉;
		
		foreach ( $patternArray as $pattern ) {
			$matches = array ();
			
			$pattern = trim ( $pattern );
			$endPos = strpos ( $pattern, "]" );
			$start = substr ( $pattern, 0, $endPos + 1 );
			
			$end = str_replace ( "[", "[/", $start );
			
			$search = array (
					$start,
					$end 
			);
			
			$pattern = str_replace ( $search, "", $pattern );
			
			preg_match ( $pattern, $detailPage, $matches );
			
			if (count ( $matches ) > 0) {
				$result = $matches [1];
				
				$result = strip_tags ( $result, "<img><b><p><a>" );
				$result = preg_replace ( '/<img.*?src=[\'|\"]/i', "$0" . $this->hostPath, $result );
			} else {
				$result = " ";
			}
			$results .= $start . $result . $end;
		}
		
		$this->result = $results;
	}
	
	/**
	 * 获取html源码中的链接
	 *
	 * @param unknown $document        	
	 * @return multitype:
	 */
	function striplinks($document) {
		preg_match_all ( "'<\s*a\s.*?href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx", $document, $links );
		
		// catenate the non-empty matches from the conditional subpattern
		
		while ( list ( $key, $val ) = each ( $links [2] ) ) {
			if (! empty ( $val ))
				$match [] = $val;
		}
		
		while ( list ( $key, $val ) = each ( $links [3] ) ) {
			if (! empty ( $val ))
				$match [] = $val;
		}
		
		// return the links
		return $match;
	}
	
	/**
	 * 修正链接地址;转化成带http://的地址
	 *
	 * @param unknown $links        	
	 * @param unknown $this->URI        	
	 * @param unknown $host        	
	 * @return mixed
	 */
	function expandlinks($links, $URI) {
		preg_match ( "/^[^\?]+/", $URI, $match );
		
		$match = preg_replace ( "|/[^\/\.]+\.[^\/\.]+$|", "", $match [0] );
		$match = preg_replace ( "|/$|", "", $match );
		$match_part = parse_url ( $match );
		$match_root = $match_part ["scheme"] . "://" . $match_part ["host"];
		
		$search = array (
				"|^http://" . preg_quote ( $this->hostPath ) . "|i",
				"|^(\/)|i",
				"|^(?!http://)(?!mailto:)|i",
				"|/\./|",
				"|/[^\/]+/\.\./|" 
		);
		
		$replace = array (
				"",
				$match_root . "/",
				$match . "/",
				"/",
				"/" 
		);
		
		$expandedLinks = preg_replace ( $search, $replace, $links );
		
		return $expandedLinks;
	}
	/**
	 * 获得网站编码;
	 *
	 * @return string
	 */
	function getCharset() {
		/*
		 * $snoopy = new Snoopy (); $snoopy->fetch ( $this->URI );
		 */
		$listPage = $this->getContents ( $this->URI ); // $snoopy->results;
		                                               // $snoopy = null;
		$encode = mb_detect_encoding ( $listPage, "CP936,EUC-CN,BIG-5,EUC-TW,UTF-8" );
		
		return $encode;
	}
	/**
	 * 获取网站路径
	 *
	 * @return string
	 */
	function getHostPath() {
		// echo $this->URI;
		$arr_url = parse_url ( $this->URI );
		
		$end = strrpos ( $arr_url ["path"], "/" );
		$hostPath = "http://" . $arr_url ["host"] . substr ( $arr_url ["path"], 0, $end + 1 );
		return $hostPath;
	}
	
	/*
	 * function chinese2Number($str) { $str=str_replace("零", "0", $str); $str=str_replace("一", "1", $str); $str=str_replace("二", "2", $str); $str=str_replace("三", "3", $str); $str=str_replace("四", "4", $str); $str=str_replace("五", "5", $str); $str=str_replace("六", "6", $str); $str=str_replace("七", "7", $str); $str=str_replace("八", "8", $str); $str=str_replace("九", "9", $str); return $str; }
	 */
	function getContents($url) {
		$ctx = stream_context_create ( array (
				'http' => array (
						'timeout' => 30 
				) 
		) );
		$result = @file_get_contents ( $url, 0, $ctx );
		if ($result) {
			return $result;
		} else {
			// throw new Exception("抓取超时！");
			return "timeOut"; // 抓取超时！";
		}
	}
}

?>