<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Subforum extends Base
{
	//分论坛
    public function index($id)
    {
        $res = Db::name('parallel_session')->where('id ='.$id)->find();
        $this->assign('res',$res);
		return view();
    }
}
