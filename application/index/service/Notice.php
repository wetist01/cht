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
    function notice_comment($uid, $tale_uid, $comment_id, $form_id, $content, $tale_id)
    {
        if ($comment_id) {
            $m_comment = new \app\index\model\Comment();

            $parent_comment = $m_comment->where('comment_id', $comment_id)->find();
            $parent_user_name = $parent_comment['user_name'];
            $parent_uid = $parent_comment['uid'];
            $content = '回复 ' . $parent_user_name . ':' . $content;

            //给发送评论的人发消息
            $template_id1 = 'Mcf0QHSy9KLRqM8IJGmKDgwAQs9ZsfupsbTI31KNnbk';
            $page1 = 'pages/i/i';
//            echo $this->notice($uid, $template_id1, $page1, $form_id, $content);
            //给被回复的人发消息(非tale_uid)
            $template_id2 = 'GtVy1WqWSkP8ZZKNOubpMxbp5VtfCNpgJoymnYrZNj4';
            $page2 = 'pages/i/i';
            Cache::set('test',$this->notice($parent_uid,$template_id2,$page2,$form_id,$content));
        } else {

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