<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Index extends Base
{
	//首页
    public function index()
    {
        session_start();
    	//Banner
    	$banners = Db::name('banner')->where('type',1)->select();
		//参会嘉宾
		$guestList = Db::name('photo')->where('cat_id',3)->order('rank asc')->select();
		//合作伙伴
		$catmap = ['parent_id'=>7,'is_show'=>1];
		$cates = Db::name('category')->where($catmap)->field('cat_id,cat_name,sort_order')->order('sort_order asc')->select();

        //获取分论坛列表
        $subforums = Db::name('parallel_session')->find('id,title')->where('status = 1')->select();

		foreach($cates as $key=>$val){
			$list[$key]['name'] = $val['cat_name'];
			$list[$key]['children'] = Db::view('photo','id,cat_id,title,description,thumb,content,is_show,is_hot,is_recommend,rank,addtime')->where('cat_id',$val['cat_id'])->order('rank asc')->select();
		}

        $this->assign('subforums',$subforums);

		$this->assign('banners',$banners);
		$this->assign('guestList',$guestList);
		//合作伙伴
		$this->assign('partList',$list);
		$this->assign('nav',0);
		return view();
    }
}
