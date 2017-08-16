<?php
/**
 * 传送门控制器
 * User: kongjian
 * Date: 2017/8/16
 * Time: 00:53
 */

namespace app\index\controller;


use think\Request;

class Csm extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    /**
     * 传送门列表
     */
    function csmList()
    {
        $service_csm = new \app\index\service\Csm();
        $service_csm->csm_list();
    }

    /**
     * 传送门详情
     */
    function csmTaleList()
    {
        $request = Request::instance();
        $page = $request->param('page', 1, 'positive_intval');
        $csm_id = $request->param('csm_id', 0, 'intval') or data_format_json(-1, '', 'csm_id is err');
        $user_long = $request->param('longitude', null, 'floatval') or data_format_json(-1, '', 'longitude is null');
        $user_lat = $request->param('latitude', null, 'floatval') or data_format_json(-1, '', 'latitude is null');

        $service_csm = new \app\index\service\Csm();
        $service_csm->csm_tale_list($csm_id, $user_long, $user_lat, $page);
    }
}