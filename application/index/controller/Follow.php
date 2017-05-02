<?php
/**
 * 关注控制器
 * User: kongjian
 * Date: 2017/4/22
 * Time: 23:17
 */

namespace app\index\controller;


class Follow extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = [];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function createFollow()
    {

    }
}