<?php
namespace App\[[DOM]]\Validate;

use Lib\Bfw;
use Lib\BoValidate;

/**
 *
 * @author Herry
 *         文章验证
 */
class Validate_Article extends BoValidate
{

    public $_validate_array = array(
        array(
            "classname",
            "require",
            "类别必填"
        ),
        array(
            "title",
            "require",
            "标题必填"
        ),
        array(
            "content",
            "require",
            "内容必填"
        )
    );

    function checkClassName(&$a)
    {
        if ($a != 1) {
            return Bfw::RetMsg(true, "d");
        }
    }
}
?>

