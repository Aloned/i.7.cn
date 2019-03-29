<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Cooperation extends Base{
	//合作报名列表
    public function index()
    {
    	//获取文章总数 
    	$count = Db::name('article')->select();
    	
		$list = Db::view('article','art_id,cat_id,title,description,thumb,content,is_show,is_hot,is_recommend,rank,addtime')
						->view('category','cat_id,cat_name','article.cat_id=category.cat_id')->order('rank desc')->paginate(11,$count);
		
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }
	
	//添加文章
	public function addarticle(){
		//是否为POST请求
		$request = Request::instance();

		$cat_list = Db::name('category')->where("mod_id = 1 and parent_id = 0")->select();
		
		if($request->isPost()){
			// 排除数据库没有的字段  parent_id_1和parent_id_2
			$data = $request->except('file,image,parent_id_1,parent_id_2,addtime'); // 收集数据
			//重构 数据库字段 parent_id
            $data['cat_id'] = input('parent_id_1');
			if(input('parent_id_2')>0){
				$data['cat_id'] = input('parent_id_2');
			}
			$data['addtime'] = strtotime(input('addtime'));
			
			$res = Db::name('article')->insert($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'添加文章成功','url'=>url('admin/article/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加文章失败'];
			}
			return json($msg);
		}
		
		//当前时间
		$this->assign('curdate',time());
		$this->assign('cat_list',$cat_list);
		
		return view();
	}
	
	//编辑文章
	public function editarticle(){
		//是否为POST请求
		$request = Request::instance();
		$category = model('category');
		
		$cat_list = Db::name('category')->where("mod_id = 1 and parent_id = 0")->select();
		//获取当前分类id
		$cur_cat = Db::name('article')->where('art_id',input('param.art_id'))->column('cat_id');
		$level_cat = $category->find_parent_cat($cur_cat[0]); // 获取分类默认选中的下拉框
		
		if($request->isPost()){
			
			// 排除数据库没有的字段  parent_id_1和parent_id_2
			$data = $request->except('file,image,parent_id_1,parent_id_2,addtime'); // 收集数据
			//重构 数据库字段 parent_id
            $data['cat_id'] = input('parent_id_1');
			if(input('parent_id_2')>0){
				$data['cat_id'] = input('parent_id_2');
			}
			$data['addtime'] = strtotime(input('addtime'));
			
			$res = Db::name('article')->update($data); // 写入数据到数据库
		
			if($res){
				$msg = ['status'=>1,'msg'=>'更新文章成功','url'=>url('admin/article/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新文章失败'];
			}
			return json($msg);
		}
		//文章详情
		$article_info = Db::name('article')->where('art_id',input('art_id'))->find();
		$this->assign('article',$article_info);
		$this->assign('cat_list',$cat_list);
		$this->assign('level_cat',$level_cat);
		
		return view();
	}

	//删除文章
	public function delarticle(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			// 删除文章
	    	$res = Db::name('article')->where('art_id',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除文章成功','url'=>url('admin/article/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除文章失败'];
			}
			
			return json($msg);
		}
	}
	
	//单文件上传
	public function upload(){
		return singleUpload('article');
	}
	
	//编辑器文件上传
	public function layeditupload(){
		return editUpload('article');
	}
}
