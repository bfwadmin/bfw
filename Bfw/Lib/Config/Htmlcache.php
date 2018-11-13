<?php
// 路由重转向
$_config_arr ['Htmlcache'] ['data'] = [ 
		"/\/art\/([0-9]+)\/([0-9]+)$/" => [ 
				60,
				[ 
						'GET',
						'POST' 
				] 
		] 
];