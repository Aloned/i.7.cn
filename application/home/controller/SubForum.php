<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class SubForum extends Base
{
	//分论坛
    public function index()
    {
		return view();
    }
}
