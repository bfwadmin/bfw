<html>
<head lang="zh-CN">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BFW服务注册中心</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
<link rel="shortcut icon" href="/favicon.ico" />
<style>
#cmdcont p {
	padding: 0;
	margin: 5px;
	font-size: 18px;
	color: #c3c3c3;
	word-break: break-all;
}

table.gridtable {
	font-family: verdana, arial, sans-serif;
	font-size: 11px; !
	border-width: 1px;
	border-color: #ffffff;
	border-collapse: collapse;
}

table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #ffffff;
}

table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid; !
	border-color: #666666; !
	background-color: #ffffff;
}
</style>
<script language="JavaScript">


		//var cmdlist=new Array("bfw init","bfw code","bfw getapp","bfw sendapp","bfw listapp","bfw help");
		function servicereg(){
			 var url=prompt("请输入服务方url,例如http://www.g.com/","");
			  if (url!=null && url!="")
			    {
				  var dom=prompt("请输入服务方域","");
				  if (dom!=null && dom!="")
				    {
					  var iframe = document.createElement('iframe');
					   iframe.src=url+"?reg=1&domianname="+dom;
					   document.body.appendChild(iframe);
					//  var script = document.createElement("script");
					 // script.type = "text/javascript";
					 // script.src =url+"?reg=1&domianname="+dom;
					//  document.body.appendChild(script);
					  setTimeout(function(){  document.body.removeChild(iframe);alert("ok");  location.reload();},10000);
				    }
			    }
		}
		function adjust(id,dom,cont){
			 var weight=prompt("请输入权重值，必须为数字","");
			  if (weight!=null && weight!="")
			    {
				  ajax('/?act=notify&cont=service&dom='+dom+'&id='+id+"&weight="+weight+"&ser="+cont,function(str){alert(str);location.reload();});
			    }
		}
		function del(id){
			 var r=confirm("确认删除？");
			  if (r==true){
				  ajax('/?dom=bfw&act=delclient&cont=service&id='+id,function(str){alert(str);location.reload();});
			    }
		}
		function ajax(url,fnSucc){
			if(window.XMLHttpRequest)
			{
				var oAjax = new XMLHttpRequest();
			}
			else
			{
				var oAjax = new ActiveXObject("Microsoft.XMLHTTP");//IE6浏览器创建ajax对象
			}
			oAjax.open("GET",url,true);//把要读取的参数的传过来。
			oAjax.send();
			oAjax.onreadystatechange=function()
			{
				if(oAjax.readyState==4)
				{
					if(oAjax.status==200)
					{
						fnSucc(oAjax.responseText);//成功的时候调用这个方法
					}
					else
					{
						if(fnfiled)
						{
							fnField(oAjax.status);
						}
					}
				}
			};
		}
	</script>


<body
	style='padding: 20px; margin: 0; background: #40835e; color: white;'>
	<div id='cmdcont'>
		<h1>BFW SERVICE</h1>
		<form method="GET" action="/">
			<input type="text"
				value="<?=htmlspecialchars(isset($_GET['dom_inp'])?$_GET['dom_inp']:"")?>"
				id="dom_inp" name="dom_inp" placeholder="域" /> <input type="text"
				value="<?=htmlspecialchars(isset($_GET['url_inp'])?$_GET['url_inp']:"")?>"
				id="url_inp" name="url_inp" placeholder="服务方地址" /> <input
				type="text"
				value="<?=htmlspecialchars(isset($_GET['ser_inp'])?$_GET['ser_inp']:"")?>"
				id="ser_inp" name="ser_inp" placeholder="服务名" /> <input
				type="submit" value="搜搜" /> <input  onclick="servicereg();" type="button" value="服务注册" />
		</form>
		<table class="gridtable" width=“100%” style="width: 100%;">
			<tr>
				<th width="10%">域</th>
				<th width="20%">服务名</th>
				<th width="20%">服务方法</th>
				<th width="20%">提供服务方</th>
				<th width="5%">权重</th>
				<th width="5%">举报数</th>
				<th width="20%" style="text-align: center;">操作</th>
			</tr>
			<?php foreach ($service_array as $item){?>
			<tr>
				<td><?=$item['dom']?></td>
				<td><?=$item['cont']?></td>
				<td><?=$item['act']?></td>
				<td><?=$item['serviceurl']?></td>
				<td><?=$item['weight']?></td>
				<td><?=$item['reportnum']?></td>
				<td style="text-align: center;"><button
						onclick="adjust(<?=$item['id']?>,'<?=$item['dom']?>','<?=$item['cont']?>');">调整权重</button></td>
			</tr>
			<?php }?>
		
		</table>
		<h1>BFW CLIENT</h1>
		<table class="gridtable" width=“100%” style="width: 100%;">
			<tr>
				<th width="20%">域</th>
				<th width="20%">客户地址</th>
				<th width="20%">注册时间</th>
				<th width="20%">回调地址</th>
				<th width="20%">操作</th>

			</tr>
			<?php foreach ($client_array as $item){?>
			<tr>
				<td><?=$item['dom']?></td>
				<td><?=$item['customerip']?></td>
				<td><?=$item['gettime']?></td>
				<td><?=$item['notifyurl']?></td>
				<td style="text-align: center;"><button
						onclick="del(<?=$item['id']?>);">删除</button></td>
			</tr>
			<?php }?>
		
		</table>
	</div>
	<div style="margin: 20px 0;">
		Power by BFW<sup><?=VERSION?></sup>[SOA framework]
	</div>
</body>
</html>