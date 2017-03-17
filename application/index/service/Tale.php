<?php
/**
 * 吐槽逻辑处理
 * User: wetist
 * Date: 2017/2/16
 * Time: 23:19
 */

namespace app\index\service;


use think\Cache;
use think\Db;

class Tale extends Base
{
    //创建吐槽
    function create_tale($data = [])
    {
        if ($data['type'] == 1) {
            if (!$data['description']) {
                data_format_json(-2, '', 'description is null');
            }
        } elseif ($data['type'] == 2) {
            if (!$data['img']) {
                data_format_json(-3, '', 'img is null');
            }
        } else {
            data_format_json(-4, '', 'type is error');
        }
        $data['geohash'] = geohash_encode($data['longitude'], $data['latitude']);
        $m_tale = new \app\index\model\Tale();
        if ($m_tale->allowField(true)->save($data)) {
            data_format_json(0, $m_tale->getLastSql(), '创建成功');
        } else {
            data_format_json(-1, '', '创建失败，请稍后重试');
        }
    }

    //删除吐槽
    function delete_tale($where = [])
    {
        if ($where['tale_id']) {
            $m_tale = new \app\index\model\Tale();
            $data['is_deleted'] = 1;
            $data['delete_time'] = time();
            if ($m_tale->allowField(true)->save($data, $where)) {
                data_format_json(0, '', '删除成功');
            } else {
                data_format_json(-2, '', '删除失败，请稍后重试');
            }
        } else {
            data_format_json(-1, '', '请传入正确的tale_id');
        }
    }

    function get_tale_list($page, $long, $lat, $near_error)
    {

        $key_redis = 'tale_list_' . substr(geohash_encode($long, $lat), 0, $near_error);
        $tale_list_redis = Cache::get($key_redis);
        if ($tale_list_redis) {
            $tale_list = $tale_list_redis;
        } else {
            $neighbors = getNeighbors($long, $lat, $near_error);
            $tale_list = Db::query("SELECT * FROM nh_tale WHERE is_deleted = 0 AND left(geohash,$near_error) IN ($neighbors);");
            Cache::set($key_redis, $tale_list, 120);
        }
        foreach ($tale_list as $key => $value) {
            $tale_list[$key] = 1;
        }
        return $tale_list;
    }
}