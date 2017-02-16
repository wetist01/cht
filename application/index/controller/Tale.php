<?php
/**
 * 吐槽控制器
 * User: wetist
 * Date: 2017/2/16
 * Time: 22:24
 */

namespace app\index\controller;


use think\Request;

class Tale extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = ['createtale'];//不需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function taleList()
    {

    }

    //创建吐槽接口
    function createTale()
    {
        $request = Request::instance();
        if ($request->isAjax() || $request->isGet() || $request->isPost()) {//TODO
            $data['uid'] = $request->param('uid', 0, 'intval');
            $data['longitude'] = $request->param('longitude', null);
            $data['latitude'] = $request->param('latitude', null);
            $data['description'] = $request->param('description', '');
            $data['is_anon'] = $request->param('is_anon', 0, 'intval');
            $data['type'] = $request->param('type', null, 'intval');
            $data['img'] = $request->param('img', '');
            $service_tale = new \app\index\service\Tale();
            $service_tale->create_tale($data);
        }
    }


}