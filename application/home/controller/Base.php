<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class Base extends Controller {
	/*
     * 初始化操作
     */
    public function _initialize() 
    {
    	$request = Request::instance();
    	//select 得到二维数组  find 得到一维数组
		$website = Db::name('config') -> where('id',1) -> find();
		//报名人数
		$joinnum = Db::name('user') -> count();
		$this ->assign('joinnum',$joinnum);
		$this -> assign('website',$website);
    }
}
