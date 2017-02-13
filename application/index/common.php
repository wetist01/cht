<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

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