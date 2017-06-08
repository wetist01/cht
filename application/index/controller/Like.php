<?php
/**
 * 点赞控制器
 * User: kongjian
 * Date: 2017/3/22
 * Time: 20:09
 */

namespace app\index\controller;


use think\Request;

class Like extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**
     * 点赞
     */
    function createLike()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $data['liked_uid'] = $request->param('liked_uid', 0, 'intval') or data_format_json(-1, '', 'liked_uid is null');
        $data['tale_id'] = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $data['comment_id'] = $request->param('comment_id', 0, 'intval');
        $service_like = new \app\index\service\Like();
        $service_like->create_like($data);
    }

    /**
     * 获取被点咋列表
     */
    function likedList()
    {
        $request = Request::instance();
        $liked_uid = $request->param('liked_uid', 0, 'intval') or data_format_json(-1, '', 'liked_uid is null');
        $page = $request->param('page', 1, 'positive_intval');
        $service_like = new \app\index\service\Like();
        $service_like->get_liked_list($liked_uid, $page);
    }

    /**
     * 通过被赞人的uid获取未读的被赞的数目
     */
    function likedNum()
    {
        $request = Request::instance();
        $liked_uid = $request->param('liked_uid', 0, 'intval') or data_format_json(-1, '', 'liked_uid is null');
        $service_like = new \app\index\service\Like();
        $service_like->get_liked_num($liked_uid);
    }
}