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


}