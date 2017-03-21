<?php
/**
 * 评论逻辑处理
 * User: wetist
 * Date: 2017/3/21
 * Time: 10:25
 */

namespace app\index\service;


class Comment extends Base
{
    /**
     * 创建评论
     * @author kongjian
     * @param array $data 传入uid,tale_id,content
     */
    function create_comment($data = [])
    {
        if (empty($data)) {
            data_format_json(-100, '', 'data is empty');
        }
        $m_comment = new \app\index\model\Comment();
        $result = $m_comment->allowField(true)->save($data);
        if ($result) {
            $m_tale = new \app\index\model\Tale();
            $m_tale->change_comment_num($data['tale_id']);
            data_format_json(0, 'comment_id:' . $m_comment->comment_id, 'success');
        } else {
            data_format_json(-101, '', 'mysql insert fail');
        }
    }

}