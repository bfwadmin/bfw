<?php
namespace Lib\Lock;

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

    /**
     * 构造函数
     * 传入锁的存放路径，及cache key的名称，这样可以进行并发
     *
     * @param string $path
     *            锁的存放目录，以"/"结尾
     * @param string $name
     *            cache key
     */
    function __construct($name, $path = CACHE_DIR)
    {
        // 判断是否存在eAccelerator,这里启用了eAccelerator之后可以进行内存锁提高效率
        $this->eAccelerator = function_exists("eaccelerator_lock");
        if (! $this->eAccelerator) {
            $this->path = $path . DS . "lock_" . md5($name);
        }
        $this->name = $name;
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
            return flock($this->fp, LOCK_EX|LOCK_NB);
        } else {
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
            fclose($this->fp);
        } else {
            return eaccelerator_unlock($this->name);
        }
    }
}
?>