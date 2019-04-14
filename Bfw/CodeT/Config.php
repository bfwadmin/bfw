<?php
$_config_arr['App'] = [
    "web_debug"=>true,
    "service_remote"=>false,
    "dbmap"=>[],
    "tb_pre"=>"bfw_",
	"session_save_path"=>APP_BASE.DS."Session".DS,
	"log_dir"=>APP_BASE.DS."Log".DS,
	"data_dir"=>APP_BASE.DS."Data".DS,
	"static_dir"=>"static",
	"cache_dir"=>APP_BASE.DS."Cache".DS,
    "plugin_dir"=>BFW_LIB.DS."Plugin".DS,
    "runtime_dir"=>BFW_LIB.DS."Runtime".DS,
];

