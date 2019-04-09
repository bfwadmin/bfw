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
<style>
.v_c_d{
	padding:0;
	margin:0;
	height:120px;
	width:100%;
	
}
.v_c_d li{
	display:block;
	float:left;
	width:120px;
	height:30px;
	overflow:hidden;
}
.v_c_d button{
	height:20px;
	width:60px;
	color:white;
}
.bartool a{
	padding:2px 10px;
	background:#434343;
	color:white;
}
.m_hover{
	background:#434343;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

	
  $(".v_c_d button").click(function(){
	  var chk_value =[];
	  var vthis = $(this);
	  var id=$(this).attr("vid");	  //vthis.parent().parent().find("input").attr( "checked", true );
	  vthis.parent().parent().find("input[type='checkbox']:checked").each(function(){
		  chk_value.push($(this).val());
	  });
	  if(chk_value.length==0){
		  alert('你还没有选择任何内容！');
	  }else{
		  $.ajax({ 
 	           type: "get", 
 	           url: "<?=Bfw::ACLINK("Artwork","FailIt","err=")?>"+chk_value+"&id="+id, 
 	           dataType: "json", 
 	           success: function (data) { 
	   	              if(data['err']){
		   	            	alert(data['data']); 
	   	              }else{
		   	            	alert("操作成功");
		   	            	$("#tr_"+id).remove();
	   	              }
 	           }, 
 	           error: function (XMLHttpRequest, textStatus, errorThrown) { 
	   	            alert(errorThrown); 	
 	           } 
 	           });
		  
	  }   
  });
  $(".click").click(function(){
  $(".tip").fadeIn(200);
  });
  
  $(".tiptop a").click(function(){
  $(".tip").fadeOut(200);
});

  $(".sure").click(function(){
  $(".tip").fadeOut(100);
  });

  $(".cancel").click(function(){
  $(".tip").fadeOut(100);
});

});

var passit=function(id){
	$.ajax({ 
        type: "get", 
        url: "<?=Bfw::ACLINK("Artwork","PassIt","id=")?>"+id, 
        dataType: "json", 
        success: function (data) { 
	              if(data['err']){
	   	            	alert(data['data']); 
	              }else{
	   	            	alert("操作成功");
	   	            	$("#tr_"+id).remove();
	              }
        }, 
        error: function (XMLHttpRequest, textStatus, errorThrown) { 
	            alert(errorThrown); 	
        } 
        });
	  
}
</script>


</head>


<body>

	<div class="place">
		<span>位置：</span>
		<ul class="placeul">
			<li><a href="#">首页</a></li>
			<li><a href="#">文章</a></li>
			<li><a href="#">目录</a></li>
		</ul>
	</div>

	<div class="rightinfo">

		<div class="tools">
			<form method="get" action="">
				<input type="hidden" name="<?=CONTROL_NAME?>" value="Article" /> <input
					type="hidden" name="<?=ACTION_NAME?>" value="ListData" /> <input
					type="hidden" name="<?=DOMIAN_NAME?>" value="Cms" />
					
					<div class="toolbar">
                 	<ul> 
                 	
                    <li style="margin-left:10px;"><input name="title" type="text" class="dfinput"
						style="width: 200px;" placeholder="请输标题"
						value="<?=Bfw::GPC("title")?>" /></li>
					<li><input value="查找" type="submit" class="btn" /></li>
  	               </ul>
                    </div>
				
				

			

			
			</form>
		</div>

 <?php
//var_dump($itemdata);
if (empty($itemdata)) {
    ?>
                    <h1 align="center" style="padding-top:100px;">暂无信息</h1>
           <?php

} else {
    
    ?>
               <table class="tablelist">
		
			<tbody>
        
               <?php
    foreach ($itemdata as $item) {
        ?>  
           
					<tr >

					<td><a href="<?=Bfw::ACLINK("Article","CatalogData","classname=".$item['classname'])?>"><?=$item['classname']?></a></td>
					
			
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
