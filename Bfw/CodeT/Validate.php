<?php
namespace App\DOM\Validate;

use Lib\Bfw;
use Lib\BoValidate;

/**
 *
 * @author Herry
 *         CONTMEMO
 */
class Validate_CONTNAME extends BoValidate
{
    public $_validate_array = array(
    		<temp>
    		array(
    				"FIELDNAME",
    				"require",
    				"MEMO必填"
    		),
    		</temp>
    );

    function checkClassName(&$a)
    {
        if ($a != 1) {
            return Bfw::RetMsg(true, "d");
        }
    }
}
?>

