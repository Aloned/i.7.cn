<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Page extends Base
{
	//联系我们
    public function index()
    {
    	$id = input('id');
    	$detail = Db::name('content')->where('cat_id',$id)->find();
		
		$this->assign('detail',$detail);
		$this->assign('nav',$id);
		
		return view();
    }
}
