<?php
namespace App\[[DOM]]\Validate;

use Lib\BoValidate;
use Lib\Bfw;

/**
 *
 * @author Herry
 * 登录验证
 */
class Validate_Login extends BoValidate
{

    public $_validate_array = array(
        array(
            "username",
            "require",
            "手机号必填"
        ),
        array(
            "userpwd",
            "require",
            "密码必填"
        ),
        
    );

    public function checkName($a)
    {
        /*array(
            "usernamed",
            "regex",
            ['/^1[0-9]{10}$/','^[0-9]+$'],
            "手机号不对"
        )*/
        if ($a == 2) {
            return array(
                "err" => true,
                "data" => Bfw::Config("Sys", "validate")['input_array_empty']
            );
        }
    }
    
    
}
?>

