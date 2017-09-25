<?php
/**
 * 传送门模型
 * User: kongjian
 * Date: 2017/8/16
 * Time: 01:00
 */

namespace app\index\model;


use think\Cache;

class Csm extends Base
{
    protected $pk = 'uid';

    protected $readonly = ['csm_id', 'csm_name', 'geohash', 'longitude', 'latitude', 'create_time'];

    /**
     * 获取传送门列表
     * @author kongjian
     * @return array
     */
    function csm_list()
    {
        $csm_list = Cache::get('csm_list');

        if ($csm_list) {
            $list = $csm_list;
        } else {
            $where['is_deleted'] = 0;
            $field = 'csm_id,csm_name,tab,img';
            $list = $this->where($where)->field($field)->order('sort', 'asc')->select();
            $list = jsonToArray($list);
            Cache::set('csm_list', $list, 3600 * 24);
        }

        return $list;
    }

    /**
     * 金科比脸大赛
     * @author kongjian
     * @return mixed
     */
    function face_match()
    {
        $cache_key = 'face_match';
        $cache_list = Cache::get($cache_key);
        if ($cache_list) {
            $list = $cache_list;
        } else {
            $list = $this->query("select * from cht_tale where status = 0 and is_deleted = 1 and left(`description`,4) = '我要比脸' order by update_time DESC limit 50;");
            Cache::set($cache_key, $list, 5);
        }
        return $list;
    }
}