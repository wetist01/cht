<?php
/**
 * 收藏控制器
 * User: kongjian
 * Date: 2017/4/18
 * Time: 20:53
 */

namespace app\index\controller;


use think\Request;

class Collect extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**
     * 添加收藏
     */
    function createCollect()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'intval');
        $data['tale_id'] = $request->param('tale_id', 0, 'intval') or data_format_json(-1, '', 'tale_id is null');
        $service_collect = new \app\index\service\Collect();
        $service_collect->create_collect($data);
    }

    /**
     * 取消收藏
     */
    function deleteCollect()
    {
        $request = Request::instance();
        $data['uid'] = $request->param('uid', 0, 'intval') or data_format_json(-1, '', 'uid is null');
        $data['collect_id'] = $request->param('collect_id', 0, 'intval') or data_format_json(-1, '', 'collect_id is null');
        $service_collect = new \app\index\service\Collect();
        $service_collect->delete_collect($data);
    }

    /**
     * 获取收藏列表
     */
    function collectList()
    {
        $request = Request::instance();
        $uid = $request->param('uid', '0', 'intval') or data_format_json(-1, '', 'uid is null');
        $page = $request->param('page', 1, 'positive_intval');
        $service_collect = new \app\index\service\Collect();
        $service_collect->collect_list($uid, $page);
    }
}