<?php
/**
 * 评论逻辑处理
 * User: kongjian
 * Date: 2017/3/21
 * Time: 10:25
 */

namespace app\index\service;


use think\Cache;

class Comment extends Base
{
    /**
     * 创建评论
     * @author kongjian
     * @param array $data 传入uid,tale_id,content,comment_id
     */
    function create_comment($data = [])
    {
        if (empty($data)) {
            data_format_json(-100, '', 'data is empty');
        }
        $m_user = new \app\index\model\User();
        $user_info = $m_user->fetchWhere(['uid' => $data['uid']], 'name,sex,img_head', 1);
        $data['user_name'] = $user_info['user_name'];
        $data['img_head'] = $user_info['img_head'];
        $data['sex'] = $user_info['sex'];

        if ($data['comment_id'] == 0) {//创建一级评论
            $this->create_comment_tale($data);
        } else {//回复评论(二级评论)
            $this->create_comment_comment($data);
        }

    }

    /**
     * 直接给tale评论
     * @author kongjian
     * @param $data
     */
    function create_comment_tale($data)
    {
        $m_comment = new \app\index\model\Comment();
        $result = $m_comment->allowField(true)->save($data);

        if ($result) {
            $m_tale = new \app\index\model\Tale();
            $m_tale->change_comment_num($data['tale_id']);//改变tale表中的评论数以及更新时间
            Cache::clear('comment_list_tale_' . $data['tale_id']);
            data_format_json(0, 'comment_id:' . $m_comment->comment_id, 'success');
        } else {
            data_format_json(-101, '', 'mysql insert fail');
        }
    }

    /**
     * 回复评论
     * @author kongjian
     * @param $data
     */
    function create_comment_comment($data)
    {
        $parent_comment_id = $data['comment_id'];
        unset($data['comment_id']);
        $m_comment = new \app\index\model\Comment();
        $is_match = $m_comment->match_tale_id_comment_id($data['tale_id'], $parent_comment_id);

        if ($is_match) {
            $data['parent_comment_id'] = $parent_comment_id;

            $parent_comment = $m_comment->where('comment_id', $parent_comment_id)->find();
            $parent_user_name = $parent_comment['user_name'];
            $data['content'] = '回复 ' . $parent_user_name . ':' . $data['content'];

            $result = $m_comment->allowField(true)->save($data);
            $comment_id = $m_comment->comment_id;

            if ($result) {
                $m_tale = new \app\index\model\Tale();
                $m_tale->change_comment_num($data['tale_id']);//改变tale表中的评论数以及更新时间
                $m_comment->change_comment_num($parent_comment_id);//改变comment表中被评论的那条记录的评论数
                Cache::clear('comment_list_tale_' . $data['tale_id']);
                data_format_json(0, 'comment_id:' . $comment_id, 'success');
            } else {
                data_format_json(-101, '', 'mysql insert fail');
            }

        } else {
            data_format_json(-1, '', 'tale_id,comment_id is not matched');
        }
    }

    /**
     * 根据tale_id获取评论列表
     * @author kongjian
     * @param array $data
     */
    function get_comment_list_by_tale_id($data = [])
    {
        $m_comment = new \app\index\model\Comment();
        $result = $m_comment->get_comment_list_by_tale_id($data['tale_id'], $data['page']);

        foreach ($result as $key => $val) {
            $result[$key]['distance'] = getDistance($data['longitude'], $data['latitude'], $val['longitude'], $val['latitude']);
        }

        data_format_json(0, $result, 'success');
    }

}