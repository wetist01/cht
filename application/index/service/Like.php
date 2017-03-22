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

}