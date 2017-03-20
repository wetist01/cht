<?php
/**
 * Created by PhpStorm.
 * 吐槽模型类
 * User: wetist
 * Date: 2017/1/17
 * Time: 10:28
 */

namespace app\index\model;

use think\Model;
use think\Cache;

class Tale extends Model
{
    protected $pk = 'tale_id';

    protected $readonly = ['uid', 'user_name', 'geohash', 'longitude', 'latitude', 'create_time', 'img_head'];

    /**
     * 获取附近原始数据
     * @param Decimal $long 经度
     * @param Decimal $lat 纬度
     * @param int $near_error 范围经度，默认为6，代表附近2km
     * @return mixed
     */
    function get_tale_list($long = 0, $lat = 0, $near_error = 6)
    {
        $key_redis = 'tale_list_' . substr(geohash_encode($long, $lat), 0, $near_error);

        $tale_list_redis = Cache::get($key_redis);

        if ($tale_list_redis) {
            $tale_list = $tale_list_redis;
        } else {
            $neighbors = getNeighbors($long, $lat, $near_error);
            if ($neighbors) {
                $tale_list = $this->query("SELECT * FROM nh_tale WHERE is_deleted = 0 AND left(geohash,$near_error) IN ($neighbors)");
                Cache::set($key_redis, $tale_list, 120);
            } else {
                $tale_list = [];
            }
        }

        return $tale_list;
    }
}