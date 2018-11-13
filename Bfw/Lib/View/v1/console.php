<html>
	<title>Bfw comsole</title>
	<style>
		#cmdcont p{
			padding:0;
			margin:5px;
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
			if (event.keyCode == 13) {
				//alert(document.getElementById("bfw_input").value);
				var cmd=document.getElementById("bfw_input").value;
				if(cmd!=""){
					var cmdarr=cmd.split(" ");
					if(cmdarr[0]=="bfw"){
						if(cmdarr.length>2&&cmdarr[1]=="init"){
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
						}
						if(cmdarr.length>2&&cmdarr[1]=="code"){
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
						}
						if(cmdarr.length>2&&cmdarr[1]=="getapp"){
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
						}
						if(cmdarr.length>2&&cmdarr[1]=="sendapp"){
							ajax('/?getapp='+cmdarr[2],function(str){ajaxback(str)});  
						}
						if(cmdarr.length>1&&cmdarr[1]=="help"){
							ajaxback("请输入bfw init projectname或bfw code tablename或bfw getapp appname或bfw listapp或bfw sendapp appname");
						}
						if(cmdarr.length>1&&cmdarr[1]=="listapp"){
							ajax('/?listapp=1',function(str){ajaxback(str)});  
						}
						
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
	
	<input placeholder='bfw <?=VERSION?>/' id="bfw_input" onkeypress="bfw_cmd();"  style='outline:none;padding:5px;color:white;width:100%;height:30px;border:none;background:black;' type='text'' />
	</body>
	</html>