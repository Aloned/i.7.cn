<?php
namespace app\admin\controller;

use think\Controller;
use think\Validate;
use think\Db;
use think\Request;
use think\Session;
use think\captcha\Captcha;

class Login extends Controller {

	//领票点登录
	public function index() {
		if (session('?admin_id') && session('admin_id') > 0) {
			$this->success('您已登录', 'admin/index/index');
		}

		//是否为POST请求
		$request = Request::instance();

		if ($request->isPost()) {
			//验证码
			$captcha = new Captcha();
			$code = input('post.code');
			if(!$captcha -> check($code)){
				$msg = ['status' => 1000, 'msg' => '验证码错误'];

				return json($msg);
			}

			$condition['user_name'] = input('post.user_name/s');
			$condition['password'] = input('post.password/s');
			if (!empty($condition['user_name']) && !empty($condition['password'])) {
				//md5 加密
				$condition['password'] = md5($condition['password']);

				$join = [
					['md_admin_role r','a.role_id=r.role_id'],
					['md_store s','a.store_id=s.store_id'],
				];

				$admin_info = Db::name('admin')->alias('a')->join($join)
								->where($condition)->find();

				/*var_dump($condition);
				var_dump($admin_info);*/

				if (is_array($admin_info)) {
					if($admin_info['is_open'] == '0'){
						$msg = ['status' => 1004, 'msg' => '此账号已被禁用'];
					}else{
						session('admin_id', $admin_info['admin_id']);
						session('store_id', $admin_info['store_id']);
						session('role_id', $admin_info['role_id']);
						session('act_list',$admin_info['act_list']);
						session('login_type','ticket');

						Db::name('admin') -> where("admin_id = " . $admin_info['admin_id']) -> update(['last_login' => time(), 'last_ip' => $request -> ip()]);
						session('last_login_time', $admin_info['last_login']);
						session('last_login_ip', $admin_info['last_ip']);
					    //日志内容
					    $log = [
					    	'log_time' => time(),
					    	'admin_id' => session('admin_id'),
							'log_info' => '账号密码登录',
							'log_ip' => $request->ip(),
							'log_url' => $request -> baseUrl()
					    ];
					    //添加日志
						Db::name('admin_log')->insert($log);

						$url = session('from_url') ? session('from_url') : url('admin/index/index');
						$msg = ['status' => 1001,'msg'=>'登录成功','url' => $url];
					}

					return json($msg);
				} else {
					$msg = ['status' => 1002, 'msg' => '账号或密码不正确'];
					return json($msg);
				}
			} else {
				$msg = ['status' => 1003, 'msg' => '请填写账号密码'];
				return json($msg);
			}
		}
		return view('login');
	}

	//加密锁登陆
    public function adminLogin(){
        //是否为POST请求
        $request = Request::instance();

        if ($request->isPost()) {
            $uKeySn= input('post.uKeySn');
            if($uKeySn){
                $condition['uk_sn'] = md5($uKeySn);

                $join = [
                    ['md_admin_role r','a.role_id=r.role_id'],
                    ['md_store s','a.store_id=s.store_id'],
                ];

                $admin_info = Db::name('admin')->alias('a')->join($join)
                    ->where($condition)->find();

                if (is_array($admin_info)) {
                    if($admin_info['is_open'] == '0'){
                        $msg = ['status' => 1004, 'msg' => '此账号已被禁用'];
                    }else{
                        session('admin_id', $admin_info['admin_id']);
                        session('store_id', $admin_info['store_id']);
                        session('role_id', $admin_info['role_id']);
                        session('act_list',$admin_info['act_list']);
                        session('login_type','uk');
                        cookie('uk_sn',$admin_info['uk_sn']);

                        Db::name('admin') -> where("admin_id = " . $admin_info['admin_id']) -> update(['last_login' => time(), 'last_ip' => $request -> ip()]);
                        session('last_login_time', $admin_info['last_login']);
                        session('last_login_ip', $admin_info['last_ip']);
                        //日志内容
                        $log = [
                            'log_time' => time(),
                            'admin_id' => session('admin_id'),
                            'log_info' => '加密锁登录',
                            'log_ip' => $request->ip(),
                            'log_url' => $request -> baseUrl()
                        ];
                        //添加日志
                        Db::name('admin_log')->insert($log);

                        $url = session('from_url') ? session('from_url') : url('admin/index/index');
                        $msg = ['status' => 1001,'msg'=>'登录成功','url' => $url];
                    }

                    return json($msg);
                } else {
                    $msg = ['status' => 1002, 'msg' => '该Ukey暂未绑定管理人员'];
                    return json($msg);
                }
            }else{
                $msg = ['status' => -1, 'msg' => '获取Ukey信息失败，请联系客服人员'];
                return json($msg);
            }
        }

    }

	/**
     * 退出登陆
     */
    public function logout(){
		session::clear();
		cookie('uk_sn',null);
        $msg = ['status' => 1, 'msg' => '退出成功'];
		return json($msg);
    }

	/**
     * 修改管理员密码
     * @return \think\mixed
     */
    public function editpwd(){
        $admin_id = input('admin_id/d',0);
        $oldPwd = input('old_pw/s');
        $newPwd = input('new_pw/s');
        $new2Pwd = input('new_pw2/s');

        if($admin_id){
            $info = Db::name('admin')->where("admin_id", $admin_id)->find();
            $info['password'] =  "";
            $this->assign('info',$info);
        }

		//是否为POST请求
		$request = Request::instance();

		if ($request->isPost()) {
			//修改密码
            $enOldPwd = md5($oldPwd);
            $enNewPwd = md5($newPwd);
            $admin = Db::name('admin')->where('admin_id' , $admin_id)->find();
            if(!$admin || $admin['password'] != $enOldPwd){
                $msg = ['status'=>-1,'msg'=>'旧密码不正确'];
            }else if($newPwd != $new2Pwd){
                $msg = ['status'=>-1,'msg'=>'两次密码不一致'];
            }else{
                $row = Db::name('admin')->where('admin_id' , $admin_id)->update(['password' => $enNewPwd]);
                if($row){
                	session::clear();
                    $msg = ['status'=>1,'msg'=>'修改成功,请重新登录','url'=>url('admin/login/index')];
                }else{
                    $msg = ['status'=>-1,'msg'=>'修改失败'];
                }
            }

			return json($msg);
		}

        return view();
    }

    //领票点登录
    function ticketLogin(){
        return view('ticketLogin');
    }
}
