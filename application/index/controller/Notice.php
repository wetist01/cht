<?php
/**
 * 微信模板消息.
 * User: wetist
 * Date: 2017/9/11
 * Time: 05:43
 */

namespace app\index\controller;


use think\Cache;
use think\Request;

class Notice extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = ['noticecomment'];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function noticeComment()
    {
        $request = Request::instance();
        $uid = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $tale_uid = $request->param('tale_uid', 0, 'intval') or data_format_json(-1, '', 'tale_uid is null');
        $tale_id = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $comment_id = $request->param('comment_id', 0, 'intval');
        $form_id = $request->param('form_id') or data_format_json(-1, '', 'form_id is null');
        $content = $request->param('content') or data_format_json(-1, '', 'content is null');
        Cache::set('test1', $_POST);
        $service_notice = new \app\index\service\Notice();
        $service_notice->notice_comment($uid, $tale_uid, $comment_id, $form_id, $content, $tale_id);
    }
}