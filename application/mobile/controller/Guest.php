<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Guest extends Base
{
	//客户案例
    public function index()
    {
    	$id = input('param.id')?input('param.id'):3;
		$map = ['cat_id'=>$id];
    	//获取文章总数 
    	$count = Db::name('photo')->where($map)->count();
    	$list = Db::name('photo')->where($map)->paginate(100,$count);
		// 获取分页显示
		$page = $list->render();
		
		$this->assign('list',$list);
		$this->assign('pager',$page);
		//获取seo内容
		$seo = Db::name('category')->where('cat_id',$id)->find();
		
		$this->assign('seo',$seo);
		$this->assign('nav',$id);
		
		return view();
    }
}
