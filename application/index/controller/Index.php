<?php
namespace app\index\controller;

use app\index\model\User;
use think\Cache;
use think\Db;
use think\helper\Time;
use think\Request;

class Index extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = ['upload_img_head'];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    public function index()
    {
        return view('index');
    }

    function test()
    {
        return view('test');
    }

    //上传头像接口
    public function upload_img_head()
    {
        $request = Request::instance();
        $file = $request->file('image');
        $uid = $request->param('uid', '');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'head');
        if ($info) {
            $extension = $info->getSaveName();
            $bucket = 'cht-img';
            $object = 'head/' . $extension;
            $file = 'uploads/head/' . $extension;
            if (upload_file_oss($bucket, $object, $file)) {
                Db::table('cht_user')->where('uid', $uid)->setField('img_head', 'http://img.chuanhuatong.cc/' . $object);
                data_format_json(0, ['url' => 'http://img.chuanhuatong.cc/' . $object], '上传成功');
            }
        } else {
            echo $file->getError();
        }
    }

}
