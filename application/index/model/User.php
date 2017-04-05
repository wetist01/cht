<?php
/**
 * 用户模型类
 * User: kongjian
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
        $sex = [0 => '女', 1 => '男', 2 => '未知'];
        return $sex[$value];
    }

    protected function setIpAttr()
    {
        return request()->ip();
    }

    /**
     * 查询公共方法
     * @author kongjian
     * @param null $where
     * @param string $field
     * @param int $type
     * @return array|false|\PDOStatement|string|\think\Collection|Model
     */
    function fetchWhere($where = null, $field = '*', $type = 0)
    {
        if ($type == 0) {
            $result = $this->where($where)->field($field)->select();
        } else {
            $result = $this->where($where)->field($field)->find();
        }
        return $result;
    }

}