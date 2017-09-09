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
}