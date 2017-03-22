<?php
/**
 * 点赞模型
 * User: kongjian
 * Date: 2017/3/22
 * Time: 20:12
 */

namespace app\index\model;


use think\Model;

class Like extends Model
{
    protected $pk = 'like_id';

    protected $readonly = ['uid', 'tale_id', 'comment_id', 'create_time',];
}