<?php
namespace Lib;

class BoCheck
{

    public static function CheckDir()
    {
        $_unpass = [];
        $_checkdir = [
            SESSION_SAVE_PATH,
            DATA_DIR,
            CACHE_DIR,
            LOG_DIR,
            RUNTIME_DIR,
            APP_DIR,
            PLUGIN_DIR
        ];
        foreach ($_checkdir as $item) {
            if (file_put_contents($item . DS . "bfw_testfile", "write test")) {
                @unlink($item . DS . "bfw_testfile");
            } else {
                $_unpass[] = $item;
            }
        }
        if (empty($_unpass)) {
            return true;
        } else {
            Core::V("dircheck", "System", "v1", [
                "dir_check_arr" => $_unpass
            ]);
            return false;
        }
    }
}

?>