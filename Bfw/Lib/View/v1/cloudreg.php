<html>
<head lang="zh-CN">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BFW-请先登录</title>
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
        }
        .wrap {
            width: 100%;
            height: 100%;
            padding: 140px 0;
            position: fixed;
            opacity: 0.8;
            background: linear-gradient(to bottom right,#1b6e95,#084589);
            background: -webkit-linear-gradient(to bottom right,#1b6e95,#084589);
        }
        .container {
        	padding:20px;
            width: 30%;
            margin: 0 auto;
        	background:#0870b2;
        }
        .container input {
            width: 100%;
            display: block;
            height: 36px;
            border: 0;
            outline: 0;
            padding: 6px 10px;
            line-height: 24px;
            margin: 32px auto;
            -webkit-transition: all 0s ease-in 0.1ms;
            -moz-transition: all 0s ease-in 0.1ms;
            transition: all 0s ease-in 0.1ms;
        }
        .container input[type="text"] , .container input[type="password"]  {
            background-color: #FFFFFF;
            font-size: 16px;
            color: #50a3a2;
        }
        .container input[type='submit'] {
            font-size: 16px;
            letter-spacing: 2px;
            color: #666666;
            background-color: #FFFFFF;
        }
        .container input:focus {
            
        }
        .container input[type='submit']:hover {
            cursor: pointer;
          
        }

        .ani_ele{
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
        }
        .ani_ele li {
            list-style-type: none;
            display: block;
            position: absolute;
            bottom: -120px;
            width: 15px;
            height: 15px;
            z-index: -8;
            background-color:rgba(255, 255, 255, 0.15);
            animotion: square 25s infinite;
            -webkit-animation: square 25s infinite;
        }
        .ani_ele  li:nth-child(1) {
            left: 20%;
            animation-duration: 10s;
            -moz-animation-duration: 10s;
            -o-animation-duration: 10s;
            -webkit-animation-duration: 10s;
        }
        .ani_ele li:nth-child(2) {
            width: 40px;
            height: 40px;
            left: 40%;
            animation-duration: 15s;
            -moz-animation-duration: 15s;
            -o-animation-duration: 15s;
            -webkit-animation-duration: 15s;
        }
        .ani_ele li:nth-child(3) {
            left: 60%;
            width: 25px;
            height: 25px;
            animation-duration: 12s;
            -moz-animation-duration: 12s;
            -o-animation-duration: 12s;
            -webkit-animation-duration: 12s;
        }
        .ani_ele li:nth-child(4) {
            width: 50px;
            height: 50px;
            left: 80%;
            -webkit-animation-delay: 3s;
            -moz-animation-delay: 3s;
            -o-animation-delay: 3s;
            animation-delay: 3s;
            animation-duration: 12s;
            -moz-animation-duration: 12s;
            -o-animation-duration: 12s;
            -webkit-animation-duration: 12s;
        }


        @keyframes square {
            0%  {
                    -webkit-transform: translateY(0);
                    transform: translateY(0)
                }
            100% {
                    bottom: 400px;
                    transform: rotate(600deg);
                    -webit-transform: rotate(600deg);
                    -webkit-transform: translateY(-500);
                    transform: translateY(-500)
            }
        }
        @-webkit-keyframes square {
            0%  {
                -webkit-transform: translateY(0);
                transform: translateY(0)
            }
            100% {
                bottom: 400px;
                transform: rotate(600deg);
                -webit-transform: rotate(600deg);
                -webkit-transform: translateY(-500);
                transform: translateY(-500)
            }
        }
        .nav_tab{
        	padding:0;
        	margin:0;
        	list-style:none;
        	clear:both;
        	height:40px;
        }
        .nav_tab li{
        	float:left;
        	height:40px;
        	width:50%;
        	background:#2596cc;
        	color:white;
        	text-align:center;
        	padding:10px;
        	cursor:pointer;
        }
        .nav_tab .selected{
        	background:#084589;
        	color:white;
        	font-weight:bold;
        }
    </style>
    <script language="JavaScript">
    function reg(){
    	document.getElementById("logbtn").className="";
    	document.getElementById("regbtn").className="selected";
    	document.getElementById("acttype").value="register";
    }
    function log(){
    	document.getElementById("logbtn").className="selected";
    	document.getElementById("regbtn").className="";
    	document.getElementById("acttype").value="login";
    }
		function loginreg(){
			var u=document.getElementById("username").value;
			var p=document.getElementById("password").value;
			var type=document.getElementById("acttype").value;
			if(u==""||p==""){
				return ;
			}
			ajax('?webide=1&'+type+'=' + u + "|" + p, function(str) {
				var obj = eval('(' + str + ')');
				if(obj.err){
					alert(obj.data);
				}else{
					location.href="/Cloud/"+obj.data+"/?webide=1";
				}
			});
			//ajax(url, function(str){if(str=="ok"){location.href=oldurl;}else{alert(str);}}, "post", "username="+u+"&password="+p) ;
		
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
            <ul class="nav_tab"><li id="logbtn" class="selected" onclick="log()">登录</li><li onclick="reg()"  id="regbtn" > 注册</li></ul>
            <form method="POST" action="/">
             <input type="hidden"  id="acttype"  name="acttype"  value="login" />
                <input type="text"  id="username"  name="username" placeholder="请输入用户名"/>
                <input type="password"  id="password" name="password"  placeholder="请输入密码"/>
                <input type="button" value="提   交"  onclick="loginreg();"/>
            </form>
        </div>
        <ul class="ani_ele">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
   

        </ul>
        <div style="margin: 0; text-align:center; color:white;">Power by BFW<sup><?=VERSION?></sup>[SOA framework]</div>
    </div>
    
</body>
</html>