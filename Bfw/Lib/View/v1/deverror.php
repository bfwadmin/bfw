<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<title>错误提示</title>
	<style type="text/css">
* {
	padding: 0;
	margin: 0;
}

html {
	overflow:auto;
}

body {
	background: black;
	font-family: '微软雅黑';
	color: #c2c2c2;
	font-size: 16px;
}

img {
	border: 0;
}

.err_pannel {
	padding: 24px 48px;
}

.tips {
	font-size: 100px;
	font-weight: normal;
	line-height: 120px;
	margin-bottom: 12px;
}

h1 {
	font-size: 32px;
	line-height: 48px;
}

.err_pannel .content {
	padding-top: 10px
}

.err_pannel .info {
	margin-bottom: 12px;
}

.err_pannel .info .title {
	margin-bottom: 3px;
}

.err_pannel .info .title h3 {
	color: #000;
	font-weight: 700;
	font-size: 16px;
}

.err_pannel .info .text {
	line-height: 24px;
}

.copyright {
	padding: 12px 48px;
	color: #999;
}

.copyright a {
	color: #000;
	text-decoration: none;
}
</style>

</head>
<body>
	<div class="err_pannel">
		<div class="tips">Oops</div>
		<h1><?=$errmsg?></h1>
		<div class="content">
			<div class="info">
				<div class="title">
					<h3>错误位置</h3>
				</div>
				<div class="text">
					<p>FILE:<?=$errfile?> &#12288;LINE: <?=$errline?></p>
				</div>
			</div>
			<div class="info">
				<div class="title">
					<h3>TRACE</h3>
				</div>
				<div class="text">
					<p><?=str_replace("\n", "</br>", $trace)?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="copyright">
		<p>
			BFW</a><sup><?=VERSION?></sup>[SOA framework]
		</p>
	</div>
</body>
</html>
