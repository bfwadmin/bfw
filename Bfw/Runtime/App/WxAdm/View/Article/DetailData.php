<?php
use Lib\Bfw;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$itemdata['title']?></title>
<link href="/statics/js/xheditor/prettify/prettify.css" rel="stylesheet"
	type="text/css" />
<script type="text/javascript"
	src="/statics/js/xheditor/prettify/prettify.js"></script>
<style>
body {
	font-size: 14px;
	padding: 0;
	margin: 0;
}

* {
	padding: 0;
	margin: 0;
}

.containter {
	height: 80%;
	width: 100%;
	clear:both;
}

.navbar {
	width: 15%;
	height: 100%;
	float:left;
	padding:10px;
}

.content {
	width: 80%;
	height: 100%;
	float:right;
	padding:10px;
}

h1 {
	text-align: center;
}

.content p {
	line-height: 26px;
}

pre.prettyprint {
	border-left: 2px dashed #888;
	background: #FFFFE0;
	margin-left: 20px;
}

footer {
	text-align: center;
	clear:both;
}

footer span {
	font-size: 14px;
	font-weight: bold;
}
.menu,.menu_submenu{
		padding: 0;
	margin: 0;
	list-style:none;
}
.menu_item{
	padding-left:5px;
}
.menu_subitem{
	padding-left:10px;
}
header{
	background:#414141;
	height:60px;
	color:#b0b0b0;
}
</style>

<script>
 window.onload = function ()
{
    prettyPrint();
}
</script>
</head>

<body>
	<header>
	<a >logo</a>
	<ul>
	<li>首页</li>
	<li>首页</li>
	<li>首页</li>
	<li>首页</li>
	<li>首页</li>
	</ul>
	</header>
	<section class="containter"> 
	<section class="navbar">

	<ul class="menu">
    <?php
    foreach ($menudata as $item) {
        ?>  
           <li class="menu_item">
			<ul class="menu_submenu">
				<li class="menu_subitem"><a
					href="<?=Bfw::ACLINK("Article","DetailData","id=".$item['id'])?>"><?=$item['title']?></a></li>
			</ul>
		</li>
<?php
    }
    ?>

</ul>
	</section> 
	<section class="content">
	<h1><?=$itemdata['title']?></h1>
	<div>
	<?=$itemdata['content']?>
	</div>
	</section>
	 </section>
	<footer> <span><?=isset($beforeid)?"<a href='".Bfw::ACLINK("Article","DetailData","id=".$beforeid)."'>上一篇</a>":""?></span>
	<span><?=isset($nextid)?"<a href='".Bfw::ACLINK("Article","DetailData","id=".$nextid)."'>下一篇</a>":""?></span>
	</footer>
</body>
</html>