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
    /**
     * 创建吐槽
     * @author kongjian
     * @param array $data
     */
    function create_tale($data = [])
    {
        $data['geohash'] = geohash_encode($data['longitude'], $data['latitude']);

        $m_user = new \app\index\model\User();
        if ($data['form_id']) {
            $m_user->isUpdate(true)->save(['form_id' => $data['form_id']], ['uid' => $data['uid']]);
        }
        $user_info = $m_user->fetchWhere(['uid' => $data['uid']], 'user_name,sex,img_head', 1);
        $data['user_name'] = $user_info['user_name'];
        $data['img_head'] = $user_info['img_head'];
        $data['sex'] = $user_info['sex'];

        $m_tale = new \app\index\model\Tale();

        if ($m_tale->allowField(true)->save($data)) {
            $key_redis = 'tale_list_' . substr(geohash_encode($data['longitude'], $data['latitude']), 0, 6) . '_limit_' . 40;
            Cache::rm($key_redis);
            data_format_json(0, '', '创建成功');
        } else {
            data_format_json(-1, '', '创建失败，请稍后重试');
        }
    }

    /**
     * 删除吐槽
     * @author kongjian
     * @param array $where
     */
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
     * 获取tale_list
     * @author kongjian
     * @param Decimal $long 经度
     * @param Decimal $lat 维度
     * @param int $near_error 精度，如为6代表附近2km
     * @param int $page 分页
     * @param int $uid
     * @param string $token
     * @return array
     */
    function get_tale_list($long, $lat, $near_error, $page, $uid, $token, $version)
    {
        if ($uid && $token) {//判断是否是登录用户
            $tale_list = $this->get_tale_list_login($long, $lat, $near_error, $page, $uid, $token);
        } else {
            $tale_list = $this->get_tale_list_not_login($long, $lat, $near_error, $page);
        }

        return $tale_list;
    }

    /**
     * 登录用户tale_list数据处理
     * @author kongjian
     * @param Decimal $long 经度
     * @param Decimal $lat 维度
     * @param int $near_error 精度，如为6代表附近2km
     * @param int $page 分页 1除外的都返回空
     * @param int $uid
     * @param string $token
     * @return array|mixed
     */
    function get_tale_list_login($long, $lat, $near_error = 6, $page = 1, $uid, $token)
    {
        $cache_key = 'tale_list_login_' . $uid . '_' . $token;

        if ($page == 1) {
            $model_tale = new \app\index\model\Tale();
            $tale_list = $model_tale->get_tale_list($long, $lat, $near_error, 40, 3600);
            Cache::set($cache_key, $tale_list, 3600);
        } else {
            $tale_list_cache = Cache::get($cache_key);
            if ($tale_list_cache) {
                $tale_list = $tale_list_cache;
            } else {
                $tale_list = [];
            }
        }

        $tale_list = $this->process_tale($tale_list, $long, $lat, $page);

        return $tale_list;
    }

    /**
     * 未登录用户tale_list数据处理
     * @author kongjian
     * @param Decimal $long 经度
     * @param Decimal $lat 维度
     * @param int $near_error 精度，如为6代表附近2km
     * @param int $page 分页 1除外的都返回空
     * @return array|mixed
     */
    function get_tale_list_not_login($long, $lat, $near_error = 6, $page = 1)
    {
        if ($page == 1) {
            $model_tale = new \app\index\model\Tale();
            $tale_list = $model_tale->get_tale_list($long, $lat, $near_error, 30, 5);//todo
        } else {
            $tale_list = [];
        }

        $tale_list = $this->process_tale($tale_list, $long, $lat, $page);


        return $tale_list;
    }

    /**
     * 处理从model获取的tale原始数据
     * @author kongjian
     * @param array $tale_list
     * @param $long
     * @param $lat
     * @param $page
     * @return array
     */
    function process_tale($tale_list = [], $long = 0, $lat = 0, $page = 1)
    {
        if ($tale_list && $page > 0) {
            $tale_list = page_array($tale_list, $page, 50);
            foreach ($tale_list as $key => $value) {
                $tale_list[$key]['distance'] = getDistance($long, $lat, $value['longitude'], $value['latitude']);
                $tale_list[$key]['latest_reply_time'] = getTimeDifference($value['update_time']);

                if ($value['is_anon'] == 1) {//TODO 增加匿名用户的默认头像
                    $tale_list[$key]['user_name'] = '匿名';
                    $tale_list[$key]['img_head'] = 'http://img.chuanhuatong.cc/head/20170907/43fe8ec6278c240c82376d6ddb9486b9.png';
                }

                unset($tale_list[$key]['longitude']);
                unset($tale_list[$key]['latitude']);
                unset($tale_list[$key]['create_time']);
                unset($tale_list[$key]['update_time']);
                unset($tale_list[$key]['delete_time']);
                unset($tale_list[$key]['is_deleted']);
                unset($tale_list[$key]['status']);
                unset($tale_list[$key]['geohash']);
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

            if ($info['is_anon'] == 1) {
                $info['user_name'] = '匿名';
                $info['img_head'] = 'http://img.chuanhuatong.cc/head/20170907/43fe8ec6278c240c82376d6ddb9486b9.png';
            }

            $info['distance'] = getDistance($data['longitude'], $data['latitude'], $info['longitude'], $info['latitude']);

            $info['latest_reply_time'] = getTimeDifference($info['update_time']);

            unset($info['update_time'], $info['longitude'], $info['latitude']);

            data_format_json(0, $info, 'success');
        } else {
            data_format_json(-1, '', 'return is null');
        }
    }

    /**
     * 我发布的
     * @author kongjian
     * @param int $uid
     * @param int $page 分页
     * @param float $long 经度
     * @param float $lat 纬度
     */
    function my_tale_list($uid, $page, $long, $lat)
    {
        $where['uid'] = $uid;
        $m_tale = new \app\index\model\Tale();
        $list = $m_tale->my_tale_list($uid, $page);
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['distance'] = getDistance($long, $lat, $value['longitude'], $value['latitude']);
                $list[$key]['latest_reply_time'] = getTimeDifference($value['update_time']);
            }
            data_format_json(0, $list, 'success');

        } else {
            data_format_json(-3, '', 'return is null');
        }
    }

}