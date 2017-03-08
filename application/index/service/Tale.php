<?php
/**
 * 吐槽逻辑处理
 * User: wetist
 * Date: 2017/2/16
 * Time: 23:19
 */

namespace app\index\service;


class Tale extends Base
{
    //创建吐槽
    function create_tale($data = [])
    {
        if ($data['type'] == 1) {
            if (!$data['description']) {
                data_format_json(-2, '', 'description is null');
            }
        } elseif ($data['type'] == 2) {
            if (!$data['img']) {
                data_format_json(-3, '', 'img is null');
            }
        } else {
            data_format_json(-4, '', 'type is error');
        }
        $data['longitude'] = encode_coordinate($data['longitude']);
        $data['latitude'] = encode_coordinate($data['latitude']);
        $data['coordinate'] = $data['longitude'] . $data['latitude'];
        $m_tale = new \app\index\model\Tale();
        if ($m_tale->allowField(true)->save($data)) {
            data_format_json(0, '', '创建成功');
        } else {
            data_format_json(-1, '', '创建失败，请稍后重试');
        }
    }

    //删除吐槽
    function delete_tale($where = [])
    {
        if ($where['tale_id']) {
            $m_tale = new \app\index\model\Tale();
            $data['is_deleted'] = 1;
            $data['delete_time'] = time();
            if ($m_tale->allowField(true)->save($data, $where)) {
                data_format_json(0, '', '删除成功');
            } else {
                data_format_json(-2, '', '删除失败，请稍后重试');
            }
        } else {
            data_format_json(-1, '', '请传入正确的tale_id');
        }
    }
}