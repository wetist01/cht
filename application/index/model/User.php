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

    public function getSexAttr($value)
    {
        $sex = [0 => '女', 1 => '男', 2 => '未知'];
        return $sex[$value];
    }

    protected function setIpAttr()
    {
        return request()->ip();
    }

}