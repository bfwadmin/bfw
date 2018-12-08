<?php
use Lib\Bfw;
use Lib\Core;
use Lib\WebApp;
use Lib\Registry;
use Lib\BoErrEnum;
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
    require_once APP_ROOT . DS . 'Lib' . DS . 'config.php';
    date_default_timezone_set(TIMEZONE);
    define("SYS_TIME", date('Y-m-d H:i:s')); // 系统当前时间
    define("UNIX_TIME", time()); // 当前时间戳，整形
    if (RUN_MODE == "C" && strtolower(PHP_SAPI) != "cli") {
        ini_set('session.gc_maxlifetime', SESSION_COOKIE_EXPIRE);
        ini_set('session.gc_probability', 0);
        ini_set('session.gc_divisor', 100);
        session_name(SESSION_ID_NAME);
        session_set_cookie_params(SESSION_COOKIE_EXPIRE, "/", null, false, true);
        $_sessionpath = "Lib\\Session\\" . SESSION_HANDLE_NAME . "::";
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

if (RUN_MODE === "S") {
    if (WEB_DEBUG) {
        if (is_array($_exception)) {
            $_data['bo_err'] = true;
            $_data['bo_data'] = $_exception['errmsg'];
        }
    }
    echo Core::LoadClass('Lib\\RPC\\' . RPC_WAY)->pack($_data);
} else {
    if (strtolower(PHP_SAPI) != "cli") {
        if (WEB_DEBUG && DEBUG_IP == IP) {
            if (is_array($_exception)) {
                Core::V("deverror", "System", "v1", [
                    'errmsg' => $_exception['errmsg'],
                    "errline" => $_exception['errline'],
                    "errfile" => $_exception['errfile'],
                    "trace" => $_exception['trace']
                ]);
            }
            $total = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; // 计算差值
            if (IS_AJAX_REQUEST) {
                if (WEB_DEBUG_AJAX) {
                    Bfw::DebugEcho($total);
                }
            } else {
                Bfw::DebugEcho($total);
            }
        } else {
            if (is_array($_exception)) {
                Bfw::LogR($_exception['errmsg'] . "行:" . $_exception['errline'] . "文件:" . $_exception['errfile'] . "跟踪:" . $_exception['trace'], 'INIT_ERR', BoErrEnum::BFW_WARN);
                Core::V("error", "System", "v1", [
                    'but_msg' => $_exception['type'] . "_ERR"
                ]);
            }
        }
    }
}

if (strtolower(PHP_SAPI) != "cli") {
    ob_end_flush();
}

function autoloadclient($class)
{
    $classpath = APP_ROOT . DS . str_replace("\\", DS, str_replace(".", DS, $class)) . ".php";
    if (file_exists($classpath)) {
        include_once $classpath;
        if (WEB_DEBUG) {
            require_once 'Registry.php';
            $_import_info = &Registry::getInstance()->get("import_info");
            $_import_info[] = "import file:" . $classpath . ",mem:" . (memory_get_usage() / 1024) . "KB";
            Registry::getInstance()->set("import_info", $_import_info);
        }
    } else {
        throw new Exception(Bfw::Config("Sys", "webapp", "System")['class_not_found'] . $class);
    }
}

function exception_handler($_exception)
{
    if (strtolower(PHP_SAPI) != "cli") {
        if (WEB_DEBUG && DEBUG_IP == IP) {
            Core::V("deverror", "System", "v1", [
                'errmsg' => $_exception['errmsg'],
                "errline" => $_exception['errline'],
                "errfile" => $_exception['errfile'],
                "trace" => $_exception['trace']
            ]);
        } else {
            Bfw::LogR("PHP Warning: {$_exception->getMessage()}", "php", BoErrEnum::BFW_ERROR);
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
                Core::V("deverror", "System", "v1", [
                    'errmsg' => $_e['message'],
                    "errline" => $_e['line'],
                    "errfile" => $_e['file'],
                    "trace" => $_e['type']
                ]);
            } else {
                Bfw::LogR("PHP Warning: {$_e['message']}, {$_e['message']}, {$_e['message']}", "php", BoErrEnum::BFW_ERROR);
            }
        } else {
            print $_e;
        }
    }
}

function BoErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (WEB_DEBUG && DEBUG_IP == IP) {
        Bfw::Debug("err:" . $errstr . ",file:" . $errfile . ",line:" . $errline);
    }
    if (E_WARNING === $errno) {
        Bfw::LogR("PHP Warning: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_WARN);
        return true;
    }
    if (E_ERROR === $errno) {
        Bfw::LogR("PHP Err: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_ERROR);
        return true;
    }
    if (E_NOTICE === $errno) {
        Bfw::LogR("PHP Notic: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_INFO);
        return true;
    } else {
        Bfw::LogR("Err: $errstr, $errfile, $errline", "php", BoErrEnum::BFW_INFO);
        return true;
    }
    return false;
}
?>