<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Log extends Base{
	//登录日志列表
    public function index()
    {
    	//获取文章总数 
    	$count = Db::name('admin_log')->select();
    	
		$list = Db::view('admin_log','log_info,log_ip,log_url,log_time')
						->view('admin','user_name,true_name','admin_log.admin_id=admin.admin_id')->order('rank desc')->paginate(11,$count);
		
		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }
}
