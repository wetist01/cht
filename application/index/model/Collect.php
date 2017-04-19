<?php
/**
 * 收藏模型类
 * User: kongjian
 * Date: 2017/4/18
 * Time: 20:55
 */

namespace app\index\model;


class Collect extends Base
{
    protected $pk = 'collect_id';

    /**
     * 查询tale_id
     * @author kongjian
     * @param int $uid
     * @param int $page
     * @return array
     */
    function get_tale_id_by_uid($uid = 0, $page = 1)
    {
        $where = [
            'uid' => $uid,
            'is_deleted' => 0
        ];
        $tale_id = $this->where($where)->order('collect_id', 'desc')->page($page, 10)->column('tale_id');
        return $tale_id;
    }
}