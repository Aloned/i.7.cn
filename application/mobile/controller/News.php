<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class News extends Base
{
	//新闻中心
    public function index()
    {
    	$id = input('param.id')?input('param.id'):5;
		$map = ['cat_id'=>$id];
    	//获取文章总数 
    	$count = Db::name('article')->where($map)->count();
    	$list = Db::name('article')->where($map)->paginate(10,$count);
		// 获取分页显示
		$page = $list->render();
		
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
		//获取seo内容
		$seo = Db::name('category')->where('cat_id',$id)->find();
		
		$this->assign('seo',$seo);
		$this->assign('nav',5);
		
		return view();
    }
	//详情页
	public function detail()
    {
    	$id = input('id');
		
    	$detail = Db::name('article')->where('art_id',$id)->find();
		
		$this->assign('detail',$detail);
		$this->assign('nav',5);
		return view();
    }
}
