<?php
/**
 * 用户相关逻辑处理
 * User: kongjian
 * Date: 2017/2/10
 * Time: 00:44
 */

namespace app\index\service;


use think\Cache;
use think\Db;
use common\lib\Xcrypt;

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
        $wx_open = $this->getOpenid($code);
        $openid = $wx_open->openid;

        $m_user = new \app\index\model\User();
        $where['openid'] = $openid;
        $uid = $m_user->where($where)->value('uid');
        if ($uid) {
            //已注册，走登录接口
            $this->doLogin($uid);
        } else {
            //未注册，走注册接口
            $this->register($openid);
        }
    }

    function getOpenid($code)
    {
        $appid = 'wxbfbc582268450e07';
        $secret = '2017a2e0fcc6c11927ad3e62a17d76ae';
        $grant_type = 'authorization_code';
        $js_code = $code;
        $arr = file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=" . $appid . "&secret=" . $secret . "&js_code=" . $js_code . "&grant_type=" . $grant_type);
        return json_decode($arr);
    }

    private function doLogin($uid)
    {
        $user = Db::table('cht_user')->where('uid', $uid)->find();

        //获取token
        $token_content = mt_rand(10000, 99999) . "|" . $uid . "|" . $user['mobile'] . "|" . time();
        $class_xcrypt = new Xcrypt(INTERFACE_KEY, "ofb", INTERFACE_KEY);
        $token = $class_xcrypt->encrypt($token_content);

        //token等存入缓存
        $key_token = "cht_user_auth_token_" . $uid . $user['mobile'];
        Cache::set($key_token, $token, REDIS_EXPIRE_TIME_TOKEN);
        $data = [
            'uid' => $uid,
            'token' => $token
        ];
        Db::table('cht_user')->where('uid', $uid)->setField('lastlogin_time', time());
        $result = $data;

        data_format_json(0, $result, '登录成功');
    }

    private function register($openid)
    {
        //自动生成昵称
        $name = '传话筒' . rand_number(4) . substr($openid, 7, 10);

        //注册新用户
        $user = new \app\index\model\User();
        $user->data([
            'openid' => $openid,
            'user_name' => $name
        ]);
        $user->save();
        $uid = $user->uid;
        $result = $this->doLogin($uid);

        data_format_json(0, $result, '登录成功');
    }

    /**
     * 获取微信小程序access_token
     */
    function accessTokenWxApp()
    {
        $accesstoken = Cache::get('wxapp_access_token');
        if ($accesstoken) {
            $access_token = $accesstoken;
        } else {
            $access = wxapp_access_token();
            $access_token = $access->access_token;
            Cache::set('wxapp_access_token', $access_token, 3600);
        }
        return $access_token;
    }

    /**
     * 发送微信模板消息
     */
    function template_notice($uid, $form_id, $content, $template_id, $page)
    {
        $m_user = new \app\index\model\User();
        $openid = $m_user->where('uid', $uid)->value('openid');

        $data = [
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $form_id,
            'data' => [
                'keyword1' => $content,
                'keyword2' => date('Y-m-d H:i', time())
            ]
        ];

        $access_token = $this->accessTokenWxApp();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        $data = json_encode($data);
        data_format_json(http_post($url, $data));
    }

}