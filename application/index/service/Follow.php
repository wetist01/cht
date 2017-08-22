<?php
/**
 * 关注逻辑处理
 * User: kongjian
 * Date: 2017/4/22
 * Time: 23:20
 */

namespace app\index\service;


class Follow extends Base
{
    /**
     * 创建关注
     * @author kongjian
     * @param int $uid 关注者uid
     * @param int $followed_uid 被关注者uid
     */
    function create_follow($uid, $followed_uid)
    {
        $m_follow = new \app\index\model\Follow();

        $where['uid'] = $uid;
        $where['followed_uid'] = $followed_uid;

        $is_exist = $m_follow->where($where)->find();

        if ($is_exist) {
            data_format_json(-2, '', '已经关注过');
        }

        $follow_id = $m_follow->create_follow($uid, $followed_uid);

        if (intval($follow_id)) {
            data_format_json(0, 'follow_id:' . $follow_id, 'success');
        } else {
            data_format_json(-3, '数据库写入错误');
        }
    }

    /**
     * 我的关注
     * @author kongjian
     * @param int $uid
     */
    function follow_list($uid)
    {
        $m_follow = new \app\index\model\Follow();
        $followed_uid = $m_follow->get_followed_uid_by_uid($uid);

        $range = implode(',', $followed_uid);

        $where['uid'] = ['in', $range];
        $where['status'] = 0;
        $where['is_deleted'] = 0;

        $field = 'uid,user_name,img_head,sex,school,city';

        $m_user = new \app\index\model\User();
        $list = $m_user->fetchWhere($where, $field);

        data_format_json(0, $list, 'success');
    }

}