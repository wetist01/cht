<?php
/**
 * 点赞逻辑处理
 * User: kongjian
 * Date: 2017/3/22
 * Time: 20:11
 */

namespace app\index\service;


class Like extends Base
{
    /**
     * 创建点赞
     * @param array $data
     */
    function create_like($data = [])
    {
        if (empty($data)) {
            data_format_json(-1, '', 'data is empty');
        }

        if ($data['comment_id'] == 0) {
            $this->create_tale_like($data);
        } else {
            $this->create_comment_like($data);
        }
    }

    /**
     * 给吐槽点赞
     * @param $data
     */
    function create_tale_like($data)
    {
        $m_like = new \app\index\model\Like();
        $is_liked = $m_like->where($data)->count();

        if ($is_liked == 0) {//判断是否点赞过

            $m_user = new \app\index\model\User();
            $user_info = $m_user->where('uid', $data['uid'])->find();
            $data['img_head'] = $user_info['img_head'];
            $data['user_name'] = $user_info['user_name'];

            $is_save = $m_like->allowField(true)->save($data);

            if ($is_save) {//判断是否写入数据库成功
                $m_tale = new \app\index\model\Tale();
                $like_num = $m_tale->change_tale_like_num($data['tale_id']);
                if ($like_num) {
                    data_format_json(0, 'like_num:' . $like_num, 'success');
                } else {
                    data_format_json(-2, '', '无法获取like_num');
                }
            } else {
                data_format_json(-1, '', 'mysql insert fail');
            }
        } else {
            data_format_json(-3, '', '已经点赞过了');
        }
    }

    /**
     * 给评论点赞
     * @param $data
     */
    function create_comment_like($data)
    {
        $m_like = new \app\index\model\Like();
        $is_liked = $m_like->where($data)->count();

        if ($is_liked == 0) {//判断是否点赞过

            $m_user = new \app\index\model\User();
            $user_info = $m_user->where('uid', $data['uid'])->find();
            $data['img_head'] = $user_info['img_head'];
            $data['user_name'] = $user_info['user_name'];

            $is_save = $m_like->allowField(true)->save($data);

            if ($is_save) {//判断是否写入数据库成功
                $m_comment = new \app\index\model\Comment();
                $like_num = $m_comment->change_comment_like_num($data['comment_id']);
                if ($like_num) {
                    data_format_json(0, 'like_num:' . $like_num, 'success');
                } else {
                    data_format_json(-2, '', '无法获取like_num');
                }
            } else {
                data_format_json(-1, '', 'mysql insert fail');
            }
        } else {
            data_format_json(-3, '', '已经点赞过');
        }
    }

    /**
     * 获取被点赞的列表
     * @author kongjian
     * @param int $liked_uid
     * @param int $page
     */
    function get_liked_list($liked_uid = 0, $page = 1)
    {
        $m_like = new \app\index\model\Like();
        $where['liked_uid'] = $liked_uid;
        $where['is_deleted'] = 0;
        $list = $m_like->where($where)->field('uid,user_name,img_head,tale_id,create_time')->order('create_time', 'desc')->page($page, 20)->select();
        $list = jsonToArray($list);
        foreach ($list as $key => $val) {
            $list[$key]['create_time'] = getTimeDifference($val['create_time']);
        }

        $m_like->isUpdate(true)->save(['is_read' => 1], ['liked_uid' => $liked_uid]);

        data_format_json(0, $list, 'success');
    }

    /**
     * 通过被赞人的uid获取未读的被赞的数目
     * @param $liked_uid
     */
    function get_liked_num($liked_uid)
    {
        $m_like = new \app\index\model\Like();
        $where['liked_uid'] = $liked_uid;
        $where['is_read'] = 0;
        $where['is_deleted'] = 0;
        $liked_num = $m_like->where($where)->count();
        data_format_json(0, $liked_num, 'success');
    }
}