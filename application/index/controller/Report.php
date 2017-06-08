<?php
/**
 * 举报控制器.
 * User: kongjian
 * Date: 2017/6/8
 * Time: 23:20
 */

namespace app\index\controller;


use think\Request;

class Report extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**
     * 创建举报
     */
    function createReport()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'positive_intval') or data_format_json(-1, '', 'uid is null');
        $data['reported_uid'] = $request->param('reported_uid', 0, 'positive_intval');
        $data['tale_id'] = $request->param('tale_id', 0, 'positive_intval');
        $data['comment_id'] = $request->param('comment_id', 0, 'positive_intval');
        $data['description'] = $request->param('description', '');
        $service_report = new \app\index\service\Report();
        $service_report->create_report($data);
    }
}