<?php
namespace App\Hbapi\Validate;

use Lib\Bfw;
use Lib\BoValidate;

/**
 *
 * @author Herry
 *         
 */
class Validate_Usermoney extends BoValidate
{
    public $_validate_array = array(
    		
    		array(
    				"money",
    				"require",
    				"money必填"
    		),
    		
    );

    function checkClassName(&$a)
    {
        if ($a != 1) {
            return Bfw::RetMsg(true, "d");
        }
    }
}
?>

