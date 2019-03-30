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
            $data['created_on'] = date('Y-m-d H:i:s',time());
            $res = Db::name('cooperation')->insert($data);
            if($res){
                $this->success('您已经报名成功，工作人员稍后将与您联系!','Index/index');
            }else{
                $this->error('报名失败');
            }
        }
        $this->assign('nav',0);
		return view();
    }

    public function existPhone(){
        $phone = input('phone');
        $exist = Db::name('cooperation')->where('tel='.$phone)->find();
        if($exist){
            return json(array('code'=>1,'msg'=>'该手机号已经提交过报名信息'));
        }
        exit;
    }
}
