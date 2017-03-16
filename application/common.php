<?php
//短信发送公共方法
function send_sms($mobile = '', $text = '')
{
    if ($mobile && $text) {
        include_once EXTEND_PATH . 'sms-yunpian/YunpianAutoload.php';
        $smsOperator = new SmsOperator();
        $data['mobile'] = $mobile;
        $data['text'] = $text;
        $smsOperator->single_send($data);
    }
}

//json结果返回
function data_format_json($error_code = 0, $content = [], $msg = '')
{
    $data = [];
    $data['ret'] = $error_code;
    $data['data'] = $content;
    $data['msg'] = $msg;

    echo json_encode($data);
    exit;
}

/**
 * 判断是否是手机号
 * @param int $mobile_num
 * @return int 0代表不是 1代表是
 */
function is_mobile_num($mobile_num = 0)
{
    $pattern_mobile = "/^1\d{10}$/";
    $is_mobile = preg_match($pattern_mobile, $mobile_num);
    return $is_mobile;
}

//随机六位数
function rand_number($length = 6)
{
    if ($length < 1) {
        $length = 6;
    }

    $min = 1;
    for ($i = 0; $i < $length - 1; $i++) {
        $min = $min * 10;
    }
    $max = $min * 10 - 1;

    return rand($min, $max);
}

/**
 * 将geohash值解码为经纬度
 * @param string $hash geohash值
 * @return array|bool
 */
function geohash_decode($hash = '')
{
    if (!$hash) {
        return false;
    } else {
        $geo = new \app\index\controller\GeoHash();
        $result = $geo->decode($hash);
        $lat = $result[0];
        $long = $result[1];
        $result = [$long, $lat];
        return $result;
    }
}

/**
 * 将普通经纬度进行geohash编码
 * @param int $long 经度
 * @param int $lat 纬度
 * @return bool|string 返回编码后的geohash
 */
function geohash_encode($long = 0, $lat = 0)
{
    if ($lat == 0 && $long == 0) {
        return false;
    } else {
        $geo = new \app\index\controller\GeoHash();
        $result = $geo->encode($lat, $long);
        return $result;
    }
}
