<html>
<head lang="zh-CN">
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>BFW网页开发环境</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" media="all" href="?webide=1&getstatic=/tree.css" />
<link rel="stylesheet" media="all"
	href="?webide=1&getstatic=/webide.css" />
<style type="text/css">
.ace_autocomplete {
	width: 600px !important;
}
.ace_gutter-cell .bfw-breakpointer{
	border-radius:4px;
	background:red;
	width:8px;
	height:8px;
	display:inline-block;
}
.ace_gutter-cell.ace_breakpoint{
    border-radius: 20px 0px 0px 20px;
    box-shadow: 0px 0px 1px 1px red inset;
	background:url("?webide=1&getstatic=/breakpointer.png") 3px -1px no-repeat;
}
.ace_gutter-cell.ace_coderunstatus{
    border-radius: 20px 0px 0px 20px;
    box-shadow: 0px 0px 1px 1px green inset;
	background:url("?webide=1&getstatic=/breakpointer.png") 3px -25px no-repeat;
}
</style>
</head>
<body onload="RunOnBeforeUnload()">
	<div id="mask" class="pos-abs v-fullscreen color-black "
		style="z-index: 999998; opacity: 0.2;">
		<div id="notice" style="text-align: center;">
			<img src="?webide=1&getstatic=/loading.gif" />
			<p style="font-size: 12px; padding: 0; color: grey; margin: 3px;">请求中,请稍后</p>
		</div>
	</div>
	<header>
		<ul>
			<li class="logo">BFW STUDIO<span class="logo_vers">v1.0</span></li>
			<li class="navitem dis-hide" id="project-menu">
				<div>
					<p>
						<a>当前项目</a>
					</p>
					<p onclick="popup($('#addtemplate'))">
						<a>提交模板</a>
					</p>
					<p onclick="versoncontrol(project_name)">
						<a>版本管理</a>
					</p>
					<p onclick="versoncontrol(project_name)">
						<a>项目部署</a>
					</p>
					<p onclick="showapppower(project_name)">
						<a>权限管理</a>
					</p>
					<p onclick="getlog(project_name)">
						<a>修改日志</a>
					</p>
				</div>
			</li>
			<?php if(DEV_PLACE=="local"){?>
			<li>云端存储</li>
			<?php }?>
			<li onclick="popup($('#askniuren'))">请教大牛</li>
			<li><a href="/" target="_blank">模板商城</a></li>
			<li><a href="/" target="_blank">文档教程</a></li>
			<li onclick="popup($('#aboutus'));">关于我们</li>

			<li class="navitem"  id="logined" style="float:right;<?php if($uid==""){?>display:none;<?php }?>">
				<div>
					<p>
						<a href="/Cloud/<?=$uid?>/?webide=1">我的项目</a>
					</p>
					<p onclick="showwiki()">
						<a>WIKI文档</a>
					</p>
					<p onclick="showjoblistview()">
						<a>我的任务</a>
					</p>
					<p>
						<a>我的bug</a>
					</p>
					<p>
						<a>我的日记</a>
					</p>
					<p>
						<a>我的博客</a>
					</p>
					<p>
						<a>代码片段</a>
					</p>
					<p onclick="logout();">
						<a>退出</a>
					</p>
				</div>
			</li>

			<li class="userinfo" id="unlogin"  onclick="popup($('#login'));" style="<?php if($uid!=""){?>display:none;<?php }?>">登录/注册</li>
		</ul>
	</header>
	<div id="loadding"
		style="position: absolute; z-index: 999999; top: 0; left: 0; background: black; color: #44a6ff; height: 100vh; width: 100%;">
		<div style="text-align: center; margin: 45vh auto;">
			<div style="font-size: 42px; font-weight: bold;">BFW STUDIO</div>
			<div style="color: #3c3c3c; padding-top: 10px;">made develop easier</div>
		</div>

	</div>
	<div id="confictshow" class="popup_dia"
		style="width: 80%; height: 80%;">
		<div class="popup_title">
			<span>文件冲突提醒</span> <span onclick="closeconfictshow()"
				class="popup_close">×</span>
		</div>
		<input type="hidden" id="confictfilepath" name="confictfilepath" />
		<div style="height: 30px; clear: both;">
			<div style="width: 50%; float: left;">云端别人的</div>
			<div style="width: 50%; float: left;">本地我的</div>
		</div>
		<div id="confictnotice" class="scrollbar"
			style="height: 70%; overflow-y: scroll;"></div>
		<p>
			<input type="button" value="使用本地我的版本提交" class="popup_btn"
				onclick="resolvebymev()" />
		</p>
		<p>

			<input type="button" value="使用云端别人版本" class="popup_btn"
				onclick="resolvebyserverv()" style="background: grey;" />
		</p>
	</div>
	<div id="askniuren" class="popup_dia" style="width: 50%; height: 50%;">
		<div class="popup_title">
			<span>请教大牛</span> <span onclick="popclose('askniuren')"
				class="popup_close">×</span>
		</div>

		<p>

			<textarea class="popup_textin" id="askcontent" style="height: 60%;"
				name="askcontent" placeholder="问题描述"></textarea>
		</p>

		<p>
			<input type="button" value="提 交" class="popup_btn" onclick="" />
		</p>
	</div>
	<div id="addtemplate" class="popup_dia"
		style="width: 50%; height: 50%;">
		<div class="popup_title">
			<span>模板提交</span> <span onclick="popclose('addtemplate')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" placeholder="模板名称" class="popup_textin"
				id="templatename" name="templatename" />
		</p>
		<p>

			<textarea class="popup_textin" id="templatedesc" style="height: 30%;"
				name="templatedesc" placeholder="模板简介"></textarea>
		</p>
		<p>
			<input type="text" placeholder="价格，0为免费" class="popup_textin"
				id="templateprice" style="width: 45%;" name="templateprice" />
				<select class="popup_textin"  style="width: 45%;"><option >公开</option><option >私有</option></select>
		</p>
		<p>
			<input type="button" value="提 交" class="popup_btn" onclick="" />
		</p>
	</div>
	<div id="addjobpage" class="popup_dia" style="width: 50%; height: 50%;">
		<div class="popup_title">
			<span>新增任务</span> <span onclick="popclose('addjobpage')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" placeholder="任务名称" class="popup_textin"
				id="jobname" name="jobname" />
		</p>
		<p>

			<textarea class="popup_textin" id="jobcont" style="height: 60%;"
				name="jobcont" placeholder="请输入任务简介"></textarea>
		</p>

		<p>
			<input type="button" value="提 交" class="popup_btn" onclick="addjob()" />
		</p>
	</div>
	<div id="commitlog" class="popup_dia" style="width: 60%; height: 60%;">
		<div class="popup_title">
			<span>日志查看</span> <span onclick="popclose('commitlog')"
				class="popup_close">×</span>
		</div>

		<ul id="commitloglist" class="scrollbar"
			style="height: 70%; overflow-y: scroll;">
			<!-- 			<li><p>2019-11-11 23:30</p> -->
			<!-- 				<p> -->
			<!-- 					系统增加了功纳斯达克发链接 <input type="button" value="回滚到此版本" /> -->
			<!-- 				</p></li> -->

		</ul>
		<p>
			<input type="button" value="更多" class="popup_btn" onclick="" />
		</p>

	</div>
	<div id="appversoncontrol" class="popup_dia"
		style="width: 50%; height: 60%;">
		<div class="popup_title">
			<span>版本控制</span> <span onclick="popclose('appversoncontrol')"
				class="popup_close">×</span>
		</div>
		<input type="hidden" id="appversion_appname" name="appversion_appname" />
		<ul id="appversonlist" class="scrollbar"
			style="height: 70%; overflow-y: scroll;">
			<!-- 			<li><p>2019-11-11 23:30</p> -->
			<!-- 				<p> -->
			<!-- 					系统增加了功纳斯达克发链接 <input type="button" value="回滚到此版本" /> -->
			<!-- 				</p></li> -->

		</ul>
		<p>
			<input type="button" value="提交版本" class="popup_btn"
				onclick="addversion()" />
		</p>

	</div>
	<div id="addwikipage" class="popup_dia"
		style="width: 60%; height: 80%;">
		<div class="popup_title">
			<span>新增wiki文档</span> <span onclick="popclose('addwikipage')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" placeholder="文档名称" class="popup_textin"
				id="wikipagename" name="wikipagename" />
		</p>
		<p>
			<input type="text" placeholder="分类" class="popup_textin"
				id="wikiclassname" name="wikiclassname" />
		</p>
		<p>

			<textarea class="popup_textin" id="wikibodytext" style="height: 65%;"
				name="wikibodytext" placeholder="请输入"></textarea>
		</p>

		<p>
			<input type="button" value="提 交" class="popup_btn"
				onclick="addwikipage()" />
		</p>
	</div>
	<div id="aboutus" class="popup_dia" style="width: 50%; height: 40%;">
		<div class="popup_title">
			<span>关于我们</span> <span onclick="popclose('aboutus')"
				class="popup_close">×</span>
		</div>
		<p style="color: grey;">
			本程序由王小伢兴趣爱好编写，</br> 是全球首款支持webide的开发及运行框架，</br>支持单机，伪集群，集群，SOA部署，</br>支持code
			first，db first，template first开发模式，支持java php net多种流行语言，</br>云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，</br>社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，</br>支持在线模板交易，让优秀的程序员收获自己的财富
		</p>
	</div>
	<div id="rename" class="popup_dia" style="width: 20%; height: 25%;">
		<div class="popup_title">
			<span>重命名</span> <span onclick="popclose('rename')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" class="popup_textin" id="filename" name="filename" />
			<input type="hidden" class="popup_textin" id="parentpathname"
				name="parentpathname" /> <input type="hidden" class="popup_textin"
				id="oldfilename" name="oldfilename" /> <input type="hidden"
				class="popup_textin" id="oldfiletype" name="oldfiletype" />

		</p>

		<p>
			<input type="button" value="确   定" class="popup_btn"
				onclick="rename()" />
		</p>

	</div>

	<div id="previewhtml" class="popup_dia"
		style="width: 30%; height: 20%;">
		<div class="popup_title">
			<span>由于浏览器限制弹窗，请点击查看</span> <span onclick="popclose('previewhtml')"
				class="popup_close">×</span>
		</div>
		<div id="previewbtn" style="text-align: center; color: grey;"></div>

	</div>
	<div id="runaction" class="popup_dia" style="width: 20%; height: 40%;">
		<div class="popup_title">
			<span>请选择执行的动作器</span> <span onclick="popclose('runaction')"
				class="popup_close">×</span>
		</div>
		<div id="actionlist"></div>

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
	<div id="apppower" class="popup_dia" style="width: 50%; height: 40%;">
		<div class="popup_title">
			<span>设置app开发权限</span> <span onclick="popclose('apppower')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="hidden" class="popup_textin" id="apppower_appname"
				name="apppower_appname" placeholder="请输入控制器名称" />
			<textarea class="popup_textin" id="apppower_text"
				style="height: 60%;" name="apppower_text"
				placeholder="请输入开发人员账号，并用|分割"></textarea>
		</p>

		<p>
			<input type="button" value="确 定" class="popup_btn"
				onclick="setapppower()" />
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

	<div id="newprodia" class="popup_dia" style="width: 50%; height: 70%;">
		<div class="popup_title">
			<span>新建项目</span> <span onclick="popclose('newprodia')"
				class="popup_close">×</span>
		</div>
		<p>
			<input type="text" class="popup_textin" id="proname" name="proname"
				placeholder="请输入项目名称,英文首字母大写" style="width: 50%;" />	<input type="text" class="popup_textin"
				placeholder="请选择开发语言" style="width: 30%;" readonly /><select
				style="width: 15%;"  class="popup_textin"><option value="c#">C#</option>
				<option value="java">JAVA</option>
				<option value="php">PHP</option></select>
		</p>


		<p>
		<ul class="temp_ul" id="choose_temp">
			<li style="font-weight:bold;">开发模板</li>
			<li classid="1">我的</li>
			<li classid="1">电商</li>
			<li classid="1">企业</li>
			<li classid="1">后台</li>
			<li classid="1">新闻</li>
			<li classid="1">博客</li>
			<li classid="1">接口</li>
			<li classid="1">其他</li>
			<li style="float:right;"><input style="width:125px;padding:2px;margin:0px;" type="search" placeholder="输入关键词搜索" /></li>
		</ul>
		</p>
		<p>


		<ul class="temp_d_ul scrollbar" id="choose_d_temp" >
			<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
