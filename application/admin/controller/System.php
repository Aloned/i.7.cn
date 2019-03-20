<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Cache;

class System extends Base
{
	//首页
    public function index()
    {
    	//select 得到二维数组  find 得到一维数组
		$config = Db::name('config') -> where('id',1) -> find();
		$this -> assign('config',$config);
		
    	return view();
    }
	
	//更新网站设置
	public function updateConfig(){
		$request = Request::instance();
		
		if($request -> isPost()){
			$data = $request ->except('image');
			
			$res = Db::name('config') -> where('id',1) -> update($data);
			
			if($res){
				$msg = ['status' => 1,'msg'=>'更新成功','url'=>''];
			}else{
				$msg = ['status' => 0,'msg'=>'更新失败'];
			}
			
			return json($msg);
		}
	}
	
	//友情链接列表
	public function linklist(){
		//获取友情链接总数
		$count = Db::name('friend_link')->count();
		//获取友情链接列表 
		$list = Db::name('friend_link')->order('orderby desc')->paginate(11,$count);
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
		return view();
	}
	
	//编辑友情链接
	public function editlink(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->except('image');
		
		if($request->isPost()){
			$res = Db::name('friend_link')->where('link_id',$data['link_id'])->update($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新友情链接成功','url'=>url('admin/system/linklist')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新友情链接失败'];
			}
				
			return json($msg);
		}
		
		$link_info = Db::name('friend_link')->where('link_id',$data['link_id'])->find();
		$this->assign('link',$link_info);
		
		return view();
	}
	
	//添加友情链接
	public function addlink(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->except('image');
		
		if($request->isPost()){
			$res = Db::name('friend_link')->insert($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'添加友情链接成功','url'=>url('admin/system/linklist')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加友情链接失败'];
			}
			
			return json($msg);
		}
		
		return view();
	}

	//友情链接删除
	public function delLink(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$link_id = input('post.id/d');
			$res = Db::name('friend_link')->where('link_id',$link_id)->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除友情链接成功','url'=>url('admin/system/linklist')];
			}else{
				$msg = ['status'=>0,'msg'=>'删除友情链接失败'];
			}
			
			return json($msg);
		}
	}
	
	//城市列表
	public function citylist(){
		//获取友情链接总数
		$count = Db::name('city')->count();
		//获取友情链接列表 
		$list = Db::name('city')->order('orderid asc')->paginate(11,$count);
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
		return view();
	}
	
	//编辑城市
	public function editcity(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->param();
		
		if($request->isPost()){
			$res = Db::name('city')->where('id',$data['id'])->update($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新城市成功','url'=>url('admin/system/citylist')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新城市失败'];
			}
				
			return json($msg);
		}
		
		$city = Db::name('city')->where('id',$data['id'])->find();
		$this->assign('city',$city);
		
		return view();
	}
	
	//添加友情链接
	public function addcity(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->param();
		
		if($request->isPost()){
			$res = Db::name('city')->insert($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'添加城市成功','url'=>url('admin/system/citylist')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加城市失败'];
			}
			
			return json($msg);
		}
		
		return view();
	}

	//友情链接删除
	public function delcity(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$id = input('post.id/d');
			$res = Db::name('city')->where('id',$id)->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除城市成功','url'=>url('admin/system/citylist')];
			}else{
				$msg = ['status'=>0,'msg'=>'删除城市失败'];
			}
			
			return json($msg);
		}
	}
	
	//城市列表
	public function message(){
		//获取友情链接总数
		$count = Db::name('message')->count();
		//获取友情链接列表 
		$list = Db::name('message')->paginate(11,$count);
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
		return view();
	}
	
	//编辑城市
	public function editmsg(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->param();
		
		if($request->isPost()){
			$res = Db::name('message')->where('id',$data['id'])->update($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新接口成功','url'=>url('admin/system/message')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新接口失败'];
			}
				
			return json($msg);
		}
		
		$message = Db::name('message')->where('id',$data['id'])->find();
		$this->assign('message',$message);
		
		return view();
	}
	
	//添加友情链接
	public function addmsg(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->param();
		
		if($request->isPost()){
			$res = Db::name('message')->insert($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'添加接口成功','url'=>url('admin/system/message')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加接口失败'];
			}
			
			return json($msg);
		}
		
		return view();
	}

	//友情链接删除
	public function delmsg(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$id = input('post.id/d');
			$res = Db::name('message')->where('id',$id)->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除接口成功','url'=>url('admin/system/message')];
			}else{
				$msg = ['status'=>0,'msg'=>'删除接口失败'];
			}
			
			return json($msg);
		}
	}
	
	/**
     * 清空系统缓存
     */
    public function cleanCache(){              
        delFile(RUNTIME_PATH);
		Cache::clear(); 
		
		return json(['status'=>1,'msg'=>'清理缓存成功！']);
    }
	
	//单文件上传
	public function upload(){
		return singleUpload('logo');
	}
}
