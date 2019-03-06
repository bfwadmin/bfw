<?php
namespace App\DOM\Model;

use Lib\BoConfig;
use Lib\BoModel;

/**
 * @author bfw
 * CONTMEMO数据模型
 */
class Model_CONTNAME extends BoModel
{

    protected $_prikey = "id";

    protected $_isview = false;
    
    //protected $_cpsql="select * from [Model] as b left join [Model] as b where a.id=b.id";//

    private static $_instance;

    function __construct()
    {
        $this->_connarray = BoConfig::Config("Db", "localconfig");
        parent::__construct();
    }

    /**
     * 获取单例
     *
     * @return Model
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