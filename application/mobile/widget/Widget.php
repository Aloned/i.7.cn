<?php
namespace app\mobile\widget;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Widget extends controller
{
	//头部菜单
    public function foot()
    {
		$subforums = Db::name('parallel_session')->where('status','1')->order('id desc')->field('title,id')->select();
		
		$this->assign('subforums',$subforums);

		return $this->fetch('widget/foot');
    }
}
