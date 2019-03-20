<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class Login extends Base {
	//登录
    public function index()
    {
    	$request = Request::instance();
		
		if($request->isPost()){
			$map['utel'] = input('utel/s');
//			$map['password'] = input('password/s');
			if (!empty($map['utel'])) {
				//md5 加密
//				$map['password'] = md5($map['password']);
				
				$user = Db::name('user')->where($map)->find();
				
				if (is_array($user)) {
					session('userid', $user['uid']);
					$msg = ['status' => 1001,'msg'=>'登录成功','url' => url('/my')];
				} else {
					$msg = ['status' => 1002, 'msg' => '账号不正确'];
				}
			} else {
				$msg = ['status' => 1003, 'msg' => '请填写账号'];
			}
			
			return json($msg);
		}
		$this->assign('nav',99);
		
		return view('user/login');
    }
	
	/**
     * 退出登陆
     */
    public function logout(){
		session::clear();
		
        $msg = ['status' => 1, 'msg' => '退出成功'];
		return json($msg);
    }
	
	
	//忘记密码
	public function forget(){
		$this->assign('nav2',3);
		$this->assign('nav',99);
		
		return view('user/forget');
	}
}
