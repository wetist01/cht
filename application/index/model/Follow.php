<?php
/**
 * Created by PhpStorm.
 * User: wetist
 * Date: 2017/4/22
 * Time: 23:27
 */

namespace app\index\model;


class Follow extends Base
{
    protected $pk = 'follow_id';

    protected $readonly = ['uid', 'followed_uid', 'create_time'];

    /**
     * 创建关注
     * @param int $uid
     * @param int $followed_uid
     * @return mixed
     */
    function create_follow($uid, $followed_uid)
    {
        $data['uid'] = $uid;
        $data['followed_uid'] = $followed_uid;
        $this->allowField(true)->data($data)->save();
        $follow_id = $this->follow_id;
        return $follow_id;
    }
}