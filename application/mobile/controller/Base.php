<?php
namespace app\mobile\controller;

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
        //微信端屏蔽
        if($this->is_weixin()){
//            echo '网站筹备中...';die;
        }
    	$request = Request::instance();
    	//select 得到二维数组  find 得到一维数组
		$website = Db::name('config') -> where('id',1) -> find();
		//报名人数
		$joinnum = Db::name('user') -> count();
		$this ->assign('joinnum',$joinnum);
		$this -> assign('website',$website);
    }

    private function is_weixin() {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } return false;
    }
}
