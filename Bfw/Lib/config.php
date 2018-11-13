<?php
namespace Lib;

use Lib\Bfw;
use Lib\Util\UrlUtil;
// 系统定义常量
define("VERSION", "9.0"); // 版本
define("INSTANCE_NAME", "ART-001"); // 实例名称
define("TIMEZONE", "PRC"); // 時區
define("WEB_DEBUG", true); // 调试模式
define("DEBUG_IP", "::1"); // 可以调试的机器
define("WEB_DEBUG_AJAX", false); // 调试模式
define("ROUTETYPE", 1); // 路由模式 1 get 2 pathinfo
define("HTTP_METHOD", isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '');
define("LANG", "Zh"); // 语言库
define("RUN_MODE", "C"); // 运行模式 S server C client
define("AUTO_CON", false); // 是否自动填充control到数据库
define("SHOW_APIDOC", true); // 是否显示api文档
define("APP_HOST_URL", "http://localhost/"); // APP远程库

define("FILTER_CONT", false); // 是否过滤控制器
                             // 控制 器相关设置
define("CONTROL_NAME", "cont"); // 控制器名称
define("ACTION_NAME", "act"); // 动作器名称
define("DOMIAN_NAME", "dom"); // 域名称
define("APPSELF", isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''); // 当前脚本名称
$_intval = Bfw::GetInitVal(array(
    "Hbapi",
    "Usermoney",
    "AddData"
));
define("CONTROL_VALUE", $_intval[1]); // 控制器传值
define("ACTION_VALUE", $_intval[2]); // 动作器传值
define("DOMIAN_VALUE", $_intval[0]); // 域传值
define("SERVICE_DOMIAN_VALUE", DOMIAN_VALUE); // 域传值
define("SERVICE_HTTP_URL", "http://passport.88art.com/service/"); // 域传值
                                              // url相关参数
define("URL_CASE_SENS", false);
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''); // 来源页
define("IP", UrlUtil::getip()); // 当前ip
define("URL", UrlUtil::geturl()); // 当前url
define("IS_AJAX_REQUEST", isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_SERVER['HTTP_BFWAJAX']) ? true : false); // 判断是否是ajax请求
define("SERVER_NAME", isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ""); // 服务器域名
define("SERVER_PORT", isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ""); // 服務器
                                                                                      // 模板设置
define("TEMPLATE_COMPILE_DIR", APP_ROOT . DS . "Runtime" . DS . "Temp"); // 模板编译路径
define("TEMPLATE_DIR", APP_ROOT . DS . "View" . DS . DOMIAN_VALUE . DS . CONTROL_VALUE); // 模板源文件路劲
define("DEVICE_AUTO_TEMPLATE", false); // 是否开启视图自动识别pc与移动渲染
define("SMARTY_CONFIG_DIR", APP_ROOT . DS . "SmartyConfig"); // smarty 配置文件目录

define("LOG_HANDLER_NAME", "LogFiles"); // log存储方式 logFiles LogServer// 日志设置
define("LOG_DIR", APP_ROOT . DS . "Log" . DS); // 日志记录路径
define("LOG_TO_SERVER", false); // 把日志发送日志服务器
                                
// 缓存设置
                                
// 缓存设置
define("CACHE_HANDLER_NAME", "CacheFiles"); // cache存储方式 FCache与MCache RCache
define("CACHE_DEPENDCY_PRE", "cache_dependcy_"); // 依赖缓存pre
define("CACHE_DEPENDCY_KEY", "2X!2342XDSFSSS"); // 依赖缓存key
define("LOCK_HANDLER_NAME", "LockCache"); // Lock分为CacheLock FLock MemLock
define("LOCK_WAIT_TIME", 1); // 单位秒 一百万微秒=1秒
define("LOCK_TIMEOUT", 5); // 服务锁调用超时时间单位秒
define("CLIENT_TIMEOUT", 10); // 客户消费服务时间单位秒
                              
// 路径设置
define("STATIC_FILE_PATH", "http://localhost/static/"); // 静态资源路径
define("CACHE_DIR", APP_ROOT . DS . 'Cache' . DS); // CACHE路径
define("RUNTIME_DIR", APP_ROOT . DS . 'Runtime' . DS); // RUNTIME_DIR路径 // 獲取連接詞中數據

