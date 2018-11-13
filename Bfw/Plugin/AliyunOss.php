<?php
use OSS\OssClient;

function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'OSS' . DIRECTORY_SEPARATOR . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');

class AliyunOss
{

    protected $accessKeyId = "24mdGLCYaHZVkZIk";

    protected $accessKeySecret = "qQGjF3ixSGPWtL3vBRXtsleq13w5p9";

    protected $endpoint = "oss-cn-shanghai.aliyuncs.com";

    protected $ossClient;

    private static $_instance;

    function __construct()
    {
        try {
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * 获取单例
     * 
     * @return Client_Account_moneyhistory
     */
    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

   public  function putObject($bucket, $object, $content)
    {
        
        // $bucket = "$bucket";
        // $object = "fffff.jpg";
        // $content = file_get_contents("test.jpg"); // 上传的文件内容
        try {
           return  $this->ossClient->putObject($bucket, $object, $content);
        } catch (OssException $e) {
            print $e->getMessage();
            return false;
        }
    }
}

?>