<li tempid="empty"><div class="imgshow"><img style="width:100%;" src="?webide=1&getstatic=/demo.png"/></div><div>电商+平台+抢购</div></li>
		</ul>
		</p>
		<p>

			<input type="button" value="创     建" class="popup_btn"
				onclick="creatpro()" />
		</p>
	</div>
	<div id="wellcomepage">
		<h2 style="color:#cbcbcb;">最近项目</h2>
		<?php if(DEV_PLACE=="local"){?>
		<ul class="nav_tab" id="pro_nav_tab">
			<li class="tab_selected">本地</li>
			<li>云端</li>
		</ul>
		<?php }?>
		<ul class="project" id="latest_pro">
		</ul>
		<ul class="project" id="cloud_pro">
		</ul>
	</div>
	<div id="editorpannel">
		<!-- <div onclick="alert(editor.getValue());">title-关闭</div> -->
		<div id="dir" class="scrollbar">
			<div class="dir_title">
				<span id="php_tree_tab" class="dir_s_tab">动态</span><span
					id="static_tree_tab" class="dir_tab">静态</span> <span
					id="project_name"></span> <span class="close_btn"
					onclick="hideediter();">×</span>
			</div>

			<div id="dong_tree" class="tree"></div>
			<div id="jing_tree" class="tree" style="display: none;"></div>


		</div>
		<div id="file_tab">
			<ul></ul>
		</div>
		<div id="editor" class="scrollbar"></div>

	</div>
	<div id="wikipannel">
		<div style="padding: 5px 15px;">

			<div class="bfwmenu">
				<h2>
					wiki文档<span class="add_btn" onclick="popup($('#addwikipage'))">+</span>
					<span class="close_btn" onclick="hidewiki()">×</span>
				</h2>
				<div class="bfw_content scrollbar">
					<ul id="wikimenu">

					</ul>
				</div>

			</div>
			<div class="bfwbody scrollbar">

				<div class="doc_body">
					<h3>
						文档标题<span class="edit-btn">编辑</span>
					</h3>

					<div>
						<span class="writer">作者</span> <span class="write-time"> 时间</span>
					</div>
					<div id="wikibodydetail">这里显示文档的内容</div>
					<div id="wikiloglist">
						<ul>
							<li>2013-123-123 1232-3 由wangbo提交 <input type="button" value="回退" /></li>
							<li>2013-123-123 1232-3 由wangbo提交 <input type="button" value="回退" /></li>
							<li>2013-123-123 1232-3 由wangbo提交 <input type="button" value="回退" /></li>
							<li>2013-123-123 1232-3 由wangbo提交 <input type="button" value="回退" /></li>
							<li>2013-123-123 1232-3 由wangbo提交 <input type="button" value="回退" /></li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div id="jobpannel">
		<div style="padding: 15px;">

			<div class="bfwmenu">
				<h2>
					任务墙<span class="add_btn" onclick="popup($('#addjobpage'))">+</span>
					<span class="close_btn" onclick="hidejob()">×</span>
				</h2>
				<ul>
					<li>全部</li>
					<li class="selected_li">正在进行中</li>
					<li>已完成</li>
					<li>我的任务</li>
				</ul>
			</div>
			<div class="bfwbody scrollbar ">
				<div class="note">
					<ul class="card" id="joblistview">


					</ul>
				</div>
			</div>
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
	<script src="?webide=1&getstatic=/ace.js" type="text/javascript"
		charset="utf-8"></script>
	<script src="?webide=1&getstatic=/ext-language_tools.js"
		type="text/javascript" charset="utf-8"></script>
	<script src="?webide=1&getstatic=/webide.js" type="text/javascript"
		charset="utf-8"></script>

</body>
</html>