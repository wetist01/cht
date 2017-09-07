<?php

namespace app\index\controller;

use think\Env;

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
}
