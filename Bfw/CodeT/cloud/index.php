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
if (version_compare("5.4", PHP_VERSION, ">")) {
    die("PHP 5.4 or greater is required!!!");
}
define("APP_BASE", __DIR__);
define("DS", DIRECTORY_SEPARATOR);
define("APP_ROOT", APP_BASE);
define("BFW_LIB", "../../../Bfw");
require_once BFW_LIB . DS . 'Lib' . DS . 'Init.php';