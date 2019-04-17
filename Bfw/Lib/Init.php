<?php
use Lib\Core;
use Lib\BoRes;
use Lib\WebApp;
use Lib\Registry;
use Lib\BoErrEnum;
use Lib\Util\UrlUtil;
use Lib\BoRoute;
use Lib\BoDebug;
use Lib\BoConfig;
use Lib\Exception\HttpException;
use Lib\Exception\CoreException;
use Lib\Exception\DbException;
use Lib\Exception\ClientException;
use Lib\Exception\CacheException;
use Lib\Exception\FormException;
use Lib\Exception\LockException;
use Lib\Exception\QueueException;
use Lib\Exception\SessionException;
use Lib\Exception\LogException;

if (strtolower(PHP_SAPI) != "cli") {
    ob_start();
}
$_data = "";
$_exception = null;
try {
    spl_autoload_register("autoloadclient", true); // class自动搜寻加载
    set_error_handler("BoErrorHandler", E_ALL);
    register_shutdown_function('err_function');
    set_exception_handler("exception_handler");
    $env = getenv('RUNTIME_ENVIROMENT');
    if ($env) {
        define("RUN_ENV", $env); // apache SetEnv RUNTIME_ENVIROMENT DEV nginx fastcgi_param RUNTIME_ENVIROMENT 'DEV'
    }
    // require_once APP_ROOT . DS . 'Lib' . DS . 'config.php';
    define("HTTP_METHOD", isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '');
    define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''); // 来源页
    define("IP", UrlUtil::getip()); // 当前ip
    define("AYC_CACHE_NAME", "ayscachelist"); // 当前ip
    define("URL", UrlUtil::geturl()); // 当前url
    define("JSON_PRETTY", isset($_SERVER['HTTP_JSONPRETTY']) ? true : false);
    define("IS_AJAX_REQUEST", isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_SERVER['HTTP_BFWAJAX'])||isset($_POST['bfwajax'])||isset($_GET['bfwajax']) ? true : false); // 判断是否是ajax请求
    define("SERVER_NAME", isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ""); // 服务器域名
    define("SERVER_PORT", isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ""); // 服務器
    define("APPSELF", isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''); // 当前脚本名称
    define("HOST_NAME", isset($_SERVER["HTTP_HOST"]) ? $_SERVER['HTTP_HOST'] : ''); // 当前访问主机名
    define("VERSION", "11.0"); // 版本
    define("QUEUE_INTVAL_TIME", 1); // 异步任务等待时间秒
    static $_config_arr = [];
    if (file_exists(APP_ROOT . DS . "App" . DS . "Config.php")) {
        include APP_ROOT . DS . "App" . DS . "Config.php";
    }
    defineinit("DEV_HOST_URL", $_config_arr['Globle'], 'dev_host_url', "http://bfwclouddeveloper/"); // 开发云端地址
    defineinit("STATIC_NAME", $_config_arr['Globle'], 'static_name', "static"); // 运行模式 S server C client M moniter
    defineinit("PAGE_SUFFIX", $_config_arr['Globle'], 'page_suffix', ""); // 后缀 routetype=2可用
    defineinit("SERVICE_M_USER", $_config_arr['Globle'], 'service_m_user', "admin"); // 服务监督中心管理账号
    defineinit("SERVICE_M_PWD", $_config_arr['Globle'], 'service_m_pwd', "admin"); // 服务监督中心管理密码
    defineinit("RESPONSE_JSON", $_config_arr['Globle'], 'response_json', false); // 是否返回json
    defineinit("LANG", $_config_arr['Globle'], 'lang', "Zh"); // 语言库
    defineinit("INSTANCE_NAME", $_config_arr['Globle'], 'instance_name', "001"); // 实例名称
    defineinit("URL_CASE_SENS", $_config_arr['Globle'], 'url_case_sens', false); // 大小写敏感
    defineinit("CONTROL_NAME", $_config_arr['Globle'], 'control_name', "cont"); // 控制器名称
    defineinit("DOMIAN_NAME", $_config_arr['Globle'], 'domian_name', "dom"); // 域名称
    defineinit("ACTION_NAME", $_config_arr['Globle'], 'action_name', "act"); // 动作器名称
    defineinit("ROUTER_NAME", $_config_arr['Globle'], 'router_name', "r"); // 路由参数名称
    defineinit("SUCCESS_PAGE", $_config_arr['Globle'], 'success_page', BFW_LIB."/Lib/View/v1/success.php"); // 成功页面
    defineinit("MSGBOX_PAGE", $_config_arr['Globle'], 'msgbox_page', BFW_LIB."/Lib/View/v1/msgbox.php"); // 提醒页面
    defineinit("ERROR_PAGE", $_config_arr['Globle'], 'error_page', BFW_LIB."/Lib/View/v1/error.php"); // 错误页面

    $_defaultdom = "";
    $_defaultcont = "";
    $_defaultact = "";
    if (isset($_config_arr['Globle']['host_runmode']) && isset($_config_arr['Globle']['host_runmode'][HOST_NAME])) {
        defineinit("RUN_MODE", $_config_arr['Globle']['host_runmode'][HOST_NAME], 'mode', "C"); // 运行模式 S server C client M moniter D developer
        if (RUN_MODE == "D") {
            defineinit("DEV_PLACE", $_config_arr['Globle']['host_runmode'][HOST_NAME], 'devplace', "local"); // 开发地点 云端或本地 local cloud
            defineinit("DEV_DEMO_URL", $_config_arr['Globle']['host_runmode'][HOST_NAME], 'devdemourl', "http://a.exm.com/Cloud/"); // 开发地点 云端或本地 local cloud
            defineinit("DEV_USERDB_DIR", $_config_arr['Globle']['host_runmode'][HOST_NAME], 'devuserdbdir', APP_ROOT.DS."Data".DS); // 云开发用户注册数据库
            defineinit("DEV_USERCOOKIE_NAME", $_config_arr['Globle']['host_runmode'][HOST_NAME], 'devusercookiename', "bfw_id"); // 云开发用户注册数据库
        }
        $_defaultdom = isset($_config_arr['Globle']['host_runmode'][HOST_NAME]['dom']) ? $_config_arr['Globle']['host_runmode'][HOST_NAME]['dom'] : "";
        $_defaultcont = isset($_config_arr['Globle']['host_runmode'][HOST_NAME]['cont']) ? $_config_arr['Globle']['host_runmode'][HOST_NAME]['cont'] : "";
        $_defaultact = isset($_config_arr['Globle']['host_runmode'][HOST_NAME]['act']) ? $_config_arr['Globle']['host_runmode'][HOST_NAME]['act'] : "";
        define("HOST_HIDE_DOM", $_defaultdom);

        if (isset($_config_arr['Globle']['host_runmode'][HOST_NAME]['routetype'])) {
            define("ROUTETYPE", $_config_arr['Globle']['host_runmode'][HOST_NAME]['routetype']); // 路由模式 1 get 2 pathinfo
        } else {
            defineinit("ROUTETYPE", $_config_arr['Globle'], 'routetype', 1); // 路由模式 1 get 2 pathinfo
        }
        if (ROUTETYPE == 2) {
            if (isset($_config_arr['Globle']['host_runmode'][HOST_NAME]['route'])) {
                Registry::getInstance()->set("route_data", $_config_arr['Globle']['host_runmode'][HOST_NAME]['route']);
            }
        }
    } else {
        defineinit("RUN_MODE", $_config_arr['Globle'], 'runmode', "C"); // 运行模式 S server C client M moniter
        $_defaultdom = isset($_config_arr['Globle']['defaultdom']) ? $_config_arr['Globle']['defaultdom'] : "";
        $_defaultcont = isset($_config_arr['Globle']['defaultcont']) ? $_config_arr['Globle']['defaultcont'] : "";
        $_defaultact = isset($_config_arr['Globle']['defaultact']) ? $_config_arr['Globle']['defaultact'] : "";
        define("ROUTETYPE", isset($_config_arr['Globle']['routetype']) ? $_config_arr['Globle']['routetype'] : 1); // 路由模式 1 get 2 pathinfo
    }
    $_intval = [
        $_defaultdom,
        $_defaultcont,
        $_defaultact
    ];
    if (strtolower(PHP_SAPI) === 'cli') {
        // BoSocket::Start();
        global $argv;
        $_intval[0] = isset($argv[1]) ? $argv[1] : $_intval[0];
        $_intval[1] = isset($argv[2]) ? $argv[2] : $_intval[1];
        $_intval[2] = isset($argv[3]) ? $argv[3] : $_intval[2];
    } else {
        if (RUN_MODE == "S") {
            if (! isset($_POST["domianname"])) {
                $_intval[0] = isset($_GET["domianname"]) ? $_GET["domianname"] : $_intval[0];
            } else {
                $_intval[0] = $_POST["domianname"];
            }
            // $_intval[0] = isset($_POST["domianname"]) ? $_POST["domianname"] : $_intval[0];
            $_intval[1] = isset($_POST["servicename"]) ? $_POST["servicename"] : $_intval[1];
            $_intval[2] = isset($_POST["methodname"]) ? $_POST["methodname"] : $_intval[2];
        }
        if (RUN_MODE == "M") {
            if (! isset($_POST["cont"])) {
                $_intval[1] = isset($_GET["cont"]) ? $_GET["cont"] : "service";
            } else {
                $_intval[1] = $_POST["cont"];
            }
            if (! isset($_POST["dom"])) {
                $_intval[0] = isset($_GET["dom"]) ? $_GET["dom"] : "bfw";
            } else {
                $_intval[0] = $_POST["dom"];
            }
            if (! isset($_POST["act"])) {
                $_intval[2] = isset($_GET["act"]) ? $_GET["act"] : "index";
            } else {
                $_intval[2] = $_POST["act"];
            }
        }
        if (RUN_MODE == "C") {
            if (ROUTETYPE == 1) {

                $_intval[0] = isset($_GET[DOMIAN_NAME]) ? $_GET[DOMIAN_NAME] : $_intval[0];
                $_intval[1] = isset($_GET[CONTROL_NAME]) ? $_GET[CONTROL_NAME] : $_intval[1];
                $_intval[2] = isset($_GET[ACTION_NAME]) ? $_GET[ACTION_NAME] : $_intval[2];
            }
            if (ROUTETYPE == 0) {
                $_routerstr = isset($_GET[ROUTER_NAME]) ? $_GET[ROUTER_NAME] : "";
                if ($_routerstr != "") {
                    $_routerarr = explode("|", $_routerstr);
                    $_intval[0] = isset($_routerarr[0]) ? $_routerarr[0] : $_intval[0];
                    $_intval[1] = isset($_routerarr[1]) ? $_routerarr[1] : $_intval[1];
                    $_intval[2] = isset($_routerarr[2]) ? $_routerarr[2] : $_intval[2];
                }
            }
            if (ROUTETYPE == 2) {
                $_patharr = BoRoute::GetParaByUrl();
                $_intval[0] = $_patharr[DOMIAN_NAME] != "" ? $_patharr[DOMIAN_NAME] : $_intval[0];
                $_intval[1] = $_patharr[CONTROL_NAME] != "" ? $_patharr[CONTROL_NAME] : $_intval[1];
                $_intval[2] = $_patharr[ACTION_NAME] != "" ? $_patharr[ACTION_NAME] : $_intval[2];
            }
        }
    }

    defineinit("ALLOW_DOMIAN", $_config_arr['Globle'], 'allow_domian', "*");
    define("CONTROL_VALUE", $_intval[1]); // 控制器传值
    define("ACTION_VALUE", $_intval[2]); // 动作器传值
    define("DOMIAN_VALUE", $_intval[0]); // 域传值
    if (file_exists(APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "Config" . DS . "Config.php")) {
        include APP_ROOT . DS . "App" . DS . DOMIAN_VALUE . DS . "Config" . DS . "Config.php";
    }
    // 系统定义常量
    defineinit("TIMEZONE", $_config_arr['App'], 'timezone', "PRC"); // 時區
    defineinit("WEB_DEBUG", $_config_arr['App'], 'web_debug', false); // 调试模式
    defineinit("DEBUG_IP", $_config_arr['App'], 'debug_ip', "127.0.0.1"); // 可以调试的机器
    defineinit("WEB_DEBUG_AJAX", $_config_arr['App'], 'web_debug_ajax', false); // 调试模式
    defineinit("AUTO_CON", $_config_arr['App'], 'auto_con', false); // 是否自动填充control到数据库
    defineinit("SHOW_APIDOC", $_config_arr['App'], 'show_apidoc', false); // 是否显示api文档
    defineinit("APP_HOST_URL", $_config_arr['App'], 'app_host_url', "http://a.exm.com/"); // APP远程库
    defineinit("PLUGIN_HOST_URL", $_config_arr['App'], 'plugin_host_url', "http://a.exm.com/"); // 插件库url
    defineinit("FILTER_CONT", $_config_arr['App'], 'filter_cont', false); // 是否过滤控制器
                                                                          // 控制 器相关设置
                                                                          // 数据库设置
    defineinit("DB_TYPE", $_config_arr['App'], 'db_type', "Mysql");
    defineinit("DB_HOST", $_config_arr['App'], 'db_host', "127.0.0.1");
    defineinit("DB_NAME", $_config_arr['App'], 'db_name', "pmdb");
    defineinit("DB_PORT", $_config_arr['App'], 'db_port', 3306);
    defineinit("DB_UNAME", $_config_arr['App'], 'db_uname', "root");
    defineinit("DB_PASSWD", $_config_arr['App'], 'db_passwd', "root");
    defineinit("DB_CHARACTER", $_config_arr['App'], 'db_character', "utf8"); // 编码，自动创建数据库时需要
    defineinit("DB_COLLATE", $_config_arr['App'], 'db_collate', "utf8_general_ci"); // 排序方式，自动创建数据库时需要
                                                                                    // define("DB_TYPE", "DbMysql"); // 数据库连接类型
    defineinit("TB_PRE", $_config_arr['App'], 'tb_pre', "wb_"); // 表前缀
                                                                // url相关参数
    defineinit("DEVICE_AUTO_TEMPLATE", $_config_arr['App'], 'service_domian_value', false); // 是否开启视图自动识别pc与移动渲染
                                                                                            // 模板设置
    define("TEMPLATE_COMPILE_DIR", APP_ROOT . DS . "Runtime" . DS . "Temp"); // 模板编译路径
    define("TEMPLATE_DIR", APP_ROOT . DS . "View" . DS . DOMIAN_VALUE . DS . CONTROL_VALUE); // 模板源文件路劲
    define("SMARTY_CONFIG_DIR", APP_ROOT . DS . "SmartyConfig"); // smarty 配置文件目录
                                                                 // Bfw::ConfigSet("Config", "map",array("dd"=>"dddd"));

    // var_dump($_configdata);
                                                                 // die();
    defineinit("SERVICE_DOMIAN_VALUE", $_config_arr['App'], 'service_domian_value', DOMIAN_VALUE); // 域传值
    defineinit("SERVICE_HTTP_URL", $_config_arr['App'], 'service_http_url', "http://s.exm.com/"); // 远程 服务地址
    defineinit("SERVICE_NOTIFY_URL", $_config_arr['App'], 'service_notify_url', "http://c.exm.com/"); // 服务通知回调地址
    defineinit("SERVICE_REG_CENTER_URL", $_config_arr['App'], 'service_reg_center_url', "http://m.exm.com/"); // 服务监督中心地址
    defineinit("LOG_HANDLER_NAME", $_config_arr['App'], 'log_handler_name', "LogFiles"); // log存储方式 logFiles LogServer// 日志设置
    defineinit("LOG_DIR", $_config_arr['App'], 'log_dir', APP_ROOT . DS . "Log" . DS); // 日志记录路径
    defineinit("LOG_TO_SERVER", $_config_arr['App'], 'log_to_server', false); // 把日志发送日志服务器
    defineinit("DATA_DIR", $_config_arr['App'], 'data_dir', APP_ROOT . DS . "Data" . DS); // runtime记录路径
    defineinit("PLUGIN_DIR", $_config_arr['App'], 'plugin_dir', BFW_LIB . DS . "Plugin" . DS); // 插件路径
    defineinit("STATIC_DIR", $_config_arr['App'], 'static_dir', "static"); // 静态文件夹名称
                                                                           // 代码生成设置
    defineinit("GEN_CODE", $_config_arr['App'], 'gen_code', false); // 运行时从数据库生成代码
    defineinit("CODE_TEMP_PATH", $_config_arr['App'], 'code_temp_path', 'CodeT'); // 代码模板文件 必须放在app下

    defineinit("QUEUE_HANDLER_NAME", $_config_arr['App'], 'queue_handler_name', "QueueRedis"); // cache存储方式 FCache与MCache RCache
    if (QUEUE_HANDLER_NAME == "QueueRedis") {
        defineinit("QUEUE_REDIS_HOST", $_config_arr['App'], 'queue_redis_host', "127.0.0.1"); // 当handelr设为redis启用
        defineinit("QUEUE_REDIS_PORT", $_config_arr['App'], 'queue_redis_port', 6379); //
        defineinit("QUEUE_REDIS_PCONNECT", $_config_arr['App'], 'queue_redis_pconnect', true); //
        defineinit("QUEUE_REDIS_TIMEOUT", $_config_arr['App'], 'queue_redis_timeout', 5); //
        defineinit("QUEUE_REDIS_AUTHKEY", $_config_arr['App'], 'queue_redis_authkey', ""); //
    }
    if (QUEUE_HANDLER_NAME == "QueueRabbitMq") {
        defineinit("QUEUE_RABBIT_HOST", $_config_arr['App'], 'queue_rabbit_host', "127.0.0.1"); // 当handelr设为redis启用
        defineinit("QUEUE_RABBIT_PORT", $_config_arr['App'], 'queue_rabbit_port', 5672); //
        defineinit("QUEUE_RABBIT_VHOST", $_config_arr['App'], 'queue_rabbit_vhost', "/"); //
        defineinit("QUEUE_RABBIT_USER", $_config_arr['App'], 'queue_rabbit_user', "guest"); //
        defineinit("QUEUE_RABBIT_PWD", $_config_arr['App'], 'queue_rabbit_pwd', "guest"); //
    }
    if (QUEUE_HANDLER_NAME == "QueueActiveMq") {
        defineinit("QUEUE_AQ_HOST", $_config_arr['App'], 'queue_aq_host', "127.0.0.1"); // 当handelr设为redis启用
        defineinit("QUEUE_AQ_PORT", $_config_arr['App'], 'queue_aq_port', 61613); //
    }
    // 缓存设置
    defineinit("CACHE_HANDLER_NAME", $_config_arr['App'], 'cache_handler_name', "CacheFiles"); // cache存储方式 FCache与MCache RCache
    if (CACHE_HANDLER_NAME == "CacheRedis") {
        defineinit("CACHE_REDIS_HOST", $_config_arr['App'], 'cache_redis_host', "127.0.0.1"); // 当handelr设为redis启用
        defineinit("CACHE_REDIS_PORT", $_config_arr['App'], 'cache_redis_port', 6379); //
        defineinit("CACHE_REDIS_PCONNECT", $_config_arr['App'], 'cache_redis_pconnect', true); //
        defineinit("CACHE_REDIS_TIMEOUT", $_config_arr['App'], 'cache_redis_timeout', 5); //
        defineinit("CACHE_REDIS_AUTHKEY", $_config_arr['App'], 'cache_redis_authkey', ""); //
    }
    if (CACHE_HANDLER_NAME == "CacheMemcache") {
        defineinit("CACHE_MEM_HOST", $_config_arr['App'], 'cache_mem_host', "127.0.0.1"); // 当handelr设为redis启用
        defineinit("CACHE_MEM_PORT", $_config_arr['App'], 'cache_mem_port', 11211); //
        defineinit("CACHE_MEM_PCONNECT", $_config_arr['App'], 'cache_mem_pconnect', true); //
        defineinit("CACHE_MEM_TIMEOUT", $_config_arr['App'], 'cache_mem_timeout', 5); //
        defineinit("CACHE_MEM_COMPRESS", $_config_arr['App'], 'cache_mem_compress', false); //
    }
    if (CACHE_HANDLER_NAME == "CacheZookeeper") {
        defineinit("CACHE_ZK_IP", $_config_arr['App'], 'cache_zk_ip', "127.0.0.1");
        defineinit("CACHE_ZK_PORT", $_config_arr['App'], 'cache_zk_ip', 2181);
    }
    define("CACHE_UPDATE_INTVAL_TIME", 15);

    defineinit("CACHE_DEPENDCY_PRE", $_config_arr['App'], 'cache_dependcy_pre', "cache_dependcy_"); // 依赖缓存pre
    defineinit("CACHE_DEPENDCY_KEY", $_config_arr['App'], 'cache_dependcy_key', " 2X!2342XDSFSSS"); // 依赖缓存key
    defineinit("LOCK_HANDLER_NAME", $_config_arr['App'], 'lock_handler_name', "LockCache"); // Lock分为CacheLock FLock MemLock

    if (LOCK_HANDLER_NAME == "LockRedis") {
        defineinit("LOCK_REDIS_IP", $_config_arr['App'], 'lock_redis_ip', "127.0.0.1");
        defineinit("LOCK_REDIS_PORT", $_config_arr['App'], 'lock_redis_ip', 6379);
        defineinit("LOCK_REDIS_PCONNECT", $_config_arr['App'], 'lock_redis_pconnect', true); //
        defineinit("LOCK_REDIS_TIMEOUT", $_config_arr['App'], 'lock_redis_timeout', 5); //
        defineinit("LOCK_REDIS_AUTHKEY", $_config_arr['App'], 'lock_redis_authkey', ""); //
    }
    if (LOCK_HANDLER_NAME == "LockZookeeper") {
        defineinit("LOCK_ZK_IP", $_config_arr['App'], 'lock_zk_ip', "127.0.0.1");
        defineinit("LOCK_ZK_PORT", $_config_arr['App'], 'lock_zk_ip', 2181);
    }
    if (LOCK_HANDLER_NAME == "LockMemcache") {
        defineinit("LOCK_MEM_IP", $_config_arr['App'], 'lock_mem_ip', "127.0.0.1");
        defineinit("LOCK_MEM_PORT", $_config_arr['App'], 'lock_mem_ip', 11211);
        defineinit("LOCK_MEMS_PCONNECT", $_config_arr['App'], 'lock_mem_pconnect', true); //
        defineinit("LOCK_MEM_TIMEOUT", $_config_arr['App'], 'lock_mem_timeout', 5); //
    }

    defineinit("LOCK_WAIT_TIME", $_config_arr['App'], 'lock_wait_time', 1); // 单位秒 一百万微秒=1秒
    defineinit("LOCK_TIMEOUT", $_config_arr['App'], 'lock_timeout', 2); // 服务锁调用超时时间单位秒
    defineinit("CLIENT_TIMEOUT", $_config_arr['App'], 'client_timeout', 10); // 客户消费服务时间单位秒
                                                                             // 路径设置
    defineinit("STATIC_FILE_PATH", $_config_arr['App'], 'static_file_path', "/static/"); // 静态资源路径
    defineinit("CACHE_DIR", $_config_arr['App'], 'cache_dir', BFW_LIB . DS . 'Cache' . DS); // CACHE路径
    defineinit("RUNTIME_DIR", $_config_arr['App'], 'runtime_dir', APP_ROOT . DS . 'Runtime' . DS); // RUNTIME_DIR路径 // 獲取連接詞中數據
    defineinit("UPLOAD_DIR", $_config_arr['App'], 'upload_dir', "/upload/");
    // define("UPLOAD_DIR", DS . 'home' . DS . 'uploadfiles' . DS . 'userpic' . DS);
    defineinit("USERIMG_DIR", $_config_arr['App'], 'userimg_dir', DS . 'home' . DS . 'uploadfiles' . DS . 'userimg' . DS);
    defineinit("AD_DIR", $_config_arr['App'], 'ad_dir', DS . 'home/uploadfiles/adfile/');
    defineinit("IMG_EXPIRE_TIME", $_config_arr['App'], 'img_expire_time', 3600 * 24 * 3); // img expire time//单位秒
    defineinit("WATERMARK_URL", $_config_arr['App'], 'watermark_url', APP_ROOT . DS . 'WaterMark/logo.gif');
    defineinit("FILES_FILE_PATH", $_config_arr['App'], 'files_file_path', 'http://localhost'); // 文件附件地址
    defineinit("IMG_FILE_PATH", $_config_arr['App'], 'img_file_path', 'http://localhost'); // 文件附件地址
    defineinit("UPLOAD_PATH", $_config_arr['App'], "upload_path", 'http://mupl.com/Cms/Attach/AddData'); // 文件附件地址
    defineinit("ACTION_PATH", $_config_arr['App'], "action_path", 'http://action.com'); // 后端处理url
    defineinit("WEB_ROOT_PATH", $_config_arr['App'], 'web_root_path', 'http://www.88art.com'); // 网站主页面域名
    defineinit("DEFAULT_IMG_URL", $_config_arr['App'], 'default_img_url', STATIC_FILE_PATH . "/statics/images/common/common_avatar.png");
    // 秘钥设置
    defineinit("SECRIT_KEY", $_config_arr['App'], 'secrit_key', "wW!@#"); // KEY

    defineinit("ROOT_DOMIAN", $_config_arr['App'], 'root_domian', "local.com"); // domian
    defineinit("CONTENT_IMG_WIDTH", $_config_arr['App'], 'content_img_width', 600); // 内容页图片长度
                                                                                    // 通信相关设置
    defineinit("OUTPUT_ERR", $_config_arr['App'], 'output_err', true); // 是否向客户端发送php错误
                                                                       // define("RESPONSE_JOSN", isset($_config_arr['App']['response_json'])?$_config_arr['App']['output_err']:false);
    defineinit("SERVICE_REMOTE", $_config_arr['App'], 'service_remote', false); // 是否远程服务
    defineinit("RPC_WAY", $_config_arr['App'], 'rpc_way', "RpcJson"); // 通讯协议
    defineinit("SERVICE_ALLOW_IP", $_config_arr['App'], 'service_allow_ip', '::1|127.0.0.1');
    // 页面缓存设置
    // define("PAGE_CACHE_DIR", DS . "home" . DS . "pagecache"); // 页面缓存
    defineinit("PAGE_EXPIRE_TIME", $_config_arr['App'], 'page_expire_time', 90); // 页面缓存expire time//单位秒
                                                                                 // session相关设置
    defineinit("SESSION_HANDLER_NAME", $_config_arr['App'], 'session_handler_name', "SessionFiles"); // Mysql Redis Memcache Files
    defineinit("SESSION_SAVE_PATH", $_config_arr['App'], 'session_save_path', APP_ROOT . DS . "Session"); //
    if (SESSION_HANDLER_NAME == "SessionRedis") {
        defineinit("SESSION_REDIS_IP", $_config_arr['App'], 'session_redis_ip', "127.0.0.1"); // redis地址
        defineinit("SESSION_REDIS_PORT", $_config_arr['App'], 'session_redis_port', 6379); // redis地址
        defineinit("SESSION_REDIS_AUTHKEY", $_config_arr['App'], 'session_redis_authkey', ""); // redis地址
    }
    if (SESSION_HANDLER_NAME == "SessionMemcache") {
        defineinit("SESSION_MEMCACHE_IP", $_config_arr['App'], 'session_memcache_ip', "127.0.0.1"); // memcache地址
        defineinit("SESSION_MEMCACHE_PORT", $_config_arr['App'], 'session_memcache_port', 11211); // memcache 端口
        defineinit("SESSION_MEMCACHE_COMPRESS", $_config_arr['App'], 'session_memcache_compress', false); // memcache 端口
    }
    if (SESSION_HANDLER_NAME == "SessionMysql") {
        defineinit("SESSION_MYSQL_IP", $_config_arr['App'], 'session_mysql_ip', "127.0.0.1"); // memcache地址
        defineinit("SESSION_MYSQL_PORT", $_config_arr['App'], 'session_mysql_port', 3306); // memcache 端口
        defineinit("SESSION_MYSQL_DB", $_config_arr['App'], 'session_mysql_db', ""); // memcache 端口
        defineinit("SESSION_MYSQL_USER", $_config_arr['App'], 'session_mysql_user', "root"); // memcache 端口
        defineinit("SESSION_MYSQL_PWD", $_config_arr['App'], 'session_mysql_pwd', "root"); // memcache 端口
        defineinit("SESSION_MYSQL_TB", $_config_arr['App'], 'session_mysql_tb', "bfw_sessions"); // memcache 端口
    }
    defineinit("SESSION_COOKIE_DOMIAN", $_config_arr['App'], 'session_cookie_domian', HOST_NAME); // session cookie域
    defineinit("SESSION_COOKIE_EXPIRE", $_config_arr['App'], 'session_cookie_expire', 3600 * 10); // 过期时间
    defineinit("SESSION_ID_NAME", $_config_arr['App'], 'session_id_name', "BFWID"); // sessionid

    defineinit("USER_ID", $_config_arr['App'], 'user_id', "uid"); // session用户id
    defineinit("ROLE_ID", $_config_arr['App'], 'role_id', "kindid"); // session角色id
                                                                     // define("USER_ADDINFO", "uadd"); // session附加信息id
    defineinit("ADMINUSER_ID", $_config_arr['App'], 'adminuser_id', "adminuid"); // admin session用户id
    defineinit("ADMINROLE_ID", $_config_arr['App'], 'adminrole_id', "adminkindid"); // admin session角色id

    defineinit("APP_NAME", $_config_arr['App'], 'app_name', "BFW"); // admin session角色id
                                                                    // token验证设置
    defineinit("FORM_TOKEN_NAME", $_config_arr['App'], 'form_token_name', "tokenhash"); // form标板token验证名
    defineinit("FORM_TOKEN_EXPIRE_TIME", $_config_arr['App'], 'form_token_expire_time', 180); // form token有效期
    date_default_timezone_set(TIMEZONE);
    define("SYS_TIME", date('Y-m-d H:i:s')); // 系统当前时间
    define("UNIX_TIME", time()); // 当前时间戳，整形
    if (in_array(RUN_MODE, [
        'C',
        'M',
    ]) && strtolower(PHP_SAPI) != "cli") {
       // ini_set('session.gc_maxlifetime', SESSION_COOKIE_EXPIRE);
       // ini_set('session.gc_probability', 0);
       // ini_set('session.gc_divisor', 100);
        session_name(SESSION_ID_NAME);
        session_set_cookie_params(SESSION_COOKIE_EXPIRE, "/", SESSION_COOKIE_DOMIAN, false, true);
        $_sessionpath = "Lib\\Session\\" . SESSION_HANDLER_NAME . "::";
        session_set_save_handler($_sessionpath . "sess_open", $_sessionpath . "sess_close", $_sessionpath . "sess_read", $_sessionpath . "sess_write", $_sessionpath . "sess_destroy", $_sessionpath . "sess_gc");
        if (isset($_POST[SESSION_ID_NAME]) && $_POST[SESSION_ID_NAME] != "") {
            session_id($_POST[SESSION_ID_NAME]);
        }
        if (isset($_GET[SESSION_ID_NAME]) && $_GET[SESSION_ID_NAME] != "") {
            session_id($_GET[SESSION_ID_NAME]);
        }
        session_start();
        define("SESS_ID", session_id()); // 用户Sessionid
        session_write_close();
    }
    $_data = WebApp::getInstance()->Execute(CONTROL_VALUE, ACTION_VALUE, DOMIAN_VALUE);
} catch (LogException $ex) {
    $_exception = $ex->getException();
} catch (SessionException $ex) {
    $_exception = $ex->getException();
} catch (LockException $ex) {
    $_exception = $ex->getException();
} catch (QueueException $ex) {
    $_exception = $ex->getException();
} catch (HttpException $ex) {
    $_exception = $ex->getException();
} catch (CoreException $ex) {
    $_exception = $ex->getException();
} catch (FormException $ex) {
    $_exception = $ex->getException();
} catch (ClientException $ex) {
    $_exception = $ex->getException();
} catch (CacheException $ex) {
    $_exception = $ex->getException();
} catch (DbException $ex) {
    $_exception = $ex->getException();
} catch (\Exception $ex) {
    $_exception = [
        'errmsg' => $ex->getMessage(),
        "errline" => $ex->getLine(),
        "errfile" => $ex->getFile(),
        "trace" => $ex->getTraceAsString(),
        "type" => "com"
    ];
}
if (RUN_MODE == "S") {
    if (WEB_DEBUG) {
        if (is_array($_exception)) {
            $_data['bo_err'] = true;
            $_data['bo_data'] = $_exception['errmsg'];
        }
    }
    echo Core::LoadClass('Lib\\RPC\\' . RPC_WAY)->pack($_data);
} else {

    if (RUN_MODE == "C") {
        if (strtolower(PHP_SAPI) != "cli") {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                if (is_array($_exception)) {
                    BoRes::View("deverror", "System", "v1", [
                        'errmsg' => $_exception['errmsg'],
                        "errline" => $_exception['errline'],
                        "errfile" => $_exception['errfile'],
                        "trace" => $_exception['trace']
                    ]);
                }
                $total = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; // 计算差值
                if (IS_AJAX_REQUEST) {
                    if (WEB_DEBUG_AJAX) {
                        BoDebug::DebugEcho($total, true);
                    }
                } else {
                    BoDebug::DebugEcho($total);
                }
            } else {
                if (is_array($_exception)) {
                    BoDebug::LogR($_exception['errmsg'] . "行:" . $_exception['errline'] . "文件:" . $_exception['errfile'] . "跟踪:" . $_exception['trace'], 'INIT_ERR', BoErrEnum::BFW_WARN);
                    BoRes::View("error", "System", "v1", [
                        'but_msg' => $_exception['type'] . "_ERR"
                    ]);
                }
            }
        }
    }
}

