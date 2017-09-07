<?php

namespace app\index\controller;

use think\Db;
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
        return json(1);
    }
}
