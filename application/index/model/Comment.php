<?php
/**
 * Created by PhpStorm.
 * 评论模型类
 * User: wetist
 * Date: 2017/1/17
 * Time: 10:28
 */

namespace app\index\model;

use think\Model;

class Comment extends Model
{
    protected $pk = 'comment_id';

    protected $readonly = ['uid', 'tale_id', 'create_time', 'content'];

    /**
     * 判断tale_id,comment_id是否匹配
     * @param int $tale_id
     * @param int $comment_id
     * @return int
     */
    function match_tale_id_comment_id($tale_id, $comment_id)
    {
        $where['comment_id'] = $comment_id;
        $where['tale_id'] = $tale_id;
        return $this->where($where)->count();
    }

    /**
     * 改变主表中评论数
     * @author kongjian
     * @param $comment_id
     * @param int $type 1代表自增，2代表自减
     */
    function change_comment_num($comment_id, $type = 1)
    {
        if ($type == 1) {
            $this->where('comment_id', $comment_id)->setInc('comment_num');
            $this->isUpdate(true)->save(['update_time' => time()], ['comment_id' => $comment_id]);
        } elseif ($type == 2) {
            $this->where('comment_id', $comment_id)->setDec('comment_num');
        }
    }
}