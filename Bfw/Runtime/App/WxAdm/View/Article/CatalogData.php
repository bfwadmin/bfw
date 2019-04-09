<?php
use Lib\Bfw;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>组管理</title>
<link href="/statics/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/statics/js/jquery.js"></script>



</head>


<body>

	<div class="place">
		<span>位置：</span>
		<ul class="placeul">
			<li><a href="#">首页</a></li>
			<li><a href="#">文章</a></li>
			<li><a href="#">所有</a></li>
		</ul>
	</div>

	<div class="rightinfo">

		<div class="tools"></div>

 <?php
// var_dump($itemdata);
if (empty($itemdata)) {
    ?>
                    <h1 align="center" style="padding-top: 100px;">暂无信息</h1>
           <?php
} else {
    
    ?>
               <table class="tablelist">

			<tbody>
        
               <?php
    foreach ($itemdata as $item) {
        ?>  
           
					<tr>

					<td><?=$item['id']?></td>
					<td><a href="<?=Bfw::ACLINK("Article","DetailData","id=".$item['id'])?>"><?=$item['title']?></a></td>


				</tr> 
					
        
                        <?php
    }
    
    ?>
                  </tbody>
		</table>
		
            <?php
}

?>						
                       
      
        
       
  

      
		

		<div class="tip">
			<div class="tiptop">
				<span>提示信息</span><a></a>
			</div>

			<div class="tipinfo">
				<span><img src="/statics/images/ticon.png" /></span>
				<div class="tipright">
					<p>是否确认对信息的修改 ？</p>
					<cite>如果是请点击确定按钮 ，否则请点取消。</cite>
				</div>
			</div>

			<div class="tipbtn">
				<input name="" type="button" class="sure" value="确定" />&nbsp; <input
					name="" type="button" class="cancel" value="取消" />
			</div>

		</div>




	</div>

	<script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

</body>

</html>
