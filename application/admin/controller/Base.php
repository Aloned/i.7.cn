<?php
namespace app\admin\controller;

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
    	// 判断是否已登录
    	if(session('admin_id') == null ){
    		$this->error('请先登录','login/index');
    	}
		//拥有权限
		$rolelist = explode(',', session('act_list'));
		
		$id = getaccess($request->url());
		
		if(!empty($id)){
			if(!in_array($id, $rolelist)){
				$this->error('您没有访问权限','admin/index/index');
			}
		}
		
		$siteurl = Db::name('config')->where('id',1)->value('url');
		$this->assign('siteurl',$siteurl);
    }
}
