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

		$this->assign('status',array('','显示','隐藏'));
		$this->assign('list',$list);
		return view();
	}
	
	//添加
	public function add(){
		//是否为POST请求
		$request = Request::instance();
		
		if($request->isPost()){
			$data = $request ->except('image');
			$res = Db::name('template')->insert($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'添加成功','url'=>url('admin/template/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加失败'];
			}
			
			return json($msg);
		}
		
		return view();
	}
	
	//编辑
	public function edit(){
		//是否为POST请求
		$request = Request::instance();
		$data = $request ->except('image');
		
		if($request->isPost()){
			$res = Db::name('template')->where('id',$data['id'])->update($data);
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新成功','url'=>url('admin/template/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新失败'];
			}
				
			return json($msg);
		}

        $res = Db::name('template')->where('id',$data['id'])->find();
		$this->assign('res',$res);
		
		return view();
	}

	//删除Banner
	public function del(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$id = input('post.id/d');
			$res = Db::name('template')->where('id',$id)->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除成功','url'=>url('admin/template/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'删除失败'];
			}
			
			return json($msg);
		}
	}
	
	//单文件上传
	public function upload(){
		return singleUpload('template');
	}
}
