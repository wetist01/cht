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

    /**
     * 传送门详情
     * @author kongjian
     * @param int $csm_id
     * @param Decimal $user_long
     * @param Decimal $user_lat
     * @param int $page
     */
    function csm_tale_list($csm_id, $user_long, $user_lat, $page)
    {
        $m_csm = new \app\index\model\Csm();
        $csm_info = $m_csm->fetchWhere(['csm_id' => $csm_id], '*', 1);
        $csm_long = $csm_info['longitude'];
        $csm_lat = $csm_info['latitude'];
        $csm_near = $csm_info['near_num'];

        $m_tale = new \app\index\model\Tale();
        $tale_list = $m_tale->get_tale_list($csm_long, $csm_lat, $csm_near, 40, 3600);

        $service_tale = new \app\index\service\Tale();
        $list = $service_tale->process_tale($tale_list, $user_long, $user_lat, $page);

        if ($list) {
            data_format_json(0, $list, 'success');
        } else {
            data_format_json(-2, '', '没有数据');
        }

    }

}