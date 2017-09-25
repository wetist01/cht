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
        $m_tale = new \app\index\model\Tale();

        if ($csm_id == 14) {
            $tale_list = $m_csm->face_match();
            $top = $m_tale->get_top();
            if ($top) {
                $tale_list = array_unshift($tale_list, $top);
            }
        } else {
            $csm_info = $m_csm->fetchWhere(['csm_id' => $csm_id], '*', 1);
            $csm_long = $csm_info['longitude'];
            $csm_lat = $csm_info['latitude'];
            $csm_near = $csm_info['near_num'];

            $tale_list = $m_tale->get_tale_list($csm_long, $csm_lat, $csm_near, 40, 5);
        }

        $service_tale = new \app\index\service\Tale();
        $list = $service_tale->process_tale($tale_list, $user_long, $user_lat, $page);

        if ($list) {
            data_format_json(0, $list, 'success');
        } else {
            data_format_json(-2, '', '没有数据');
        }

    }

}