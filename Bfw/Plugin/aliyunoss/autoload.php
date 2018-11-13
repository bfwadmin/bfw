<?php

function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');

$accessKeyId = "24mdGLCYaHZVkZIk"; ;
$accessKeySecret = "qQGjF3ixSGPWtL3vBRXtsleq13w5p9";
$endpoint = "88artpic.oss-cn-shanghai.aliyuncs.com";
try {
    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint,false);
} catch (OssException $e) {
    print $e->getMessage();
}
$bucket = "88artpic";
$object = "fffff.txt";
$content = "Hello, OSS!"; // 上传的文件内容
try {
    $ossClient->putObject($bucket, $object, $content);
} catch (OssException $e) {
    print $e->getMessage();
}