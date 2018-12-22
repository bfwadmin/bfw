<html>
<head lang="zh-CN">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?=DOMIAN_VALUE?>接口文档</title>
<meta name="keywords" content="php开发框架,SOA框架,java开发框架" />
<meta name="description"
	content="全球首款支持webide的开发及运行框架，支持单机，伪集群，集群，SOA部署，支持code first，db first，template first开发模式，支持java php net多种流行语言，云端保存，多处开发，支持企业云部署，一个账号，随时随地打开浏览器即可开发应用，丰富的在线插件及文档，社群问题回答，模板涵盖电商、企业官网、在线教育、企业erp，oa等热门系统，支持在线模板交易，让优秀的程序员收获自己的财富" />
	<link rel="shortcut icon" href="/favicon.ico" />


<body>
	<h1><?=DOMIAN_VALUE?>接口文档</h1>
				<?php foreach($con_act_array as $key=>$val){ ?>
				   <div style='padding: 10px; background: black; color: white;'><?=$key?>
					<?php
        foreach ($val as $item) {
            ?>
						<p style='color: yellow;'>接口地址:<?=str_replace("getapidoc=1", "", URL).DOMIAN_NAME."=".DOMIAN_VALUE."&".CONTROL_NAME."=".CONTROL_VALUE."&".ACTION_NAME."=".$item[0]?>
		
		
		<p>;
		
		
		<p style='color: green; border-bottom: 4px solid grey;'>描述:<?=str_replace("*","<br/>",$item[1])?>
		
		
		<p>;
					
					<?php
        }
        ?>
					
	
	</div>
				<?php } ?>
		</body>
</html>