<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Partner extends Base
{
	//新闻中心
    public function index()
    {
    	$id = input('param.id')?input('param.id'):4;
		
    	//合作伙伴
		$catmap = ['cat_id'=>$id,'is_show'=>1];
		$cates = Db::name('category')->where($catmap)->field('cat_id,cat_name')->select();
		
		$list = [];
		
		foreach($cates as $key=>$val){
			$list[$key]['name'] = $val['cat_name'];
			$list[$key]['children'] = Db::view('photo','id,cat_id,title,description,thumb,content,is_show,is_hot,is_recommend,rank,addtime')->where('cat_id',$val['cat_id'])->order('rank asc')->select();
		}
		//获取seo内容
		$seo = Db::name('category')->where('cat_id',$id)->find();
		
		$this->assign('list',$list);
		$this->assign('seo',$seo);
		$this->assign('nav',$id);
		
		return view();
    }
}
