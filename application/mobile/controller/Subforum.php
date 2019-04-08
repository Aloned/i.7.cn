<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Subforum extends Base
{
	//分论坛
    public function index()
    {
        $res = Db::name('parallel_session')->where('id = 1')->find();
        $this->assign('res',$res);
		return view();
    }
}
