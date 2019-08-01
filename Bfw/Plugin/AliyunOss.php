<?php
namespace Plugin;

class AliyunOss
{

    protected $accessKeyId = "";

    protected $accessKeySecret = "";

    protected $endpoint = "";

    protected $ossClient;

    private static $_instance;

    function __construct($accessKeyId,$accessKeySecret,$endpoint)
    {
        $this->accessKeyId=$accessKeyId;
        $this->accessKeySecret=$accessKeySecret;
        $this->endpoint=$endpoint;
        try {
            $this->ossClient = new \Plugin\OSS\OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        } catch (\Plugin\OSS\Core\OssException $e) {
            print $e->getMessage();
        }
    }

    /**
     * 获取单例
     *
     * @return Client_Account_moneyhistory
     */
    public static function getInstance($accessKeyId,$accessKeySecret,$endpoint)
    {
        if (! (self::$_instance instanceof self)) {
            self::$_instance = new self($accessKeyId,$accessKeySecret,$endpoint);
        }
        return self::$_instance;
    }

    public function copyObject($from_bucket, $from_object, $to_bucket, $to_object)
    {
        try {
            return $this->ossClient->copyObject($from_bucket, $from_object, $to_bucket, $to_object);
        } catch (\Plugin\OSS\Core\OssException $e) {
            printf($e->getMessage() . "\n");
            return false;
        }
    }
    public function uploadFile($bucket, $object, $file){
        try {
            return $this->ossClient->uploadFile($bucket, $object, $file);
        } catch (\Plugin\OSS\Core\OssException $e) {
            print $e->getMessage();
            return false;
        }

    }
    public function putObject($bucket, $object, $content)
    {
        try {
            return $this->ossClient->putObject($bucket, $object, $content);
        } catch (\Plugin\OSS\Core\OssException $e) {
            print $e->getMessage();
            return false;
        }
    }
}

?>