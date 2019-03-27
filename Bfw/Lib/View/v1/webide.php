<html>
<head lang="zh-CN">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BFW网页开发环境</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" media="all" href="?webide=1&getstatic=/tree.css" />
<link rel="stylesheet" media="all"
	href="?webide=1&getstatic=/webide.css" />

</head>
<body onload="RunOnBeforeUnload()">
	<div id="mask"
		class="pos-abs v-fullscreen opacity-7 color-black zindex-99999 dis-hide">
		<div id="notice">loading...</div>
	</div>
	<header>
		<ul>
			<li class="logo">BFW</li>
			<li>开发设置</li>
			<li onclick="dbconfshow();">数据库连接</li>
			<li>云端存储</li>
			<li>提交问题</li>
			<li>模板商城</li>
			<li>文档教程</li>
			<li>关于我们</li>
			<li class="userinfio" onclick="popup($('#login'));">登录/注册</li>
		</ul>
	</header>
	<div id="loadding"
		style="position: absolute; top: 0; left: 0; background: black; color: grey; height: 100vh; width: 100%; text-align: center; line-height: 400px;">
		加载中.........</div>
	<div id="runaction" class="popup_dia" style="width: 30%; height: 40%;">
		<div class="popup_title">
			<span>执行的动作器</span> <span onclick="popclose('runaction')"
				class="popup_close">×</span>
		</div>
		<p id="actionlist">
		</p>

	</div>
	<div id="addcontroler" class="popup_dia">
		<div class="popup_title">
			<span>新建控制器</span> <span onclick="popclose('addcontroler')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" class="popup_textin" id="controlername"
				name="controlername" placeholder="请输入控制器名称" />
		</p>

		<p>
			<input type="checkbox" />普通 <input type="checkbox" />接口
		</p>
		<p>
			<input type="checkbox" />生成数据库 <input style="width: 100px;"
				type="button" value="添加字段" class="popup_btn"
				onclick="addcontroler()" />
		</p>
		<p>
			<input type="text" class="popup_textin" name="filed" placeholder="名称"
				style="width: 20%;" /> <select class="popup_textin"
				style="width: 20%;"><option value="dd">ddd</select> <select
				class="popup_textin" style="width: 20%;"><option value="dd">ddd</select><input
				style="width: 10%;" type="button" value="×" class="popup_btn"
				onclick="addcontroler()" />
		</p>
		<p>
			<input type="text" class="popup_textin" name="filed" placeholder="名称"
				style="width: 20%;" /> <select class="popup_textin"
				style="width: 20%;"><option value="dd">ddd</select> <select
				class="popup_textin" style="width: 20%;"><option value="dd">ddd</select><input
				style="width: 10%;" type="button" value="×" class="popup_btn"
				onclick="addcontroler()" />
		</p>
		<p>
			<input type="checkbox" />生成其他层POINT VIEW VALIDATE SERVICE CLIENT
			MODEL
		</p>
		<p>
			<input type="button" value="确   定" class="popup_btn"
				onclick="addcontroler()" />
		</p>
	</div>
	<div id="login" class="popup_dia" style="width: 30%; height: 40%;">
		<div class="popup_title">
			<span>登录</span> <span onclick="popclose('login')" class="popup_close">×</span>
		</div>
		<p>
			<input type="text" class="popup_textin" id="loginusername"
				name="loginusername" placeholder="请输入用户名" />
		</p>
		<p>
			<input type="password" class="popup_textin" id="loginpwd"
				name="loginpwd" placeholder="请输入密码" />
		</p>
		<p onclick="popclose('login');popup($('#register'));">没有账号？点击注册</p>
		<p>
			<input type="button" value="登    录" class="popup_btn"
				onclick="login()" />
		</p>
	</div>
	<div id="register" class="popup_dia" style="width: 30%; height: 30%;">
		<div class="popup_title">
			<span>注册</span> <span onclick="popclose('register')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" class="popup_textin" id="regusername"
				name="regusername" placeholder="请输入用户名" />
		</p>
		<p>
			<input type="password" class="popup_textin" id="regpwd" name="regpwd"
				placeholder="请输入密码" />
		</p>
		<p>
			<input type="button" value="注   册" class="popup_btn"
				onclick="register()" />
		</p>
	</div>
	<div id="mysqlconf" class="popup_dia" style="height: 30%;">
		<div class="popup_title">
			<span>mysql设置</span> <span onclick="popclose('mysqlconf')"
				class="popup_close">×</span>
		</div>

		<input style="width: 23%;" type="text" id="mysqlhost"
			class="popup_textin" name="mysqlhost" placeholder="请输入mysql地址" /> <input
			style="width: 23%;" type="text" id="mysqlport" name="mysqlport"
			placeholder="请输入mysql端口" class="popup_textin" /> <input type="text"
			id="mysqlname" name="mysqlname" placeholder="请输入mysql用户名"
			style="width: 23%;" class="popup_textin" /> <input type="text"
			id="mysqlpwd" name="mysqlpwd" placeholder="请输入mysql密码"
			style="width: 23%;" class="popup_textin" />
		<p>

			<input type="button" value="设     置" class="popup_btn"
				onclick="mysqlconf()" />
		</p>
	</div>
	<div id="newprodia" class="popup_dia">
		<div class="popup_title">
			<span>新建项目</span> <span onclick="popclose('newprodia')"
				class="popup_close">×</span>
		</div>

		<input type="text" class="popup_textin" id="proname" name="proname"
			placeholder="请输入项目名称" />

		<div style="margin: 5px 5px 0 5px;">从模板创建</div>
		<hr></hr>
		<ul class="temp_ul" id="choose_temp">
			<li tempid="empty">空白</li>
			<li tempid="gfdgdfg">电商</li>
			<li tempid="dxadvcbcsdfsf">企业</li>
			<li tempid="dfgd">后台</li>
			<li tempid="dxasbbcvdfsf">新闻</li>
			<li tempid="dxascvbdfsf">博客</li>
		</ul>
		<p>

			<input type="button" value="创     建" class="popup_btn"
				onclick="creatpro()" />
		</p>
	</div>
	<div id="wellcomepage">
		<h1>最近项目</h1>
		<ul class="project" id="latest_pro">
		</ul>
	</div>
	<div id="editorpannel">
		<!-- <div onclick="alert(editor.getValue());">title-关闭</div> -->
		<div id="dir">
			<div class="dir_title">
				<span id="php_tree_tab" class="dir_s_tab">动态</span><span
					id="static_tree_tab" class="dir_tab">静态</span> <span
					id="project_name"></span> <span class="close_btn"
					onclick="hideediter();">×</span>
			</div>

			<div id="dong_tree" class="tree"></div>
			<div id="jing_tree" class="tree" style="display: none;"></div>
			<div class="scrollbar"></div>

		</div>
		<div id="file_tab">
			<ul></ul>
		</div>
		<div id="editor"></div>

	</div>
	<div class="footer">
		Power by BFW<sup><?=VERSION?></sup>[SOA framework]
	</div>
	<script src="?webide=1&getstatic=/tree.js" type="text/javascript"
		charset="utf-8"></script>
	<script src="?webide=1&getstatic=/jquery.js" type="text/javascript"
		charset="utf-8"></script>
	<script src="?webide=1&getstatic=/bfw.js" type="text/javascript"
		charset="utf-8"></script>
	<script src="?webide=1&getstatic=/ace.js" type="text/javascript"
		charset="utf-8"></script>
	<script src="?webide=1&getstatic=/ext-language_tools.js"
		type="text/javascript" charset="utf-8"></script>
	<script src="?webide=1&getstatic=/webide.js" type="text/javascript"
		charset="utf-8"></script>
</body>
</html>