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

}