<?php
namespace App\[[DOM]]\Validate;

use Lib\BoValidate;
use Lib\Bfw;

/**
 *
 * @author Herry
 *         人物
 */
class Validate_Group extends BoValidate
{

    public $_validate_array = array(
        array(
            "groupname",
            "require",
            "组名必填"
        ),
        array(
            "grouppower",
            "require",
            "组权限必填"
        )
    );

    public function checkName($a)
    {
        if ($a == 2) {
            return array(
                "err" => true,
                "data" => Bfw::Config("Sys", "validate")['input_array_empty']
            );
        }
    }
}
?>

