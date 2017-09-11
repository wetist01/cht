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
    function notice_comment($uid, $tale_uid, $comment_id, $form_id, $content)
    {
        $m_user = new \app\index\model\User();
        $m_user->isUpdate(true)->save(['form_id' => $form_id], ['uid' => $uid]);
        $template_id = 'GtVy1WqWSkP8ZZKNOubpMxbp5VtfCNpgJoymnYrZNj4';
        $page = 'pages/i/i';

        if ($comment_id) {
            $m_comment = new \app\index\model\Comment();

            $parent_comment = $m_comment->where('comment_id', $comment_id)->find();
            $parent_user_name = $parent_comment['user_name'];
            $parent_uid = $parent_comment['uid'];
            $content = '回复 ' . $parent_user_name . ':' . $content;

            $form_id1 = $m_user->where(['uid' => $parent_uid])->value('form_id');

            Cache::set('test', $this->notice($parent_uid, $template_id, $page, $form_id1, $content));
        } else {
            $form_id1 = $m_user->where(['uid' => $tale_uid])->value('form_id');
            Cache::set('test1', $this->notice($uid, $template_id, $page, $form_id1, $content));
        }
    }

    function notice($uid, $template_id, $page, $form_id, $content)
    {
        $m_user = new \app\index\model\User();
        $openid = $m_user->where('uid', $uid)->value('openid');

        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => [
                'keyword1' => [
                    'value' => $content,
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => date('Y-m-d H:i', time()),
                    'color' => '#173177'
                ]
            ]
        ];

        $access_token = wxapp_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $data = json_encode($data);
        return http_post($url, $data);
    }
}