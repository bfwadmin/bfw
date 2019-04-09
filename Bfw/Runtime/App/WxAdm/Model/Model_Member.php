<?php
namespace App\[[DOM]]\Model;

use Lib\Bfw;
use Lib\BoModel;


class Model_Member extends BoModel
{

    protected $_prikey = "id";

    protected $_pre = "";

    protected $_isview = false;

    private static $_instance;

    function __construct()
    {
        $this->_connarray = Bfw::Config("Db", "localconfig");
        parent::__construct();
    }

    /**
     * 获取单例
     *
     * @return Service_Logs
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

?>