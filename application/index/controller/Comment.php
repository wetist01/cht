<?php
/**
 * 评论控制器
 * User: kongjian
 * Date: 2017/3/21
 * Time: 10:21
 */

namespace app\index\controller;


use think\Request;

class Comment extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**\
     * 创建评论
     */
    function createComment()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $data['commented_uid'] = $request->param('commented_uid', 0, 'intval') or data_format_json(-1, '', 'commented_uid is null');
        $data['comment_id'] = $request->param('comment_id', 0, 'intval');
        $data['tale_id'] = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $data['content'] = $request->param('content', '') or data_format_json(-1, '', 'content is null');
        $data['is_anon'] = $request->param('is_anon', 0, 'intval');
        $data['longitude'] = $request->param('longitude', null, 'floatval') or data_format_json(-1, '', 'longitude is null');
        $data['latitude'] = $request->param('latitude', null, 'floatval') or data_format_json(-1, '', 'latitude is null');
        $service_comment = new \app\index\service\Comment();
        $service_comment->create_comment($data);
    }

    /**
     * 获取评论列表
     */
    function commentList()
    {
        $request = Request::instance();
        $data['tale_id'] = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $data['page'] = $request->param('page', 1, 'intval');
        $data['longitude'] = $request->param('longitude', null, 'floatval');
        $data['latitude'] = $request->param('latitude', null, 'floatval');
        $service_comment = new \app\index\service\Comment();
        $service_comment->get_comment_list_by_tale_id($data);
    }

    /**
     * 未读的评论数
     */
    function commentedUnreadNum()
    {
        $request = Request::instance();
        $commented_uid = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $service_comment = new \app\index\service\Comment();
        $service_comment->get_commented_unread_num($commented_uid);
    }

    /**
     * 评论我的列表
     */
    function commentedList()
    {
        $request = Request::instance();
        $commented_uid = $request->param('commented_uid', 0, 'intval') or data_format_json(-1, '', 'commented_uid is null');
        $service_comment = new \app\index\service\Comment();
        $service_comment->get_commented_list($commented_uid);
    }

    /**
     * 获取我评论的列表
     */
    function myCommentList()
    {
        $request = Request::instance();
        $uid = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $service_comment = new \app\index\service\Comment();
        $service_comment->get_my_comment_list($uid);
    }
}