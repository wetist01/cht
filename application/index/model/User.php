<?php
/**
 * Created by PhpStorm.
 * 用户模型类
 * User: wetist
 * Date: 2017/1/17
 * Time: 10:28
 */

namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $pk = 'uid';

    protected $insert = ['ip'];

    public function getSexAttr($value)
    {
        $sex = [0 => '保密', 1 => '男', 2 => '女'];
        return $sex[$value];
    }

    protected function setIpAttr()
    {
        return request()->ip();
    }

}