<?php
/**
 * 用户模型类
 * User: kongjian
 * Date: 2017/1/17
 * Time: 10:28
 */

namespace app\index\model;


class User extends Base
{
    protected $pk = 'uid';

    protected $insert = ['ip'];

    protected function setIpAttr()
    {
        return request()->ip();
    }

}