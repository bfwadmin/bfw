<?php
include 'Spider.php';
/**
	 * 解析出分页和列表页的正则;
	 *
	 * @param unknown $patterns        	
	 */
	function getListAndPagerPattern($patterns) {
		$patterns = str_replace ( array (
				"\r\n",
				"\n",
				"\r" 
		), '/hhf/', $patterns );
		
		$patternArray = array_filter ( explode ( "/hhf/", $patterns ) ); // 将空数组过滤掉;
		
		return $patternArray;
	}
	
$spideName="";
$url="";
$listarea="";
$contarea="";
$tip="";

if(!empty($_GET["spidername"]))
{
	$spideName=$_GET["spidername"];
	
	
}else 
{
	$tip="你还没有填写采集器名称！";
	echo $tip;
	exit;
}

if(!empty($_GET["url"]))
{
	$url=$_GET["url"];
}else
{
	$tip="采集地址不要忘记填写！";
	echo $tip;
	exit;
}

if(!empty($_GET["listarea"]))
{
	$listarea=$_GET["listarea"];
}else
{
	$tip="采集列表区域没有填写";
	echo $tip;
	exit;
}

if(!empty($_GET["contarea"]))
{
	$contarea=$_GET["contarea"];
}else
{
	$tip="采集内容区域没有填写";
	echo $tip;
	exit;
}


		

$spider = new Spider ( $url );

$patternArray = array_values (getListAndPagerPattern ( $listarea ) );
		$patternCount = count ( $patternArray );
		$listPattern = "";
		$pagerPattern = "";
		
		switch ($patternCount) {
			case 1 :
				$listPattern = $patternArray [0];
				$pagerPattern = "";
				break;
			case 2 :
				$listPattern = $patternArray [0];
				$pagerPattern = $patternArray [1];
				break;
		}

				$spider->listPattern = $listPattern; // 设置文章列表的正则表达式
				$spider->contentPatterns = $contarea; // 设置文章标题的正则表达式
				$spider->pagerPattern = $pagerPattern;
				$spider->maxSize = 1; // 设置批量处理的最大值
				$spider->debug();
				$listUrl=$spider->listurl;
				
				if(count($listUrl)>0)
				{
					$listUrlHtml="";
					foreach($listUrl as $val)
					{
						$listUrlHtml.="<li><a target='_blank' href='$val'>$val</a></li>";
					}
					
				}else
				{
					$listUrlHtml="<li>没有采集到文章列表，请修改后再重试！</li>";
				}
				
				$debugPager=$spider->debugPager;
				if(count($debugPager)>0)
				{
					$debugPagerHtml="";
					foreach($debugPager as $val)
					{
						$debugPagerHtml.="<li><a target='_blank' href='$val'>$val</a></li>";
					}
					
				}else
				{
					$debugPagerHtml="<li>没有采集到文章列表，请修改后再重试！</li>";
				}
				
				

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>采集器调试信息</title>
<style type="text/css">

table
{
	font-size:14px;
	
}

h3
{
	font-size:18px;
	margin-bottom:10px;
}


</style>
</head>
<body>
<table class="list" width="200" border="1">
<caption><h3>采集器调试信息</h3></caption>
  
  <tr>
    <td style="width:10em;">采集器名称</td>
    <td><?php echo $spideName?></td>
  </tr>
  <tr>
    <td>采集到文章列表</td>
    <td><ul>
   <?php echo $listUrlHtml?>
    </ul></td>
  </tr>
  <tr>
    <td>采集到的分页</td>
    <td><ul>
   <?php echo $debugPagerHtml?>
    </ul></td>
  </tr>
  <tr>
    <td>采集到文章内容</td>
    <td> <?php echo empty($spider->debugContent)? "没有采集到内容,请修改后再重试！":$spider->debugContent;?></td>
  </tr>
</table>

</body>
</html>
