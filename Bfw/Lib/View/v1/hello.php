<!DOCTYPE html>
<html>
<head lang="zh-CN">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BFW多语言SOA开发框架及开发环境</title>
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
            height: 400px;
            padding: 40px 0;
            position: fixed;
            top: 50%;
            margin-top: -200px;
            opacity: 0.8;
            background: linear-gradient(to bottom right,#1b6e95,#084589);
            background: -webkit-linear-gradient(to bottom right,#1b6e95,#084589);
        }
        .container {
            width: 60%;
            margin: 0 auto;
        	text-align:center;
        	color:#f3f3f3;
        }
        .container h1 {
            color: #FFFFFF;
            font-weight: 500;
        	margin:20px;
        }
        .container h4 {
            color: #ededed;
        	line-height:30px;
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
        .wrap ul li:nth-child(5) {
            width: 60px;
            height: 60px;
            left: 40%;
            animation-duration: 10s;
            -moz-animation-duration: 10s;
            -o-animation-duration: 10s;
            -webkit-animation-duration: 10s;
        }
        .wrap ul li:nth-child(6) {
            width: 75px;
            height: 75px;
            left: 50%;
            -webkit-animation-delay: 7s;
            -moz-animation-delay: 7s;
            -o-animation-delay: 7s;
            animation-delay: 7s;
        }
        .wrap ul li:nth-child(7) {
            left: 60%;
            animation-duration: 8s;
            -moz-animation-duration: 8s;
            -o-animation-duration: 8s;
            -webkit-animation-duration: 8s;
        }
        .wrap ul li:nth-child(8) {
            width: 90px;
            height: 90px;
            left: 70%;
            -webkit-animation-delay: 4s;
            -moz-animation-delay: 4s;
            -o-animation-delay: 4s;
            animation-delay: 4s;
        }
        .wrap ul li:nth-child(9) {
            width: 100px;
            height: 100px;
            left: 80%;
            animation-duration: 20s;
            -moz-animation-duration: 20s;
            -o-animation-duration: 20s;
            -webkit-animation-duration: 20s;
        }
        .wrap ul li:nth-child(10) {
            width: 120px;
            height: 120px;
            left: 90%;
            -webkit-animation-delay: 6s;
            -moz-animation-delay: 6s;
            -o-animation-delay: 6s;
            animation-delay: 6s;
            animation-duration: 30s;
            -moz-animation-duration: 30s;
            -o-animation-duration: 30s;
            -webkit-animation-duration: 30s;
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
        .menu{
        	margin:30px;
        	color:#484848;
        	height:40px;

        }
        .menu a{
        	margin:15px;
        	cursor:pointer;
        }
        .menu a:link{
        	color:#a9e1ff;
        }
        .menu a:visited{
        	color:#70c6f3;
        }
  .menu a:hover{
        	font-size:20px;
  	font-weight:bold;
  	color:white;

        }
        .menu a:active{
        	color:#484848;
        }

    </style>
</head>
<body>
    <div class="wrap">
        <div class="container">
            <h1>欢迎您选择BFW</h1>
           <h4>全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富</h4>
           <div class="menu">
          <a href="#">进入官网</a>
                <a href="?webide=1">进入WEBIDE开发</a>
                <a href="?console=1">进入CONSOLE</a>
                  <a href="#">查看开发文档</a>
                     <a href="#">查看优质模板代码</a></div>
        </div>
          <div style="margin: 0; text-align:center; color:white;">Power by BFW<sup><?=VERSION?></sup>[SOA framework]</div>
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>

    </div>

</body>
</html>