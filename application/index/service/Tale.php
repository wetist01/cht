<?php
/**
 * 吐槽逻辑处理
 * User: kongjian
 * Date: 2017/2/16
 * Time: 23:19
 */

namespace app\index\service;


use think\Cache;

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
        $user_info = $m_user->fetchWhere(['uid' => $data['uid']], 'name,sex,img_head', 1);
        $data['user_name'] = $user_info['user_name'];
        $data['img_head'] = $user_info['img_head'];
        $data['sex'] = $user_info['sex'];

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
     * @param int $page
     * @return array|mixed
     */
    function get_tale_list($long = 0, $lat = 0, $near_error = 6, $page = 1, $uid, $token)
    {
        if ($uid && $token) {//判断是否是登录用户,如果登录从缓存读取数据
            $key = 'tale_list_login_' . $uid . '_' . $token;
            $tale_list_cache = Cache::get($key);
            if ($tale_list_cache) {
                $tale_list = $tale_list_cache;
            } else {
                $model_tale = new \app\index\model\Tale();
                $tale_list = $model_tale->get_tale_list($long, $lat, $near_error);
                Cache::set($key, $tale_list, 3600);
            }
        } else {
            $model_tale = new \app\index\model\Tale();
            $tale_list = $model_tale->get_tale_list($long, $lat, $near_error, 20);
        }

        if ($tale_list) {
            $tale_list = page_array($tale_list, $page, 20);
            foreach ($tale_list as $key => $value) {
                $tale_list[$key]['distance'] = getDistance($long, $lat, $value['longitude'], $value['latitude']);
                $tale_list[$key]['latest_reply_time'] = getTimeDifference($value['update_time']);

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

    /**
     * 根据tale_id获取单条tale详情
     * @author kongjian
     * @param array $data
     */
    function get_tale_info($data)
    {
        $m_tale = new \app\index\model\Tale();
        $where['tale_id'] = $data['tale_id'];
        $field = 'tale_id,uid,user_name,sex,img_head,like_num,comment_num,description,is_anon,type,img,update_time,longitude,latitude';
        $info = $m_tale->fetchWhere($where, $field, 1);
        if ($info) {
            $info = json_decode(json_encode($info), true);

            if ($info['is_anon'] == 1) {
                $info['user_name'] = '匿名';
            }

            $info['distance'] = getDistance($data['longitude'], $data['latitude'], $info['longitude'], $info['latitude']);

            $info['latest_reply_time'] = getTimeDifference($info['update_time']);

            unset($info['update_time'], $info['longitude'], $info['latitude']);

            data_format_json(0, $info, 'success');
        } else {
            data_format_json(-1, '', 'return is null');
        }
    }

}