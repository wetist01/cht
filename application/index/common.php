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
function encode_coordinate($data = '')
{
    if ($data && is_numeric($data)) {
        $result = round($data * 1000000);
    } else {
        $result = null;
    }
    return $result;
}

/**
 * 数组分页
 * @author kongjian
 * @param int $count
 * @param int $page
 * @param array $array
 * @param int $order
 * @return array
 */
function page_array($array = [], $page = 1, $count = 0, $order = 0)
{
    $offset = ($page - 1) * $count; #计算每次分页的开始位置
    if ($order == 1) {
        $array = array_reverse($array);
    }
    $page_data = array_slice($array, $offset, $count);

    return $page_data; #返回查询数据
}



