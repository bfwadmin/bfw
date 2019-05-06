<html>
<head lang="zh-CN">
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>请先登录</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
<link rel="shortcut icon" href="/favicon.ico" />
<style type="text/css">
@media only screen and (max-width:640px) {
	.container {
		width: 100%;
		margin: 0 auto;
		text-align: center;
		color: #f3f3f3;
	}
}

@media only screen and (min-width:640px) {
	.container {
		width: 35%;
		margin: 0 auto;
		text-align: center;
		color: #f3f3f3;
	}
}

* {
	box-sizing: border-box;
}

body {
	margin: 0;
	padding: 0;
	width: 100%;
	height: 100vh;
	font: 1em microsft yahei;
	background: linear-gradient(to bottom right, #1b6e95, #084589);
	background: -webkit-linear-gradient(to bottom right, #1b6e95, #084589);
	overflow: hidden;
}

.wrap {
	width: 100%;
	padding: 3.135em 0;
	opacity: 0.8;
}

.container h1 {
	text-align: center;
	color: #FFFFFF;
	font-weight: 500;
}

.container input {
	width: 70%;
	display: block;
	height: 2em;
	border: 0;
	outline: 0;
	padding: 0.135em 0.85em;
	line-height: 1.5em;
	margin: 2em auto;
	-webkit-transition: all 0s ease-in 0.1ms;
	-moz-transition: all 0s ease-in 0.1ms;
	transition: all 0s ease-in 0.1ms;
}

.container input[type="text"], .container input[type="password"] {
	background-color: #FFFFFF;
	font-size: 1em;
	color: #50a3a2;
}

.container input[type='submit'] {
	font-size: 1em;
	letter-spacing: 2px;
	color: #666666;
	background-color: #FFFFFF;
}

.wrap ul {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	z-index: -10;
}

.wrap ul li {
	list-style-type: none;
	display: block;
	position: absolute;
	bottom: -120px;
	width: 15px;
	height: 15px;
	z-index: -8;
	background-color: rgba(255, 255, 255, 0.15);
	animotion: square 25s infinite;
	-webkit-animation: square 25s infinite;
}

.wrap ul li:nth-child(1) {
	left: 0;
	animation-duration: 10s;
	-moz-animation-duration: 10s;
	-o-animation-duration: 10s;
	-webkit-animation-duration: 10s;
}

.wrap ul li:nth-child(2) {
	width: 40px;
	height: 40px;
	left: 10%;
	animation-duration: 15s;
	-moz-animation-duration: 15s;
	-o-animation-duration: 15s;
	-webkit-animation-duration: 15s;
}

.wrap ul li:nth-child(3) {
	left: 20%;
	width: 25px;
	height: 25px;
	animation-duration: 12s;
	-moz-animation-duration: 12s;
	-o-animation-duration: 12s;
	-webkit-animation-duration: 12s;
}

.wrap ul li:nth-child(4) {
	width: 50px;
	height: 50px;
	left: 30%;
	-webkit-animation-delay: 3s;
	-moz-animation-delay: 3s;
	-o-animation-delay: 3s;
	animation-delay: 3s;
	animation-duration: 12s;
	-moz-animation-duration: 12s;
	-o-animation-duration: 12s;
	-webkit-animation-duration: 12s;
}

@
keyframes square { 0% {
	-webkit-transform: translateY(0);
	transform: translateY(0)
}

100%
{
bottom
:

400
px
;


transform
:

rotate
(600deg);


-webit-transform
:

rotate
(600deg);


-webkit-transform
:

translateY
(-500);


transform
:

translateY
(-500)


}
}
@
-webkit-keyframes square { 0% {
	-webkit-transform: translateY(0);
	transform: translateY(0)
}
100%
{
bottom
:

400
px
;


transform
:

rotate
(600deg);


-webit-transform
:

rotate
(600deg);


-webkit-transform
:

translateY
(-500);


transform
:

translateY
(-500)


}
}
</style>
<script language="JavaScript">
		function login(){
			var u=document.getElementById("username").value;
			var p=document.getElementById("password").value;
			var url="<?=$refer?>";
			var oldurl="<?=$refer?>";
			var lst=url.substring(url.length-1,url.length);
			if(lst=="/"){
				 url=url+"?username="+u+"&password="+p;
			}else if(lst=="&"){
				url=url+"username="+u+"&password="+p;
			}else{
				if(url.indexOf('?')!==-1){
					url=url+"&username="+u+"&password="+p;
				}else{
					 url=url+"?username="+u+"&password="+p;
				}
			}
			ajax(url, function(str){if(str=="ok"){location.href=oldurl;}else{alert(str);}}, "post", "username="+u+"&password="+p) ;

		}
		function ajax(url, fnSucc, method, data) {
			if (window.XMLHttpRequest) {
				var oAjax = new XMLHttpRequest();
			} else {
				var oAjax = new ActiveXObject("Microsoft.XMLHTTP");// IE6浏览器创建ajax对象
			}
			if (method == "post") {
				oAjax.open("POST", url, true);// 把要读取的参数的传过来。
				oAjax.setRequestHeader("Content-type",
						"application/x-www-form-urlencoded");
			} else {
				oAjax.open("GET", url, true);// 把要读取的参数的传过来。
			}
			oAjax.setRequestHeader("bfwajax", "v<?=VERSION?>"); // 可以定义请求头带给后端
			if (method == "post") {
				oAjax.send(data);
			} else {
				oAjax.send();
			}

			oAjax.onreadystatechange = function() {
				if (oAjax.readyState == 4) {
					if (oAjax.status == 200) {
						fnSucc(oAjax.responseText);// 成功的时候调用这个方法
					} else {
						if (fnfiled) {
							fnField(oAjax.status);
						}
					}
				}
			};
		};
	</script>
</head>
<body>
	<div class="wrap">
		<div class="container">
			<h1>请先登录</h1>
			<form method="POST" action="/">
				<input type="text" id="username" name="username"
					placeholder="请输入用户名" /> <input type="password" id="password"
					name="password" placeholder="请输入密码" /> <input type="button"
					value="登   录" onclick="login();" />
			</form>
		</div>
		<ul>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>


		</ul>
		<div style="margin: 0; text-align: center; color: white;">
			Power by BFW<sup><?=VERSION?></sup>[SOA framework]
		</div>
	</div>

</body>
</html>