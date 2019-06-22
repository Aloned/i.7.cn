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

        // 手机端跳转
        if ($this->request->isMobile()) {
            $query = $this->request->url();
            if ($query != '/') {
                header('Location:http://7.ih.cn/mobile/index');
            } else {
                header('Location:http://7.ih.cn/mobile/index');
            }
            exit;
        }

    	//select 得到二维数组  find 得到一维数组
		$website = Db::name('config') -> where('id',1) -> find();
		//报名人数
		$joinnum = Db::name('user') -> count();
		$this ->assign('joinnum',$joinnum);
		$this -> assign('website',$website);
    }

}
