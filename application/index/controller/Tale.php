<?php
/**
 * 吐槽控制器
 * User: wetist
 * Date: 2017/2/16
 * Time: 22:24
 */

namespace app\index\controller;


use think\Request;

class Tale extends Base
{
    public function _initialize($token_allow = [], $request = null)
    {
        $token_allow = ['createtale', 'deletetale'];//需要token验证的action,小写
        parent::_initialize($token_allow, $request);
    }

    function taleList()
    {

    }

    //创建吐槽接口
    function createTale()
    {
        $request = Request::instance();
        if ($request->isAjax() || $request->isGet() || $request->isPost()) {//TODO
            $data['uid'] = $request->param('uid', 0, 'intval');
            $data['longitude'] = $request->param('longitude', null) or data_format_json(-5, '', 'longitude is null');
            $data['latitude'] = $request->param('latitude', null) or data_format_json(-6, '', 'latitude is null');
            $data['description'] = $request->param('description', '');
            $data['is_anon'] = $request->param('is_anon', 0, 'intval');
            $data['type'] = $request->param('type', null, 'intval') or data_format_json(-7, '', 'type is null');
            $data['img'] = $request->param('img', 'http://img.wetist.com/head/20170216/cc8789ea850e328123bff6b6b4380ae9.JPG');
            $service_tale = new \app\index\service\Tale();
            $service_tale->create_tale($data);
        }
    }

    //删除吐槽
    function deleteTale()//TODO
    {
        $request = Request::instance();
        $where['uid'] = $request->param('uid', 0, 'intval');
        $where['tale_id'] = $request->param('tale_id', 0, 'intval');
        $service_tale = new \app\index\service\Tale();
        $service_tale->delete_tale($where);
    }

    //吐槽图片上传
    function upload_img()//TODO
    {
        $request = Request::instance();
        $file = $request->file('image');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'tale');
        if ($info) {
            $extension = $info->getSaveName();
            $bucket = 'nothave-img';
            $object = 'tale/' . $extension;
            $file = 'uploads/tale/' . $extension;
            if (upload_file_oss($bucket, $object, $file)) {
                data_format_json(0, ['image_url' => 'http://img.wetist.com/' . $object], '上传成功');
            }
        } else {
            data_format_json(-100, $file->getError(), '服务异常');
        }
    }
}