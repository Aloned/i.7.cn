<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;

class Member extends Base
{
	//管理员列表
    public function index()
    {
    	$admininfo = getAdminInfo(session('admin_id'));
		
		switch($admininfo['role_id']){
			case 1:
				$map = [];
			break;
			case 2:
				$map['role_id'] = 5;
			break;
		}

        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            $map['user_name'] = $_POST['keywords'];
            $this -> assign('keyword',$_POST['keywords']);
        }
		
    	//select 得到二维数组  find 得到一维数组
		$list = Db::view('admin','admin_id,role_id,store_id,user_name,true_name,tel,is_open')
				  ->view('admin_role','role_id,role_name','admin_role.role_id=admin.role_id')
				  ->view('store','store_id,store_name','store.store_id=admin.store_id')
				  ->where($map)
				  ->select();
		
		$this -> assign('list',$list);
		
    	return view();
    }
	
	//添加管理员
	public function addman(){
		//是否为POST请求
		$request = Request::instance();
		
		//获取角色列表
		$rolelist = Db::name('admin_role')->select();
		//获取店铺列表
		$storelist = Db::name('store')->where('is_show',1)->select();
		
		if($request->isPost()){
			$data = $request->param();
			if($data['bind_sn'] == 1){
                $data['uk_sn'] = md5($data['uk_sn']);
            }
			unset($data['bind_sn']);
			$data['password'] = md5($data['password']);
			$data['add_time'] = time();
			$data['last_login'] = time();
			$data['last_ip'] = $request->ip();
			
			$res = Db::name('admin')->insert($data);
			
			if($res){
				$msg = ['status'=>1,'msg'=>'添加管理员成功','url'=>url('admin/member/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加管理员失败请重试'];
			}
			
			return json($msg);
		}
		
		$this->assign('rolelist',$rolelist);
		$this->assign('storelist',$storelist);
		
		return view();
	}
	
	//编辑管理员
	public function editman(){
		//是否为POST请求
		$request = Request::instance();
		
		//获取角色列表
		$rolelist = Db::name('admin_role')->select();
		//获取店铺列表
		$storelist = Db::name('store')->where('is_show',1)->select();
		//获取管理员信息
		$manager = Db::name('admin')->where('admin_id',input('admin_id'))->find();
		
		if($request->isPost()){
			$data = $request->param();
			
			if($data['password'] != ''){
				$data['password'] = md5($data['password']);
			}else{
				$data = $request->except('password');
			}
            if($data['bind_sn'] == 1){
                if($data['uk_sn'] == '点击加载当前UKey编码'){
                    unset($data['uk_sn']);
                }else{
                    $data['uk_sn'] = md5($data['uk_sn']);
                }
            }else{
                $data['uk_sn'] = '';
            }
            unset($data['bind_sn']);
			$data['add_time'] = time();
			$data['last_login'] = time();
			$data['last_ip'] = $request->ip();
			
			$res = Db::name('admin')->update($data);
			
			if($res){
				$msg = ['status'=>1,'msg'=>'修改管理员成功','url'=>url('admin/member/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'修改管理员失败请重试'];
			}
			
			return json($msg);
		}
		
		$this->assign('manager',$manager);
		$this->assign('rolelist',$rolelist);
		$this->assign('storelist',$storelist);
		
		return view();
	}
	
	//删除管理员
	public function delman(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			// 删除管理员
	    	$res = Db::name('admin')->where('user_name',input('id'))->delete();
            Db::name('store')->where('store_tel',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除管理员成功','url'=>url('admin/member/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除管理员失败'];
			}
			
			return json($msg);
		}
	}
	
	//角色列表
	public function role(){
		$list = Db::name('admin_role')->select();
		
		$this -> assign('list',$list);
		
		return view();
	}
	
	//添加角色
	public function addrole(){
		//是否为POST请求
		$request = Request::instance();
		
		//$menulist = getMenu();
		$menulist = config('menu');
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			//把权限列表改成字符串
			$data['act_list'] = implode(',', $data['act_list']);
			$res = Db::name('admin_role')->insert($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'添加角色成功','url'=>url('admin/member/role')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加角色失败'];
			}
			return json($msg);
		}
		
		$this->assign('menulist',$menulist);
		
		return view();
	}
	
	//编辑角色
	public function editrole(){
		//是否为POST请求
		$request = Request::instance();
		
		$role_id = input('role_id');
		$role = Db::name('admin_role')->where('role_id',$role_id)->find();
		//拥有权限
		$rolelist = explode(',', $role['act_list']);
		//所有权限
		//$menulist = getMenu();
		$menulist = config('menu');
		
		foreach($menulist as $key=>$val){
			//判断数组中是否有某个元素
			if(in_array($val['id'], $rolelist)){
				$menulist[$key]['rid'] = $val['id'];
			}else{
				$menulist[$key]['rid'] = '0';
			}
			foreach($val['children'] as $key2=>$val2){
				//判断数组中是否有某个元素
				if(in_array($val2['id'], $rolelist)){
					$menulist[$key]['children'][$key2]['rid'] = $val2['id'];
				}else{
					$menulist[$key]['children'][$key2]['rid'] = '0';
				}
			}
		}
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			//把权限列表改成字符串
			$data['act_list'] = implode(',', $data['act_list']);

			$res = Db::name('admin_role')->where('role_id',$role_id)->update($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'修改角色成功','url'=>url('admin/member/role')];
			}else{
				$msg = ['status'=>0,'msg'=>'修改角色失败'];
			}
			return json($msg);
		}
		
		$this->assign('role',$role);
		$this->assign('menulist',$menulist);

		return view();
	}
	
	//删除角色
	public function delrole(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			// 删除角色
	    	$res = Db::name('admin_role')->where('role_id',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除角色成功','url'=>url('admin/member/role')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除角色失败'];
			}
			
			return json($msg);
		}
	}
}
