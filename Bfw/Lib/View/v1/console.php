<html>
	<title>Bfw comsole</title>
	<style>
		#cmdcont p{
			padding:0;
			margin:5px;
			font-size:18px;
			color:#c3c3c3;
			word-break:break-all;
		}
	</style>
	<script language="JavaScript">
		//var cmdlist=new Array("bfw init","bfw code","bfw getapp","bfw sendapp","bfw listapp","bfw help");
		function init(){
			document.getElementById('bfw_input').focus();
		}
		function ajaxback(str){
			var cmd=document.getElementById("bfw_input").value;
			var d=document.getElementById("cmdcont");
			var p=document.createElement('p');
			p.innerText=cmd+">>"+str;
			d.appendChild(p);
			document.getElementById("bfw_input").value="";
		}
		function bfw_cmd(){
			var reg = /^[a-zA-Z]{3,15}$/;
			if (event.keyCode == 13) {
				//alert(document.getElementById("bfw_input").value);
				var cmd=document.getElementById("bfw_input").value;
				if(cmd!=""){
					var cmdarr=cmd.split(" ");
					if(cmdarr[0].toLowerCase()=="bfw"){
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="init"){
							if(!reg.test(cmdarr[2])){
								ajaxback("init后的名称必须为英文，请重试");
								return;
							}
							ajax('/?initproject='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="code"){
							if(!reg.test(cmdarr[2])){
								ajaxback("code后的名称必须为英文，请重试");
								return;
							}
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="getapp"){
							if(!reg.test(cmdarr[2])){
								ajaxback("getapp后的名称必须为英文，请重试");
								return;
							}
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="sendapp"){
							if(!reg.test(cmdarr[2])){
								ajaxback("sendapp后的名称必须为英文，请重试");
								return;
							}
							ajax('/?uploadapp='+cmdarr[2],function(str){ajaxback(str);});  
							//document.getElementById("bfw_input").disabled=true;
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="login"){
							if(!reg.test(cmdarr[2])){
								ajaxback("登录名必须为英文，请重试");
								return;
							}
							ajax('/?login='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="add"){
							if(!reg.test(cmdarr[2])){
								ajaxback("appname必须为英文，请重试");
								return;
							}
							if(!reg.test(cmdarr[3])){
								ajaxback("controlname必须为英文，请重试");
								return;
							}
							ajax('/?addcont='+cmdarr[2]+"|"+cmdarr[3],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>1&&cmdarr[1].toLowerCase()=="help"){
							ajaxback("请输入bfw init projectname或bfw code tablename或bfw getapp appname或bfw listapp或bfw sendapp appname");
							return;
						}
						if(cmdarr.length>1&&cmdarr[1].toLowerCase()=="listapp"){
							ajax('/?listapp=1',function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}else{
							ajaxback("不支持的命令");
						}
						
					}else{
						ajaxback("不支持的命令");
					}
						          
						
				}
				
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
<body style='padding:20px;margin:0;background:black;color:white;' onload="init();">
	<div id='cmdcont'></div>
	
	<input placeholder='bfw <?=VERSION?>/' id="bfw_input" onkeypress="bfw_cmd();"  style='font-size:18px;outline:none;padding:5px;color:white;width:100%;height:40px;border:none;background:black;' type='text'' />
	</body>
	</html>