<?php
namespace Lib\Cache;

use Lib\Exception\CacheException;
use Lib\BoDebug;

class CacheZookeeper implements BoCacheInterface
{

    private static $_instance = null;

    private $mem;

    private $_parent_path = "/cache";

    private $_acl = array(
        array(
            'perms' => \Zookeeper::PERM_ALL,
            'scheme' => 'world',
            'id' => 'anyone'
        )
    );

    function __construct()
    {
        $this->mem = new \Zookeeper(CACHE_ZK_IP . ":" . CACHE_ZK_PORT);
        if (! $this->mem->exists($this->_parent_path))
            $this->mem->create($this->_parent_path, "parent", $this->_acl);
    }

    static function getInstance()
    {
        // BoDebug::Info("opencachedir ".CACHE_DIR);
        if (self::$_instance == null) {
            self::$_instance = new CacheZookeeper();
        }
        return self::$_instance;
    }

    function setkey($_key, $_val, $_expire = 1800)
    {
        BoDebug::Info("zkcache setkey " . $_key);
        
        try {
            $this->mem->set($this->_parent_path . "/expire" . md5($_key), $_expire + time());
            return $this->mem->set($this->_parent_path . "/" . md5($_key), serialize($_val));
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }

    function getkey($_key)
    {
        BoDebug::Info("zkcache getkey " . $_key);
        $ret = null;
        $_expiredata = $this->mem->get($this->_parent_path . "/expire" . md5($_key));
        if ($_expiredata == 0 || $_expire > time()) {
            $ret = unserialize($this->mem->get($this->_parent_path . "/" . md5($_key)));
        }
        return $ret;
    }

    function del($_key)
    {
        BoDebug::Info("zkcache delkey " . $_key);
        try {
            return $this->mem->delete($this->_parent_path . "/" . md5($_key));
        } catch (\Exception $e) {
            throw new CacheException($e->getMessage());
        }
    }
}

?>