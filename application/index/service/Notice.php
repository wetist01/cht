<?php
/**
 * 微信模板消息.
 * User: wetist
 * Date: 2017/9/11
 * Time: 05:44
 */

namespace app\index\service;


use think\Cache;

class Notice extends Base
{
    function template_notice($uid, $form_id, $content, $template_id, $page)
    {
        $m_user = new \app\index\model\User();
        $openid = $m_user->where('uid', $uid)->value('openid');

        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => [
                '评论内容' => [
                    'value' => $content,
                    'color' => '#173177'
                ],
                '评论时间' => [
                    'value' => date('Y-m-d H:i', time()),
                    'color' => '#173177'
                ]
            ]
        ];

        $access_token = wxapp_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        Cache::set('test',$data);
        $data = json_encode($data);
        data_format_json(http_post($url, $data));
    }
}