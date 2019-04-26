<?php
namespace Qiniu;
vendor('Qiniu.Auth');
vendor('Qiniu.Storage.UploadManager');
vendor('Qiniu.Storage.FormUploader');
vendor('Qiniu.Zone');
vendor('Qiniu.Config');
vendor('Qiniu.functions');
vendor('Qiniu.Http.Client');
vendor('Qiniu.Http.Request');
vendor('Qiniu.Http.Response');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuUpload
{
    private $accessKey;
    private $secretKey;
    private $bucket;
    private $token;

    public function __construct()
    {
        $this->accessKey = '7YKPK73lkZP0UvLog4LqymohPRn3eFu0IIjayDA8';
        $this->secretKey = 'knpWEHEp0wooV3YdWJ11DzHb5vOsGEFKK2g2g4Sq';
        $this->bucket = 'host01';
    }

    public function getToken(){
        $auth = new Auth($this->accessKey, $this->secretKey);
        $this->token = $auth->uploadToken($this->bucket);
    }

    public function uploadMgr($key,$path){
        $this->getToken();
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($this->token, $key, $path);
        return $ret;
    }
}