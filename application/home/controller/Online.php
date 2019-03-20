<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Online extends Base
{
	//在线报名
    public function index()
    {
    	$this->assign('nav',0);
		return view();
    }
	//第二步
	public function apply(){
		
		$this->assign('nav',0);
		return view();
	}
}
