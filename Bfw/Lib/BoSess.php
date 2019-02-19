<?php
namespace Lib;

class BoSess
{

    public static function DestorySess($_key = null,$_dom=DOMIAN_VALUE)
    {
        if (strtolower(PHP_SAPI) != "cli") {
            $_key=$_dom.$_key;
            // if (defined(SESS_ID) && SESS_ID == "") {
            session_start();
            // }
            if (is_null($_key)) {
                session_unset();
                session_destroy();
            } else {
                unset($_SESSION[$_key]);
            }
            session_write_close();
        }
        return true;
    }
    // session操作 key val 失效时间 秒
    public static function Session($_key, $_val = "", $_expire = 0,$_dom=DOMIAN_VALUE)
    {
        if (strtolower(PHP_SAPI) != "cli") {
            session_start();
            $_key=$_dom.$_key;
            $ret = null;
            if (empty($_val) || is_null($_val)) {
                if (is_null($_val)) {
                    unset($_SESSION[$_key]);
                    $ret = true;
                } else {
                    if (isset($_SESSION[$_key])) {
                        $_tmp = $_SESSION[$_key];
                        if (is_array($_tmp) && isset($_tmp['expire'])) {
                            if (time() > $_tmp['expire']) {
                                $ret = "";
                            } else {
                                $ret = isset($_tmp['value']) ? $_tmp['value'] : '';
                            }
                        } else {
                            $ret = $_tmp;
                        }
                    } else {
                        $ret = "";
                    }
                    // Bfw::LogR("read,".$_key."=".$ret."</br>");
                }
            } else {
                if ($_expire > 0) {
                    $_SESSION[$_key] = [
                        'value' => $_val,
                        'expire' => time() + $_expire
                    ];
                } else {
                    $_SESSION[$_key] = $_val;
                }
                // Bfw::LogR("write.".$_key."=".$_val."</br>") ;
                
                $ret = true;
            }
            session_write_close();
            
            return $ret;
        }
        return "";
    }

    /**
     * 防止恶意攻击
     *
     * @param int $intvaltime            
     * @return boolean
     */
    public static function AntiRobotAttack($intvaltime)
    {
        $_sesskey = CONTROL_VALUE . '_' . ACTION_VALUE . 'lastvisitedtime';
        if (self::Session($_sesskey) != null) {
            if (time() - self::Session($_sesskey) < $intvaltime) {
                self::Session($_sesskey, time());
                return false;
            } else {
                self::Session($_sesskey, time());
                return true;
            }
        } else {
            self::Session($_sesskey, time());
            return true;
        }
    }
    

}

?>