define("UPLOAD_DIR", "/upload/");
// define("UPLOAD_DIR", DS . 'home' . DS . 'uploadfiles' . DS . 'userpic' . DS);
define("USERIMG_DIR", DS . 'home' . DS . 'uploadfiles' . DS . 'userimg' . DS);
define("AD_DIR", DS . 'home/uploadfiles/adfile/');
define("IMG_EXPIRE_TIME", 3600 * 24 * 3); // img expire time//单位秒
define("WATERMARK_URL", APP_ROOT . DS . 'WaterMark/logo.gif');
define("IMG_FILE_PATH", 'http://localhost'); // 文件附件地址
define("UPLOAD_FILE_PATH", 'http://mupl.88art.com/Cms/Attach/AddData'); // 文件附件地址
define("ACTION_PATH", 'http://action.88art.com'); // 后端处理url
define("WEB_ROOT_PATH", 'http://www.88art.com'); // 网站主页面域名
define("DEFAULT_IMG_URL", STATIC_FILE_PATH . "/statics/images/common/common_avatar.png");
// 秘钥设置
define("SECRIT_KEY", "wW!@#3"); // KEY

define("ROOT_DOMIAN", "88art.com"); // domian
define("CONTENT_IMG_WIDTH", 600); // 内容页图片长度
                                  // 通信相关设置
define("OUTPUT_ERR", false); // 是否向客户端发送php错误
define("RESPONSE_JOSN", false);
define("SERVICE_REMOTE", false); // 是否远程服务
define("RPC_WAY", "RpcJson"); // 通讯协议
define("SERVICE_ALLOW_IP", '192.168.1.1|127.0.0.1');
// 页面缓存设置
// define("PAGE_CACHE_DIR", DS . "home" . DS . "pagecache"); // 页面缓存
define("PAGE_EXPIRE_TIME", 90); // 页面缓存expire time//单位秒
                                // session相关设置
define("SESSION_HANDLE_NAME", "SessionFiles"); // Mysql Redis Memcache Files
define("SESSION_SAVE_PATH", APP_ROOT . DS . "Session"); //
define("SESSION_COOKIE_EXPIRE", 3600 * 10); // 过期时间
define("SESSION_ID_NAME", "ARTID"); // sessionid
define("SESSION_MEMCACHE_IP", "127.0.0.1"); // memcache地址
define("SESSION_MEMCACHE_PORT", 11211); // memcache 端口
define("USER_ID", "uid"); // session用户id
define("ROLE_ID", "kindid"); // session角色id
define("USER_ADDINFO", "uadd"); // session附加信息id

define("ADMINUSER_ID", "adminuid"); // admin session用户id
define("ADMINROLE_ID", "adminkindid"); // admin session角色id

define("SERVICE_REG_CENTER_URL", "http://localhost/boframeworklogserver/index.php?cont=Service&dom=Cms&");
// 数据库配置
define("DB_TYPE", "DbMysql"); // 数据库连接类型
define("TB_PRE", "wb_"); // 表前缀
define("DB_CACHE_ENABLE", true); // 数据二级缓存是否开启
                                 // 代码生成设置
define("GEN_CODE", true); // 运行时从数据库生成代码
define("CODE_TEMP_PATH", 'CodeT'); // 代码模板文件 必须放在app下
                                   // 日期设置

                             
// token验证设置
define("FORM_TOKEN_NAME", "tokenhash"); // form标板token验证名
define("FORM_TOKEN_EXPIRE_TIME", 180); // form token有效期
                                       // 用户定义常量
define("SELLER_DEPOSIT_MONEY", 0.01); // 卖家开店保证金
define("CLASSID", isset($_GET["classid"]) ? $_GET["classid"] : ''); // 栏目匪类
define("TOKEN", "88art2016"); // 微信通信密钥
define("ISCOMMOUNICATION", true); // 论坛、 博客通信按钮
define("ORDER_SN_PRE", "201601250900");
define("CUSTOMER_SERVICE_PHONE", "18616706402");
?>