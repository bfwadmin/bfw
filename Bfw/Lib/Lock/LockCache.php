<?php
namespace Lib\Lock;

use Lib\BoDebug;
class LockCache implements BoLockInterface
{
    // 文件锁存放路径
    private $path = null;
    // 文件句柄
    private $fp = null;
    // 锁粒度,设置越大粒度越小
    private $hashNum = 100;
    // cache key
    private $name;
    // 是否存在eaccelerator标志
    private $eAccelerator = false;

    private  static $_instance = null;
    private $_timeout = 0;

    /**
     * 构造函数
     * 传入锁的存放路径，及cache key的名称，这样可以进行并发
     *
     * @param string $path
     *            锁的存放目录，以"/"结尾
     * @param string $name
     *            cache key
     */
    function __construct($name, $_timeout =LOCK_TIMEOUT )
    {
        $this->_timeout = $_timeout;
        $this->_name = $name;
        // 判断是否存在eAccelerator,这里启用了eAccelerator之后可以进行内存锁提高效率
        $this->eAccelerator = function_exists("eaccelerator_lock");
        if (! $this->eAccelerator) {
            $this->path = CACHE_DIR . DS . "lock_" . md5($name);
            BoDebug::Info("filelock " . $this->_name);
        }else{
            BoDebug::Info("eAcceleratorlock " . $this->_name);
        }
       
    }

    public static function getInstance($name, $path = CACHE_DIR)
    {
        if (self::$_instance == null) {
            self::$_instance = new LockCache($name, $path);
        }
        return self::$_instance;
    }

   
    /**
     * 加锁
     * Enter description here .
     *
     * ..
     */
    public function lock()
    {
        // 如果无法开启ea内存锁，则开启文件锁
        if (! $this->eAccelerator) {
            // 配置目录权限可写
            $this->fp = fopen($this->path, 'w+');
            if ($this->fp === false) {
                return false;
            }
            
            BoDebug::Info("filelock  lock" . $this->_name);
            //此锁非阻塞模式在windows下不支持，会一直阻塞，建议在linux下运行
            return flock($this->fp, LOCK_EX|LOCK_NB);
        } else {
            BoDebug::Info("eacceleratorlock  lock" . $this->_name);
            return eaccelerator_lock($this->name);
        }
    }

    /**
     * 解锁
     * Enter description here .
     *
     * ..
     */
    public function unlock()
    {
        if (! $this->eAccelerator) {
            if ($this->fp !== false) {
                flock($this->fp, LOCK_UN);
                clearstatcache();
            }
            // 进行关闭
            BoDebug::Info("filelock  unlock" . $this->_name);
            fclose($this->fp);
        } else {
            BoDebug::Info("eacceleratorlock  unlock" . $this->_name);
            return eaccelerator_unlock($this->name);
        }
    }
}
?>