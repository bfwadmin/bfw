<?php
// 数据库配置
$_config_arr['Db']['masterconfig'] = [
    
    "dbtype" => "DbMysql",
    
    "dbconnstr" => "mysql:host=222.73.245.122;dbname=bfw_center",
    
    "dbport" => 3306,
    
    "dbuser" => "wangbo",
    
    "dbpwd" => "JmbcrMuMYnhSwzfV"
];
$_config_arr['Db']['localconfig'] = [

    "dbtype" => "DbMysql",

    "dbconnstr" => "mysql:host=127.0.0.1;dbname=hongbao",

    "dbport" => 3306,

    "dbuser" => "root",

    "dbpwd" => "root"
];
$_config_arr['Db']['map'] = [
    "Loupan" => "xd_loupan",
    "User"=>"xd_user",
    "Baobei"=>"xd_baobei",
		"Zhuli"=>"xd_zhuli",
		"Mendian"=>"xd_mendian"
];
