<html>
	<title><?=DOMIAN_VALUE?>接口文档</title>
	<body>
<h1><?=DOMIAN_VALUE?>接口文档</h1>
				<?php foreach($con_act_array as $key=>$val){ ?>
				   <div style='padding:10px;background:black;color:white;'><?=$key?>
					<?php
					foreach($val as $item){ ?>
						<p style='color:yellow;'>接口地址:<?=str_replace("getapidoc=1", "", URL).DOMIAN_NAME."=".DOMIAN_VALUE."&".CONTROL_NAME."=".CONTROL_VALUE."&".ACTION_NAME."=".$item[0]?><p>;
						<p style='color:green;border-bottom:4px solid grey;'>描述:<?=str_replace("*","<br/>",$item[1])?><p>;
					
					<?php
					}
					?>
					</div>
				<?php } ?>
		</body>		
				</html>