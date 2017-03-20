<?php
/**
 * 吐槽逻辑处理
 * User: wetist
 * Date: 2017/2/16
 * Time: 23:19
 */

namespace app\index\service;

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

        $m_user = new \app\index\model\User();
        $data['user_name'] = $m_user->where('uid', $data['uid'])->value('name');
        $data['img_head'] = $m_user->where('uid', $data['uid'])->value('img_head');
        $m_tale = new \app\index\model\Tale();

        if ($m_tale->allowField(true)->save($data)) {
            data_format_json(0, '', '创建成功');
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

    /**
     * 对附近的原始数据进行处理
     * @param int $long 经度
     * @param int $lat 纬度
     * @param int $near_error 精度，默认6，代表附近2km
     * @return array|mixed
     */
    function get_tale_list($long = 0, $lat = 0, $near_error = 6)
    {
        $model_tale = new \app\index\model\Tale();
        $tale_list = $model_tale->get_tale_list($long, $lat, $near_error);

        if ($tale_list) {
            foreach ($tale_list as $key => $value) {
                $tale_list[$key]['distance'] = getDistance($long, $lat, $value['longitude'], $value['latitude']);
                $tale_list[$key]['d_value'] = getTimeDifference($value['update_time']);

                if ($value['is_anon'] == 1) {//TODO 增加匿名用户的默认头像
                    $tale_list[$key]['user_name'] = '匿名';
                }

                unset($tale_list[$key]['longitude']);
                unset($tale_list[$key]['latitude']);
            }
        } else {
            $tale_list = [];
        }

        return $tale_list;
    }

}