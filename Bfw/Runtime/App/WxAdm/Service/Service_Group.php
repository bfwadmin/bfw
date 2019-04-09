<?php
namespace App\[[DOM]]\Service;
use Lib\BoService;

/**
 *
 * @author Herry
 *         人物
 */
class Service_Group extends BoService
{

    protected $_model = "Group";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Group
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