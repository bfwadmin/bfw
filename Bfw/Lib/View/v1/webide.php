<html>
<head lang="zh-CN">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BFW网页开发环境</title>
    <meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
    <meta name="description" content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
	<link rel="shortcut icon" href="/favicon.ico" />
<style type="text/css">
* {
	box-sizing: border-box;
}

body {
	margin: 0;
	padding: 0;
	font: 16px/20px microsft yahei;
	background: #50a3a2;
}

.project {
	list-style: "none";
	padding: 0;
	margin: 0;
	clear: both;
}

#editor {
	background: black;
	color: yellow;
	height: 100vh;
	width: 100%;
	clear: both; #
	display: none;
	position: absolute;
	top: 0;
	left: 0;
}

#dir {
	width: 20%;
	float: left;
}

#code_edt {
	width: 80%;
	float: left;
}

.project li {
	display: block;
	float: left;
	width: 100px;
	height: 100px;
	margin: 5px;
	border: 1px solid #ffffff;
	cursor: pointer;
	padding: 35px 5px;
	text-align: center;
	color: white;
}

#newprodia {
	display: none;
	position: absolute;
	left: 40%;
	top: 40%;
	width: 500px;
	height: 300px;
	background: #ffffff;
	padding: 10px;
	line-height: 20px;
}

#editarea {
	background: black;
	color: #ffffff;
	width: 100%;
	height: 100%;
	outline: none;
	resize: none;
}
</style>
<script language="JavaScript">
		function openpro(p){
			if(p=="."){
				document.getElementById("newprodia").style.display="block";
			}else{

			}
			//var u=document.getElementById("username").value;
			//var p=document.getElementById("password").value;
		
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
			oAjax.setRequestHeader("bfwajax","v<?=VERSION?>"); // 可以定义请求头带给后端
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
</head>
<body>
	<div id="newprodia">
		<input type="text" id="proname" name="proname" placeholder="请输入项目名称" />
		<input type="text" id="mysqlhost" name="mysqlhost"
			placeholder="请输入mysql地址" /> <input type="text" id="mysqlport"
			name="mysqlport" placeholder="请输入mysql端口" /> <input type="text"
			id="mysqlname" name="mysqlname" placeholder="请输入mysql用户名" /> <input
			type="text" id="mysqlpwd" name="mysqlpwd" placeholder="请输入mysql密码" />
		<input type="button" value="选择模板" onclick="" /> <input type="button"
			value="新建" onclick="" />
	</div>
	<div style="padding: 10px; height: 100vh;">
		<h1>最近项目</h1>
		<ul class="project">
			<li onclick="openpro('.')">新建项目</li>
         <?php
        foreach ($app_list_data as $item) {
            ?>
         <li onclick="openpro'<?=$item?>')"><?=$item?> </li>
         <?php
        }
        ?>
         </ul>
	</div>
	<div id="editor">
		<div>title-关闭</div>
		<div id="dir">munu</div>
		<div id="code_edt">
			<textarea id="editarea" rows="40" cols="80"></textarea>
		</div>
	</div>
	<div style="margin: 0; text-align: center; clear: both; color: white;">
		Power by BFW<sup><?=VERSION?></sup>[SOA framework]
	</div>
</body>
</html>