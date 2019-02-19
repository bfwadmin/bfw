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
<link rel="stylesheet" media="all" href="?webide=1&getstatic=/webide.css" />
</head>
<body  onload="RunOnBeforeUnload()">
<div id="mask"  class="pos-abs v-fullscreen opacity-7 color-black zindex-99999 dis-hide">
<div id="notice">loading...</div>
</div>
	<header>
		<ul>
			<li class="logo">BFW</li>
			<li>开发设置</li>
			<li>数据库连接</li>
		    <li>云端存储</li>
			<li>提交问题</li>
			<li>模板商城</li>
			<li>文档教程</li>
			<li>关于我们</li>
			<li class="userinfio">登录/注册</li>
		</ul>
	</header>
	<div id="newprodia"  class="popup_dia">
		<input type="text" id="proname" name="proname" placeholder="请输入项目名称" />
		<input type="text" id="mysqlhost" name="mysqlhost"
			placeholder="请输入mysql地址" /> <input type="text" id="mysqlport"
			name="mysqlport" placeholder="请输入mysql端口" /> <input type="text"
			id="mysqlname" name="mysqlname" placeholder="请输入mysql用户名" /> <input
			type="text" id="mysqlpwd" name="mysqlpwd" placeholder="请输入mysql密码" />
		<input type="button" value="选择模板" onclick="" /> <input type="button"
			value="新建" onclick="" />
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
			<span id="php_tree_tab"  class="dir_s_tab">动态</span><span id="static_tree_tab"  class="dir_tab">静态</span>
				<span id="project_name"></span> 
				<span class="close_btn"
					onclick="hideediter();">×</span>
			</div>

			<div id="dong_tree"  class="tree"></div>
		    <div id="jing_tree" class="tree"  style="display: none;"></div>
			<div class="scrollbar"></div>

		</div>
		<div id="file_tab"><ul></ul></div>
		<div id="editor">

		</div>

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
<script src="?webide=1&getstatic=/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="?webide=1&getstatic=/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<script src="?webide=1&getstatic=/webide.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>