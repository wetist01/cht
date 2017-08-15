<?php
/**
 * 传送门逻辑处理
 * User: kongjian
 * Date: 2017/8/16
 * Time: 00:55
 */

namespace app\index\service;


class Csm extends Base
{
    /**
     * 传送门列表
     */
    function csm_list()
    {
        $m_csm = new \app\index\model\Csm();
        $list = $m_csm->csm_list();
        data_format_json(0, $list, 'success');
    }

}