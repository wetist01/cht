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
        $token_allow = ['createlike'];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function createLike()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $data['tale_id'] = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $data['comment_id'] = $request->param('comment_id', 0, 'intval');
        $service_like = new \app\index\service\Like();
        $service_like->create_like($data);
    }

}