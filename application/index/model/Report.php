<?php
/**
 * 举报模型.
 * User: kongjian
 * Date: 2017/6/8
 * Time:*/

namespace app\index\model;


class Report extends Base
{
    protected $pk = 'report_id';

    protected $readonly = ['type', 'uid', 'reported_uid', 'tale_id', 'comment_id', 'description'];
}