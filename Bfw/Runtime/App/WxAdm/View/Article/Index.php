<?php
use Lib\Bfw;

?>
<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<title>BFW帮助文档</title>
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Cache-Control" content="no-siteapp">
<!-- <link rel="alternate icon" type="image/png" -->
<!-- 	href="http://s.amazeui.org/assets/react/i/favicon.png"> -->
<!-- <link rel="apple-touch-icon-precomposed" -->
<!-- 	href="http://s.amazeui.org/assets/react/i/app-icon72x72@2x.png"> -->
<meta name="apple-mobile-web-app-title" content="AMUI Touch">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<style type="text/css">
* {
	padding: 0;
	margin: 0;
}

html {
	font-size: 12px;
}

head {
	height: 10vh;
	width: 100%;
	display: block;
}

#cont {
	height: 90vh;
	width: 100%;
	clear: both;
}

#leftbar {
	float: left;
	height: 100%;
	width: 13%
}

#nav {
	float: right;
	width: 85%;
	height: 100%;
}
</style>
<script type="text/javascript">
function open(url)
{
	document.getElementById("nav").src=url;
}
</script>

</head>
<body>


<head>bfw

</head>
<section id="cont">
	<div id="leftbar">

		<ul class="menu">
    <?php
    foreach ($itemdata as $item) {
        ?>  
           <li class="menu_item">
				<ul class="menu_submenu">
					<li class="menu_subitem"
						onclick="open('<?=Bfw::ACLINK("Article","DetailData","id=".$item['id'])?>');"><?=$item['title']?></li>
				</ul>
			</li>
<?php
    }
    ?>

</ul>
	</div>
	<iframe frameborder="no" border="0" marginwidth="0" marginheight="0"
		scrolling="no" allowtransparency="yes"
		src="<?=Bfw::ACLINK("Article","DetailData","id=45")?>" id="nav"></iframe>
</section>
</body>
</html>