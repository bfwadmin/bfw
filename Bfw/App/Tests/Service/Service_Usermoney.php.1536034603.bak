<?php
namespace App\Hbapi\Service;

use Lib\Bfw;
use Lib\BoService;
use App\Hbapi\Model\Model_Usermoney;

/**
 *
 * @author Herry
 * 
 */
class Service_Usermoney extends BoService
{

    protected $_model = "Usermoney";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Usermoney
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function getkey()
    {
        return "123";
    }

}
?>
