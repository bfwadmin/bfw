<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>登录</title>
<meta name="description" content="">
<meta name="keywords" content="index">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="icon" type="image/png"
	href="<?=STATIC_FILE_PATH?>assets/i/favicon.png">
<link rel="apple-touch-icon-precomposed"
	href="<?=STATIC_FILE_PATH?>assets/i/app-icon72x72@2x.png">
<meta name="apple-mobile-web-app-title" content="Amaze UI" />
<link rel="stylesheet"
	href="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/css/amazeui.min.css" />
<link rel="stylesheet"
	href="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/css/admin.css">
<link rel="stylesheet"
	href="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/css/app.css">
</head>

<body data-type="login">

	<div class="am-g myapp-login">
		<div class="myapp-login-logo-block  tpl-login-max">
			<div class="myapp-login-logo-text">
				<div class="myapp-login-logo-text">
					<span>登录</span> <i class="am-icon-skyatlas"></i>

				</div>
			</div>

			<!--<div class="login-font">
			<i>Log In </i> or <span> Sign Up</span>
		</div>-->
			<div class="am-u-sm-10 login-am-center">
				<form class="am-form" action="" method="post">
					<fieldset>
						<div class="am-form-group">
							<input type="number" name="username" class=""
								id="doc-ipt-email-1" placeholder="输入手机号码">
						</div>
						<div class="am-form-group">
							<input type="password" name="userpwd" class="" id="doc-ipt-pwd-1"
								placeholder="设置个密码吧">
						</div>
						<p>
							<button type="submit" class="am-btn am-btn-default">登录</button>
						</p>
					</fieldset>
				</form>
			</div>
		</div>
	</div>

	<script
		src="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/js/jquery.min.js"></script>
	<script
		src="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/js/amazeui.min.js"></script>
	<script src="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/assets/js/app.js"></script>
	<script src="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/js/form.js"></script>
	<script src="<?=STATIC_FILE_PATH?>/<?=DOMIAN_VALUE?>/js/ajaxform.js"></script>
</body>

</html>