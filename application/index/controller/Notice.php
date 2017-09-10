<?php
/**
 * 微信模板消息.
 * User: wetist
 * Date: 2017/9/11
 * Time: 05:43
 */

namespace app\index\controller;


use think\Request;

class Notice extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function templateNotice()
    {
        $request = Request::instance();
        $uid = $request->param('noticed_uid', 0, 'intval') or data_format_json(-1, '', 'noticed_uid is null');
        $form_id = $request->param('form_id') or data_format_json(-1, '', 'form_id is null');
        $content = $request->param('content') or data_format_json(-1, '', 'content is null');
        $template_id = $request->param('template_id') or data_format_json(-1, '', 'template_id is null');
        $page = $request->param('page') or data_format_json(-1, '', 'page is null');
        $service_notice = new \app\index\service\Notice();
        $service_notice->template_notice($uid, $form_id, $content, $template_id, $page);
    }
}