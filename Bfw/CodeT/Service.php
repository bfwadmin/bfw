<?php
namespace App\DOM\Service;


use Lib\BoService;
use App\DOM\Model\Model_CONTNAME;

/**
 *
 * @author bfw
 * CONTMEMO
 */
class Service_CONTNAME extends BoService
{

    protected $_model = "CONTNAME";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_CONTNAME
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
