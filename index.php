<?php
// +----------------------------------------------------------------------
// | OPO [ SOA ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://bfw.life All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: wangbo <opo@gmail.com>
// +----------------------------------------------------------------------
// [ 应用入口文件 ]
if (version_compare ( "5.4", PHP_VERSION, ">" )) {
	die ( "PHP 5.4 or greater is required!!!" );
}
define ( "APP_BASE", __DIR__ );
define ( "DS", DIRECTORY_SEPARATOR );
define ( "APP_ROOT", substr(APP_BASE, 0,strripos(APP_BASE,DS)). DS . "Bfw" );
define ( "APP_DIR", APP_ROOT. DS . "App" .DS);
require_once APP_ROOT . DS . 'Lib' . DS . 'Init.php';