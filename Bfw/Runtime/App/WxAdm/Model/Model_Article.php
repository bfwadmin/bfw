<?php
namespace App\[[DOM]]\Model;

use Lib\Bfw;
use Lib\BoModel;

/**
 * @author Herry
 * 文章数据模型
 */
class Model_Article extends BoModel
{

    protected $_prikey = "id";

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
     * @return Model
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /*
     * protected $_connarray = array(
     * "dbtype" => "DbMysql",
     * "dbconnstr" => "mysql:host=127.0.0.1;dbname=88art",
     * "dbport" => 3306,
     * "dbuser" => "root",
     * "dbpwd" => ""
     * );
     */
}

?>