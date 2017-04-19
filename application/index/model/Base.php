<?php
/**
 * User: kongjian
 * Date: 2017/4/19
 * Time: 23:54
 */

namespace app\index\model;


use think\Model;

class Base extends Model
{
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