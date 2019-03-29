<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Cooperation extends Base{
	//合作报名列表
    public function index()
    {
    	$count = Db::name('cooperation')->count();
		$list = Db::name('cooperation')->order('id desc')->paginate(11,$count);
		$type = getCooperationType();

		//获取分页显示
		$page = $list->render();
		$this->assign('type',$type);
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }

	//删除文章
	public function del(){
		$request = Request::instance();
		if($request->isPost()){
	    	$res = Db::name('cooperation')->where('id',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除成功','url'=>url('admin/cooperation/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除失败'];
			}
			
			return json($msg);
		}
	}
}
