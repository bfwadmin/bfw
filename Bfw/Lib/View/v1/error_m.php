<?php 
use App\Lib\Util\HtmlUtil;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>系统提示</title>
</head>

<body>

<div id="popDiv" class="layer_photo" >
   <div class="xitong">
   		<div class="xitong_title">
             <div class="xitongtishi">系统提示</div>
             <!--  <div class="guanbi"><a href="javascript:history.back();" ><img src="http://static.zhongpaiwang.com/public/images/xitong_title.gif" /></a></div>-->
        </div>
        <div class="tishitubiao">
               <div class="tishiwenzi">
                <img src="http://static.zhongpaiwang.com/public/images/xitong1.gif"/>
                <b class="layer_wenzi"><?=$but_msg?></b>
               </div> 
        </div>
        <div class="xitong_text"><a href="javascript:history.back();">返回</a></div>
        <div class="xitong_photobotton" style="text-align:center;padding:0;float:none;"> <input type="button" onclick="history.back();" border="0" class="phontobotton_bg1"/></div>                                
   </div>
</div>


</body>
</html>