if (strtolower(PHP_SAPI) != "cli") {
    ob_end_flush();
}
// 发送异步消息
// $_cachedata=&Registry::getInstance()->get("cache_list_forsend");
// if(is_array($_cachedata)&&!empty($_cachedata)){
// BoQueue::Enqueue("cache_list", $_cachedata);
// }
function defineinit($_name, &$_conf, $_val, $_defval = "")
{
    return define($_name, isset($_conf[$_val]) ? $_conf[$_val] : $_defval);
}

function autoloadclient($class)
{
    $_classname = str_replace("\\", DS, str_replace(".", DS, $class));
    if (substr($_classname, 0, 3) == "Lib") {
        $classpath = BFW_LIB . DS . $_classname . ".php";
    } else {
        $classpath = APP_ROOT . DS . $_classname . ".php";
    }
    if (file_exists($classpath)) {
        include_once $classpath;
        if (defined("WEB_DEBUG")) {
            if (WEB_DEBUG) {
                require_once 'Registry.php';
                $_import_info = &Registry::getInstance()->get("import_info");
                $_import_info[] = "import file:" . $classpath . ",mem:" . (memory_get_usage() / 1024) . "KB";
                Registry::getInstance()->set("import_info", $_import_info);
            }
        }
    } else {

        if (substr($_classname, 0, strpos($_classname, DS)) == "Plugin") {
            // 下载插件
            // echo "download";
            // return autoloadclient($class);
        }
        throw new Exception(BoConfig::Config("Sys", "webapp", "System")['class_not_found'] . $class);
    }
}

