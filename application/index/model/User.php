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

    //获取用户信息
    function get_member_info($fields = "*", $where = array(), $type = 0)
    {
        if ($type == 0) {//查询单条信息
            $result = $this->field($fields)->where($where)->find();
        } else {//查询多条信息
            $result = $this->field($fields)->where($where)->select();
        }
        return $result;
    }

}