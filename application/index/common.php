<?php
// 应用公共文件
use OSS\OssClient;
use OSS\Core\OssException;

//oss本地上传文件
function upload_file_oss($bucket, $object, $file)
{
    $accessKeyId = ACCESS_KEY_ID;
    $accessKeySecret = ACCESS_KEY_SECRET;
    $endpoint = ENDPOINT;
    try {
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        return $ossClient->uploadFile($bucket, $object, $file);
    } catch (OssException $e) {
        print $e->getMessage();
    }
}

//将坐标系x1000000取整数
function encode_coordinate($data = ''){
    if ($data && is_numeric($data)){
        $result = round($data*1000000);
    }else{
        $result = null;
    }
    return $result;
}



