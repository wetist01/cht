<?php
/**
 * 举报逻辑处理.
 * User: kongjian
 * Date: 2017/6/8
 * Time: 23:22
 */

namespace app\index\service;


class Report extends Base
{
    /**
     * 创建举报
     * @author kongjian
     * @param $data
     */
    function create_report($data)
    {
        if ($data['reported_uid']) {

            $this->create_report_user($data['uid'], $data['reported_uid'], $data['description']);

        } elseif ($data['tale_id']) {

            $this->create_report_tale($data['uid'], $data['tale_id'], $data['description']);

        } elseif ($data['comment_id']) {

            $this->create_report_comment($data['uid'], $data['comment_id'], $data['description']);

        } else {

            data_format_json(-1, '', '未传入reported_uid,tale_id,comment_id中的任意一个');

        }

    }

    /**
     * 创建用户举报
     * @author kongjian
     * @param $uid 举报者uid
     * @param $reported_uid 被举报者uid
     * @param $description 描述
     */
    function create_report_user($uid, $reported_uid, $description)
    {
        $data['type'] = 3;
        $data['uid'] = $uid;
        $data['description'] = $description;
        $data['reported_uid'] = $reported_uid;
        $m_report = new \app\index\model\Report();
        $m_report->allowField(true)->save($data);
        $report_id = $m_report->report_id;
        if ($report_id) {
            data_format_json(0, ['report_id' => $report_id], 'success');
        } else {
            data_format_json(-2, '', '数据库错误');
        }
    }

    /**
     * 创建tale举报
     * @author kongjian
     * @param $uid 举报者uid
     * @param $tale_id 被举报的tale_id
     * @param $description 描述
     */
    function create_report_tale($uid, $tale_id, $description)
    {
        $m_tale = new \app\index\model\Tale();
        $m_report = new \app\index\model\Report();

        $data['type'] = 1;
        $data['uid'] = $uid;
        $data['tale_id'] = $tale_id;

        $is_reported = $m_report->where($data)->count();

        if ($is_reported == 0) {
            $reported_uid = $m_tale->where('tale_id', $tale_id)->value('uid');

            if ($reported_uid) {
                $data['description'] = $description;
                $data['reported_uid'] = $reported_uid;

                $m_report->allowField(true)->save($data);
                $report_id = $m_report->report_id;
                if ($report_id) {
                    data_format_json(0, ['report_id' => $report_id], 'success');
                } else {
                    data_format_json(-2, '', '数据库错误');
                }

            } else {
                data_format_json(-3, '', 'tale_id不存在');
            }
        } else {
            data_format_json(-4, '', '已经举报过了');
        }

    }

    /**
     * 创建评论举报
     * @author kongjian
     * @param $uid 举报者uid
     * @param $comment_id 被举报的评论id
     * @param $description 描述
     */
    function create_report_comment($uid, $comment_id, $description)
    {
        $data['type'] = 2;
        $data['uid'] = $uid;
        $data['description'] = $description;
        $data['comment_id'] = $comment_id;

        $m_comment = new \app\index\model\Comment();
        $comment = $m_comment->fetchWhere(['comment_id' => $comment_id], 'uid,tale_id', 1);
        $reported_uid = $comment['uid'];
        $tale_id = $comment['tale_id'];

        if ($reported_uid && $tale_id) {

            $data['reported_uid'] = $reported_uid;
            $data['tale_id'] = $tale_id;

            $m_report = new \app\index\model\Report();
            $m_report->allowField(true)->save($data);
            $report_id = $m_report->report_id;

            if ($report_id) {
                data_format_json(0, ['report_id' => $report_id], 'success');
            } else {
                data_format_json(-2, '', '数据库错误');
            }

        } else {
            data_format_json(-4, '', 'comment_id不存在');
        }
    }

}