<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class Image extends Base
{
	//生成朋友圈海报
	/*public function index(){
		$image = \think\Image::open('./posters/image.jpg');
		//给图片添加文字
		$image->text('宋唐好文玩','./posters/fonts/HYQingKongTiJ.ttf',20,'#ffffff',\Think\Image::WATER_NORTH,[0,300])->save('./posters/share/'.time().'.png');
		
		return view();
	}*/
	
	//
	public function index(){
		
	}
}
