<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;

class BaseUser extends Controller {
	/*
     * 初始化操作
     */
    public function _initialize() 
    {
    	if(session('userid') == null ){
    		$this->redirect(url('/sign'));
    	}else{
    		$user = Db::name('user')->where('uid',session('userid'))->find();
			$this -> assign('user',$user);
    	}
		$website = Db::name('config') -> where('id',1) -> find();
		//报名人数
		$joinnum = Db::name('user') -> count();
		$this ->assign('joinnum',$joinnum);
		$this -> assign('website',$website);
    }
}
