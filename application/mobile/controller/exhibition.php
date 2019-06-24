<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Exhibition extends Base
{
	//联系我们
    public function index()
    {
		return view('exhibition');
    }

}
