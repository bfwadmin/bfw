<?php
namespace App\[[DOM]]\Validate;

use Lib\BoValidate;
use Lib\Bfw;

/**
 *
 * @author Herry
 *         人物
 */
class Validate_Power extends BoValidate
{

    public $_validate_array = array(
        array(
            "powername",
            "require",
            "权利名称必填"
        ),
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

