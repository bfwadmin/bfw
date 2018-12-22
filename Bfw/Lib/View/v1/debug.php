<style>
#debug_btn {
	font-family: '微软雅黑';
	width: 100px;
	height: 50px;
	padding: 10px;
	opacity: 0.9;
	position: fixed;
	bottom: 10px;
	right: 10px;
	z-index: 100000;
	cursor: pointer;
}
.serverinfo{
	color:#adadad;
}
.serverinfo p{
	padding:0;
	margin:0;
	text-indent:30px;
}
#debug_pannel {
	border: 1px dashed grey;
	padding: 10px;
	position: fixed;
	bottom: 0px;
	width: 100%;
	height: 90%;
	left: 0;
	background: black;
	color: #cecece;
	opacity: 0.9;
	z-index: 99999;
	display: none;
	left: 0;
}

#debug_pannel ul {
	padding: 0;
	margin: 0;
	list-style: none;
	height: 90%;
	overflow: auto;
}

#debug_pannel li {
	padding: 0;
	margin: 0;
	list-style: none;
}

#debug_pannel h1 {
	font-size: 20px;
	font-weight: 700;
	padding: 0;
	margin: 0;
	color: #adadad;
	height: 10%;
}
</style>
<div id="debug_btn"
	onclick="document.getElementById('debug_pannel').style.display='block';">
	<img alt="查看调试信息"
		src="data:image/png;base64,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" />
</div>
<div id="debug_pannel">
	<div style="clear: both;">
		<div
			style="width: 70px; cursor: pointer; text-align: center; float: right; background: #212121;"
			onclick="document.getElementById('debug_pannel').style.display='none';">隐藏</div>
		<h1>
			Debug Info [spend {<span style="color: #a6ff0a;"><?=$spendtime?></span>}
			secs,import <span style="color: #039cdb;"><?= count ($import_info ) ?></span> files  <?=\Lib\Bfw::IIF($islogserver, "<span
			style='color: green; font-weight: bold;'>L</span>", "")?> ],[mem:<span
				style="color: #dbaa64;"><?=($totalmem / 1024 / 1024) ?>M</span>]
		</h1>
	</div>

	<ul>
		<?php if(!empty($debug_info)){foreach ( $debug_info as $_item ) { ?>
<li><?=\Lib\Util\TimeUtil::udate ( "H:i:s.u", $_item [0] ) . ":  " . $_item [1]?>  </li>
		<?php }}?>
		<?php
if (! empty($import_info)) {
    foreach ($import_info as $_item) {
        ?>
<li><?=$_item?> </li>
		<?php }}?>
</ul>
</div>