<?php
/**
 * 关注控制器
 * User: kongjian
 * Date: 2017/4/22
 * Time: 23:17
 */

namespace app\index\controller;


use think\Request;

class Follow extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**
     * 创建关注
     */
    function createFollow()
    {
        $request = Request::instance();
        $uid = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $followed_uid = $request->param('followed_uid', 0, 'intval') or data_format_json(-1, '', 'followed_uid is null');
        $service_follow = new \app\index\service\Follow();
        $service_follow->create_follow($uid, $followed_uid);
    }
}