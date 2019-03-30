<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Cooperation extends Base
{
    public function index()
    {
        //是否为POST请求
        $request = Request::instance();
        if($request->isPost()){
            $data = $request->param();
            $res = Db::name('cooperation')->insert($data);
            if($res){
                $msg = ['status'=>1,'msg'=>'报名成功','url'=>url('home/index')];
            }else{
                $msg = ['status'=>0,'msg'=>'网络繁忙,请稍后再试'];
            }
            return json($msg);
        }
		return view();
    }
}
