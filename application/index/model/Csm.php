<?php
/**
 * Created by PhpStorm.
 * User: wetist
 * Date: 2017/8/16
 * Time: 01:00
 */

namespace app\index\model;


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
        $where['is_deleted'] = 0;
        $field = 'csm_id,csm_name,tab,img,longitude,latitude,geohash';
        $list = $this->where($where)->field($field)->select();
        $list = jsonToArray($list);
        return $list;
    }
}