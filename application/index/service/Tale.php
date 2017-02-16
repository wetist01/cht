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
        $data['longitude'] = encode_coordinate($data['longitude']);
        $data['latitude'] = encode_coordinate($data['latitude']);
        $data['coordinate'] = $data['longitude'].$data['latitude'];
        $m_tale = new \app\index\model\Tale();
        if ($m_tale->allowField(true)->save($data)){
            data_format_json(0,'','创建成功');
        }else{
            data_format_json(-1,'','创建失败，请稍后重试');
        }
    }
}