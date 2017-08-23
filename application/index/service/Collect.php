<?php
/**
 * 收藏逻辑处理
 * User: kongjian
 * Date: 2017/4/18
 * Time: 20:55
 */

namespace app\index\service;


class Collect extends Base
{
    /**
     * 添加收藏
     * @author kongjian
     * @param array $data 传入uid和tale_id
     */
    function create_collect($data = [])
    {
        $m_collect = new \app\index\model\Collect();
        $exist_collect = $m_collect->fetchWhere($data, 'collect_id', 1);//查询是否已经收藏过

        if ($exist_collect) {
            data_format_json(-1, '', '已经收藏过');
        } else {
            $m_collect->data($data);
            $m_collect->allowField(true)->save();
            $collect_id = $m_collect->collect_id;

            data_format_json(0, ['collect_id' => $collect_id], 'success');
        }
    }

    /**
     * 取消收藏
     * @author kongjian
     * @param array $where 传入uid collect_id
     */
    function delete_collect($where = [])
    {
        $m_collect = new \app\index\model\Collect();
        $data = [
            'is_deleted' => 1,
            'delete_time' => time()
        ];
        $result = $m_collect->where($where)->update($data);

        if ($result) {
            data_format_json(0, '', 'success');
        } else {
            data_format_json(-1, '', 'error');
        }
    }

    /**
     * 获取收藏列表
     * @author kongjian
     * @param int $uid
     * @param int $page
     */
    function collect_list($uid, $page)
    {
        $m_collect = new \app\index\model\Collect();
        $tale_id = $m_collect->get_tale_id_by_uid($uid, $page);

        if ($tale_id) {
            $tale_id = implode(',', $tale_id);

            $where['tale_id'] = ['in', $tale_id];
            $where['is_deleted'] = 0;
            $where['status'] = 0;

            $m_tale = new \app\index\model\Tale();
            $tale_list = $m_tale->fetchWhere($where, '*');
            if ($tale_list) {

                foreach ($tale_list as $key => $value) {
                    $tale_list[$key]['latest_reply_time'] = getTimeDifference($value['update_time']);

                    if ($value['is_anon'] == 1) {//TODO 增加匿名用户的默认头像
                        $tale_list[$key]['user_name'] = '匿名';
                    }

                    unset($tale_list[$key]['longitude']);
                    unset($tale_list[$key]['latitude']);
                }
                data_format_json(0, $tale_list, 'success');
            } else {
                data_format_json(-2, '', 'result is empty');
            }
        } else {
            data_format_json(-3, '', 'result is empty');
        }
    }
}