<style>
.debug_pannel {
	margin: 20px 40px;
	border: 1px dashed grey;
	padding: 10px;
}

.debug_pannel ul {
	padding: 0;
	margin: 0;
	list-style: none;
}

.debug_pannel h2 {
	color: #000;
	font-size: 24px;
	font-weight: 700;
}
</style>
<div class="debug_pannel">
	<h2>
		Debug Info [spend {<?=$spendtime?>} secs,import <?= count ($import_info ) ?> files  <?=\Lib\Bfw::IIF($islogserver, "<span
			style='color: green; font-weight: bold;'>L</span>", "")?> ],[mem:<?=(memory_get_usage () / 1024 / 1024) ?>M]
	</h2>
	<ul>
		<?php if(!empty($debug_info)){foreach ( $debug_info as $_item ) { ?>
<li><?=\Lib\Util\TimeUtil::udate ( "Y-m-d H:i:s.u", $_item [0] ) . ":" . $_item [1]?>  </li>
		<?php }}?>
		<?php
if(!empty($import_info)){foreach ($import_info as $_item) {
    ?>
<li><?=$_item?> </li>
		<?php }}?>
</ul>
</div>