function exception_handler($_exception)
{
    if (strtolower(PHP_SAPI) != "cli") {
        if (WEB_DEBUG && DEBUG_IP == IP) {
            BoRes::View("deverror", "System", "v1", [
                'errmsg' => $_exception['errmsg'],
                "errline" => $_exception['errline'],
                "errfile" => $_exception['errfile'],
                "trace" => $_exception['trace']
            ]);
        } else {
            BoDebug::LogR("PHP Warning: {$_exception->getMessage()}", "php", BoErrEnum::BFW_ERROR);
        }
    } else {
        print $_exception;
    }
}

function err_function()
{
    $_e = error_get_last();
    if ($_e) {
        if (strtolower(PHP_SAPI) != "cli") {
            if (WEB_DEBUG && DEBUG_IP == IP) {
                BoRes::View("deverror", "System", "v1", [
                    'errmsg' => $_e['message'],
                    "errline" => $_e['line'],
                    "errfile" => $_e['file'],
                    "trace" => $_e['type']
                ]);
            } else {
                BoDebug::LogR("PHP Warning: {$_e['message']}, {$_e['message']}, {$_e['message']}", "php", BoErrEnum::BFW_ERROR);
            }
        } else {
            print_r($_e);
        }
    }
}

function BoErrorHandler($errno, $errstr, $errfile, $errline)
{
    // die($errstr.$errno.$errfile.$errline);
    if (WEB_DEBUG && DEBUG_IP == IP) {
        BoDebug::Info("err:" . $errstr . ",file:" . $errfile . ",line:" . $errline);
    }
    if (E_WARNING === $errno) {
        BoDebug::LogR("PHP Warning: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_WARN);
        return true;
    }
    if (E_ERROR === $errno) {
        BoDebug::LogR("PHP Err: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_ERROR);
        return true;
    }
    if (E_NOTICE === $errno) {
        BoDebug::LogR("PHP Notic: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_INFO);
        return true;
    } else {
        BoDebug::LogR("Err: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_INFO);
        return true;
    }
    return false;
}
?>