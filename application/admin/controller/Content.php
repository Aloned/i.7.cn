<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Content extends Base{
	//单页内容列表
    public function index()
    {
    	//获取单页内容总数 
    	$count = Db::name('category')->where('mod_id = 5')->select();
		$list = Db::name('category')->where('mod_id = 5')->order('sort_order asc')->paginate(11,$count);
		
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }
	
	//编辑单页内容
	public function editcontent(){
		//是否为POST请求
		$request = Request::instance();
		
		$data = $request->except('file');
		
		$category = Db::name('category')->where(['mod_id'=>5,'cat_id'=>input('param.cat_id')])->find();
		
		if($request->isPost()){
			
			$data['addtime'] = time();
			
			$res = Db::name('content')->where('cat_id',input('cat_id'))->update($data); // 写入数据到数据库
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新单页内容成功','url'=>url('admin/content/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新单页内容失败'];
			}
			return json($msg);
		}
		//单页内容详情
		$content = Db::name('content')->where('cat_id',input('cat_id'))->find();
		
		$this->assign('content',$content);
		$this->assign('category',$category);
		
		return view();
	}

	//编辑器文件上传
	public function layeditupload(){
		return editUpload('content');
	}
}
