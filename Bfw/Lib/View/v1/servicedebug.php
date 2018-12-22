<div class="serverinfo">
<p>
	         remote server debug Info [spend {<span style="color: #a6ff0a;"><?=$spendtime?></span>}
	secs,import <span style="color: #039cdb;"><?= count ($import_info ) ?></span> files  <?=\Lib\Bfw::IIF($islogserver, "<span
			style='color: green; font-weight: bold;'>L</span>", "")?> ],[mem:<span
		style="color: #dbaa64;">(<?=$totalmem/1024/1024?>)M</span>]
</p>
		<?php if(!empty($debug_info)){foreach ( $debug_info as $_item ) { ?>
<p>       <?=\Lib\Util\TimeUtil::udate ( "H:i:s.u", $_item [0] ) . ":  " . $_item [1]?>  </p>
		<?php }}?>
		<?php
if (! empty($import_info)) {
    foreach ($import_info as $_item) {
        ?>
<p>          <?=$_item?> </p>
		<?php }}?>
</div>