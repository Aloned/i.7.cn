<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Cache;

class Template extends Base
{
	public function index(){
		$list = Db::name('template')->select();
		
		$this->assign('list',$list);
		return view();
	}
	
	//添加Banner
	public function addbanner(){
		//是否为POST请求
		$request = Request::instance();
		
		$type = input('param.type');
		
		if($request->isPost()){
			$data = $request ->except('image');
			$res = Db::name('banner')->insert($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'添加Banner成功','url'=>url('admin/banner/index',['type'=>$type])];
			}else{
				$msg = ['status'=>0,'msg'=>'添加Banner失败'];
			}
			
			return json($msg);
		}
		
		$this->assign('type',$type);
		
		return view();
	}
	
	//编辑Banner
	public function editbanner(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->except('image');
		
		if($request->isPost()){
			$res = Db::name('banner')->where('id',$data['id'])->update($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新Banner成功','url'=>url('admin/banner/index',['type'=>$data['type']])];
			}else{
				$msg = ['status'=>0,'msg'=>'更新Banner失败'];
			}
				
			return json($msg);
		}

		$banner = Db::name('banner')->where('id',$data['id'])->find();
		$this->assign('banner',$banner);
		
		return view();
	}

	//删除Banner
	public function delBanner(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$id = input('post.id/d');
			$type = input('param.type/id');
			$res = Db::name('banner')->where('id',$id)->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除Banner成功','url'=>url('admin/banner/index',['type'=>$type])];
			}else{
				$msg = ['status'=>0,'msg'=>'删除Banner失败'];
			}
			
			return json($msg);
		}
	}
	
	//单文件上传
	public function upload(){
		return singleUpload('banner');
	}
}
