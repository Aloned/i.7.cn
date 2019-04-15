<?php
namespace app\home\widget;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class Widget extends controller
{
	//头部菜单
    public function head()
    {
		$topcate = Db::name('category')->where('is_show','1')->order('sort_order asc')->column('cat_id,parent_id,cat_name,sort_order,keywords');
		
		$catelist = get_parent_list($topcate,0);

        $subforums = Db::name('parallel_session')->where('status','1')->order('id desc')->field('title,id')->select();

        $this->assign('subforums',$subforums);
		
		$this->assign('list',$catelist);

		return $this->fetch('widget/head');
    }
}
