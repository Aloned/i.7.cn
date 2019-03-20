<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Address extends Base
{
	//生成朋友圈海报
	/*public function index(){
		$image = \think\Image::open('./posters/image.jpg');
		//给图片添加文字
		$image->text('宋唐好文玩','./posters/fonts/HYQingKongTiJ.ttf',20,'#ffffff',\Think\Image::WATER_NORTH,[0,300])->save('./posters/share/'.time().'.png');
		
		return view();
	}*/
	//城市领票点
	/*public function index(){
		$list = Db::name('store')->where('is_show',1)->group('store_city')->field('store_city')->select();
		
		foreach($list as $key=>$val){
			$arr[$key]['cityname'] = $val['store_city'];
			$arr[$key]['tickets'] = Db::name('store')->where(['is_show'=>1,'store_city'=>$val['store_city']])->field('store_name,store_contact,store_tel,store_address')->select();
		}
		
		var_dump($arr);
	}*/
	//
	public function index(){
		
    	$detail = Db::name('content')->where('cat_id',6)->find();
		
		$cityid = input('id')?input('id'):1;
		/*
		//领票点列表
		$list = Db::name('store')->where('is_show',1)->group('store_city')->field('store_city')->select();
		
		foreach($list as $key=>$val){
			$arr[$key]['cityname'] = $val['store_city'];
			$arr[$key]['tickets'] = Db::name('store')->where(['is_show'=>1,'store_city'=>$val['store_city']])->field('store_name,store_contact,store_tel,store_address')->select();
		}
		
		var_dump($list);*/
		//领票城市
		$citylist = Db::name('city')->order('orderid asc')->select();
        foreach ($citylist as $key => $value){
            $list = Db::name('store')->where(['is_show'=>1,'store_city'=>$value['id']])->field('*')->select();
            $citylist[$key]['count'] = count($list);
        }
        $list = Db::name('store')->where(['is_show'=>1,'store_city'=>$cityid])->order('sort desc')->field('*')->select();
		
		$this->assign('detail',$detail);
		$this->assign('citylist',$citylist);
		$this->assign('cityid',$cityid);
		$this->assign('list',$list);
		$this->assign('nav',6);
		
		return view();
	}
}
