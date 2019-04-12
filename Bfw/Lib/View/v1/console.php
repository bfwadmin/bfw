<html>
<head lang="zh-CN">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BFW控制台</title>
    <meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
    <meta name="description" content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
	<link rel="shortcut icon" href="/favicon.ico" />
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
	    var cmdindex=0; 
		var cmdlist=["bfw init","bfw code","bfw getapp","bfw sendapp","bfw listapp","bfw help"];
		
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
		function bfw_cmd(event){
			var reg = /^[a-zA-Z]{3,15}$/;
			//alert(event.keyCode);
			if (event.keyCode == 38) {
			
				if(cmdlist.length>cmdindex){
					cmdindex++;
				}else{
					cmdindex=0;
				}
				document.getElementById("bfw_input").value=cmdlist[cmdindex];
				
			}
			if (event.keyCode == 40) {
				if(cmdindex>0){
					cmdindex--;
				}else{
					cmdindex=cmdlist.length-1;
				}
				document.getElementById("bfw_input").value=cmdlist[cmdindex];
			}
			if (event.keyCode ==37) {
				if(cmdlist.length>cmdindex){
					cmdindex++;
				}else{
					cmdindex=0;
				}
				document.getElementById("bfw_input").value=cmdlist[cmdindex];
			}
			if (event.keyCode ==39) {

			}
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
							var uname=localStorage.getItem("user");
						    var dhost=localStorage.getItem("host");
						    var dport=localStorage.getItem("port");
						    var dpwd=localStorage.getItem("pwd");
						    if(uname==null||dhost==null){
						    	ajaxback("请先设置数据库连接信息 bfw db host 127.0.0.1~");
						    	return;
						    }
						    if(dport==null){
						    	dport=3306;
						    }
						    if(dpwd==null){
						    	dpwd="";
						    }
						    var dbinfo=dhost+"|"+dport+"|"+uname+"|"+dpwd;
						    if(cmdarr.length==4){
						    	ajax('/?console=1&initapp='+cmdarr[2]+"&dbinfo="+dbinfo+"&tempid="+cmdarr[3],function(str){ajaxback(str)});  
						    }else{
						    	ajax('/?console=1&initapp='+cmdarr[2]+"&dbinfo="+dbinfo,function(str){ajaxback(str)});  
						    }
							
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="code"){
							if(!reg.test(cmdarr[2])){
								ajaxback("code后的名称必须为英文，请重试");
								return;
							}
							ajax('/?console=1&getapp='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length==4&&cmdarr[1].toLowerCase()=="db"){
						    localStorage.setItem(cmdarr[2],cmdarr[3]);
							ajaxback("设置成功~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="getapp"){
							if(!reg.test(cmdarr[2])){
								ajaxback("getapp后的名称必须为英文，请重试");
								return;
							}
							var uname=localStorage.getItem("user");
						    var dhost=localStorage.getItem("host");
						    var dport=localStorage.getItem("port");
						    var dpwd=localStorage.getItem("pwd");
						    if(uname==null||dhost==null){
						    	ajaxback("请先设置数据库连接信息 bfw db host 127.0.0.1~");
						    	return;
						    }
						    if(dport==null){
						    	dport=3306;
						    }
						    if(dpwd==null){
						    	dpwd="";
						    }
						    var dbinfo=dhost+"|"+dport+"|"+uname+"|"+dpwd;
							if(cmdarr.length==4){
								ajax('/?console=1&getapp='+cmdarr[2]+"&appname="+cmdarr[3]+"&dbinfo="+dbinfo+"&fromtemp=1",function(str){ajaxback(str)});  
							}else{
								ajax('/?console=1&getapp='+cmdarr[2]+"&dbinfo="+dbinfo,function(str){ajaxback(str)});  
							}
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="sendapp"){
							if(!reg.test(cmdarr[2])){
								ajaxback("sendapp后的名称必须为英文，请重试");
								return;
							}
							if(cmdarr.length==4){
								ajax('/?console=1&uploadapp='+cmdarr[2]+"&totemp=1",function(str){ajaxback(str);});  
							}else{
								ajax('/?console=1&uploadapp='+cmdarr[2],function(str){ajaxback(str);});  
							}
							
							//document.getElementById("bfw_input").disabled=true;
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="login"){
							//if(!reg.test(cmdarr[2])){
								//ajaxback("登录名必须为英文，请重试");
								//return;
							//}
							ajax('/?console=1&login='+cmdarr[2],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>2&&cmdarr[1].toLowerCase()=="register"){
							//if(!reg.test(cmdarr[2])){
								//ajaxback("登录名必须为英文，请重试");
								//return;
							//}
							ajax('/?console=1&register='+cmdarr[2],function(str){ajaxback(str)});  
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
							ajax('/?console=1&addcont='+cmdarr[2]+"|"+cmdarr[3],function(str){ajaxback(str)});  
							ajaxback("执行中，请稍后~");
							return;
						}
						if(cmdarr.length>1&&cmdarr[1].toLowerCase()=="help"){
							ajaxback("请输入\r\nbfw db user root\r\nbfw db pwd root\r\nbfw db host 127.0.0.1\r\nbfw db port 3306\r\nbfw init projectname (tempid)\r\nbfw add projectname controlname\r\nbfw getapp appname (tempid)\r\nbfw listapp\r\nbfw sendapp appname (totemp)");
							return;
						}
						if(cmdarr.length>1&&cmdarr[1].toLowerCase()=="listapp"){
							ajax('/?console=1&listapp=1',function(str){ajaxback(str)});  
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
	
	<input placeholder='bfw <?=VERSION?>/' id="bfw_input" onkeypress="bfw_cmd(event);"  style='font-size:18px;outline:none;padding:5px;color:white;width:100%;height:40px;border:none;background:black;' type='text'' />
	</body>
	</html>