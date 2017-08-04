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

    /**
     *获取我关注的用户的uid
     * @author kongjian
     * @param int $uid
     * @return array
     */
    function get_followed_uid_by_uid($uid)
    {
        $where['uid'] = $uid;
        $followed_uid = $this->where($where)->field('followed_uid')->select();
        $followed_uid = jsonToArray($followed_uid);

        foreach ($followed_uid as $key => $value) {
            $followed_uid[$key] = $value['followed_uid'];
        }

        return $followed_uid;
    }

}