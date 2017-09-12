<?php

namespace app\index\controller;

use think\Env;
use think\Request;

class Index extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    public function index()
    {
        return view('index');
    }

    function show()
    {
        $show = Env::get('show');
        return json($show);
    }

    function gonggao()
    {
        $text = 'i传话筒隶属于上海呆马科技有限公司，传话筒顾名思义就是给大家传递信息，我们与部分高校合作，定期发布通知信息。上海呆马信息科技公司
        成立于2016年9月，注册地上海宝山，若在使用本产品的过程中有什么意见或建议请告知我们。
        联系电话：15651079118';
        return json($text);
    }

    function test()
    {
        $request = Request::instance();
        $a = $request->param('a');
        $b = $request->param('b');
        data_format_json(geohash_encode($a, $b));
    }
}
