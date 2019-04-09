<?php
namespace App\[[DOM]]\Service;

use Lib\Bfw;
use Lib\BoService;
use App\[[DOM]]\Model\Model_Article;

/**
 *
 * @author Herry
 *         文章服务
 */
class Service_Article extends BoService
{

    protected $_model = "Article";

    private static $_instance;

    /**
     * 获取单例
     *
     * @return Service_Article
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

    function getCatalog()
    {
        return $this->_modelins->Field(" DISTINCT classname ")->Select();
    }
}
?>