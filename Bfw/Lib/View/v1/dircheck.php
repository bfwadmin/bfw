<html>
<head lang="zh-CN">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BFW环境检测</title>
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
            width: 80%;
            margin: 0 auto;
        	background:#0870b2;
        	height:80%;
        	color:white;
        }
        .container h1 {
            text-align: center;
            color: #FFFFFF;
            font-weight: 500;
        }
        .container input {
            width: 320px;
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
            width: 400px;
        }
        .container input[type='submit']:hover {
            cursor: pointer;
            width: 400px;
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
            background-color:rgba(255, 255, 255, 0.15);
            animotion: square 25s infinite;
            -webkit-animation: square 25s infinite;
        }
        .wrap ul li:nth-child(1) {
            left: 0;

        }
        .wrap ul li:nth-child(2) {
            width: 40px;
            height: 40px;
            left: 10%;

        }
        .wrap ul li:nth-child(3) {
            left: 20%;
            width: 25px;
            height: 25px;

        }
        .wrap ul li:nth-child(4) {
            width: 50px;
            height: 50px;
            left: 30%;
  
        }
        .wrap ul li:nth-child(5) {
            width: 60px;
            height: 60px;
            left: 40%;

        }
        .wrap ul li:nth-child(6) {
            width: 75px;
            height: 75px;
            left: 50%;
        }
        .wrap ul li:nth-child(7) {
            left: 60%;

        }
        .wrap ul li:nth-child(8) {
            width: 90px;
            height: 90px;
            left: 70%;
        }
        .wrap ul li:nth-child(9) {
            width: 100px;
            height: 100px;
            left: 80%;
        }
        .wrap ul li:nth-child(10) {
            width: 120px;
            height: 120px;
            left: 90%;

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
			ajax(url,function(str){if(str=="ok"){location.href=oldurl;}else{alert(str);}});
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
    <div class="wrap">
        <div class="container">
            <h1>请确保以下目录可以写入</h1>
         <?php 
         foreach ($dir_check_arr as $item){
         ?>
         <?=$item?>
         <?php 
         }
         ?>
        </div>
        <div style="margin: 0; text-align:center; color:white;">Power by BFW<sup><?=VERSION?></sup>[SOA framework]</div>
    </div>
</body>
</html>