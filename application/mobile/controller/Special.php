<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Special extends Base
{
	//分论坛
    public function index()
    {
        //合作伙伴
        $catmap = ['cat_id'=>51,'is_show'=>1];
        $cates = Db::name('category')->where($catmap)->field('cat_id,cat_name')->select();
        foreach($cates as $key=>$val){
            $list[$key]['name'] = $val['cat_name'];
            $list[$key]['children'] = Db::view('photo','id,cat_id,title,description,thumb,content,is_show,is_hot,is_recommend,rank,addtime')->where('cat_id',$val['cat_id'])->order('rank asc')->select();
        }
        //嘉宾列表
        $guestList = Db::name('photo')->where('cat_id',50)->order('rank asc')->select();
        //联合主办
        $list1 = Db::name('photo')->where('cat_id',52)->order('rank asc')->select();
        $list2 = Db::name('photo')->where('cat_id',53)->order('rank asc')->select();
        $list3 = Db::name('photo')->where('cat_id',54)->order('rank asc')->select();
        $list4 = Db::name('photo')->where('cat_id',55)->order('rank asc')->select();
        $list5 = Db::name('photo')->where('cat_id',56)->order('rank asc')->select();
        $this->assign('guestList',$guestList);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        $this->assign('list4',$list4);
        $this->assign('list4',$list5);
        $this->assign('list',$list);
        return view();
    }
}
