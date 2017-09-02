<?php
/**
 * 用户相关逻辑处理
 * User: kongjian
 * Date: 2017/2/10
 * Time: 00:44
 */

namespace app\index\service;


class User extends Base
{
    /**
     * 编辑个人资料
     * @author kongjian
     * @param int $uid
     * @param int $category
     * @param $content
     */
    function profile_edit($uid, $category, $content)
    {
        switch ($category) {
            case 1:
                $field = 'img_head';
                break;
            case 2:
                $field = 'user_name';
                $this->user_name_edit($uid, $content);
                break;
            case 3:
                $field = 'sex';
                break;
            case 4:
                $field = 'city';
                break;
            case 5:
                $field = 'school';
                break;
            case 6:
                $field = 'company';
                break;
            case 7:
                $field = 'profession';
                break;
            default:
                $field = '';
                data_format_json(-2, '', '字段错误');
        }

        $m_user = new \app\index\model\User();
        $where['uid'] = $uid;
        $data = [$field => $content];
        $m_user->allowField(true)->isUpdate(true)->save($data, $where);
        data_format_json(0, '', 'success');

    }

    /**
     * 修改用户名
     * @author kongjian
     * @param int $uid
     * @param int $content
     */
    function user_name_edit($uid, $content)
    {
        $m_user = new \app\index\model\User();
        $user_name_count = $m_user->where('user_name', $content)->count();

        if ($user_name_count == 0) {
            $m_user->allowField('user_name')->isUpdate(true)->save(['user_name' => $content], ['uid' => $uid]);
            data_format_json(0, '', 'success');
        } else {
            data_format_json(-3, '', '用户名已存在');
        }
    }

    function wxapp_login($code)
    {
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = [
            'appid' => 'wxbfbc582268450e07',
            'secret' => '2017a2e0fcc6c11927ad3e62a17d76ae',
            'js_code' => $code,
            'grant_type' => 'authorization_code'
        ];
        dump(http_post($url, json_encode($data)));
    }
}