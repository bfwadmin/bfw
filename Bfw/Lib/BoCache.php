<?php
namespace Lib;

/**
 * @author wangbo
 * cache父类
 */
class BoCache
{


    /**
     * 缓存
     *
     * @param string $_key
     * @param string $_val
     * @param int $_lifetime
     */
    public static function Cache($_key, $_val = null, $_lifetime = 180,$_dom=DOMIAN_VALUE)
    {
        $_key=$_key.$_dom;
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
        Core::ImportClass($_cache_instance);
        if (is_null($_val)) {
            return $_cache_instance::getInstance()->getkey($_key);
        } else {
            if($_val==""){
                return $_cache_instance::getInstance()->del($_key);
            }else{
                return $_cache_instance::getInstance()->setkey($_key, $_val, $_lifetime);
            }

        }
    }

    /**
     * 删除key
     *
     * @param string $_key
     */
    public static function DelC($_key,$_dom=DOMIAN_VALUE)
    {
        $_key=$_key.$_dom;
        $_cache_instance = "Lib\\Cache\\" . CACHE_HANDLER_NAME;
         Core::ImportClass($_cache_instance);

        return $_cache_instance::getInstance()->del($_key);
    }
}

?>