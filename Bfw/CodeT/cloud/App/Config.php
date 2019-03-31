<?php
$_config_arr['Globle'] = [
    "routetype" => 0,
    "instance_name" => "001",
    "lang" => "Zh",
    "page_suffix" => ".html",
    "defaultdom" => "",
    "defaultact" => "",
    "defaultcont" => "",
    "runmode" => "C",
    "host_runmode" => [
        "bfwclouddeveloper"=>[
            "mode"=>"D",
            "devplace"=>"cloud"//local
        ],
		"a.exm.com"=>[
			"mode"=>"C"
		]
    ],
	"dev_mysql_conf" => [
        "127.0.0.1",
        3306,
        "root",
        "root"
    ]
];
?>