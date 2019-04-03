<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;
use Endroid\QrCode\QrCode;

class User extends BaseUser
{
	//个人中心
	public function my(){
		$this->assign('nav',99);
		$this->assign('nav2',1);
		return view();
	}
	
	//个人资料
	public function person(){
		$detail = Db::name('user')->where('uid',session('userid'))->find();
		$this->assign('detail',$detail);
		
		$this->assign('nav',99);
		$this->assign('nav2',2);
		return view();
	}
	
	//修改密码
	public function edit(){
		$this->assign('nav2',3);
		$this->assign('nav',99);
		return view();
	}
	
	//我的门票
	public function ticket(){
		$state = Db::name('user')->where('uid',session('userid'))->find();
		if($state['store_id']==0){ //未领票
			$flag = 0;
			$tPoint = '';
		}else{  //已领票
			$flag = 1;
			$tPoint = Db::view('user','store_id,edittime')
						->view('store','store_name,store_contact,store_tel,store_desc,store_address','user.store_id=store.store_id')
						->where('user.store_id',$state['store_id'])
						->find();
		}
		
		$this->assign('flag',$flag);
		$this->assign('point',$tPoint);
		$this->assign('nav2',3);
		$this->assign('nav',99);
		return view();
	}
	//我的邀请
	public function invite(){
		//获取当前用户邀请码
    	$ucode = Db::name('user')->where('uid',session('userid'))->value('ucode');
    	//我邀请的人
    	$list = Db::name('user')->where('ufrom',$ucode)->select();
		
		//查询前10人
        $uid = $_SESSION['think']['userid'];
        $res = Db::query("SELECT t.invited, tu.uid,tu.uname,tu.ucode FROM(
		SELECT
			tu.ufrom,
			count(1) AS invited
		FROM
			md_user tu
		WHERE
			tu.ufrom != ''
		GROUP BY
			tu.ufrom
		ORDER BY
			count(1) DESC
	) t
LEFT JOIN md_user tu ON t.ufrom = tu.ucode
ORDER BY
	t.invited DESC,
	tu.uid ASC limit 0,100;");
        $rank = 0;
        foreach ($res as $key => $value){
            if($value['uid'] == $uid){
                $rank = $key+1;
            }
        }
		
		$this->assign('sortlist',$res);
		$this->assign('list',$list);
		$this->assign('rank',$rank);
		$this->assign('nav2',3);
		$this->assign('nav',99);
		return view();
	}
	
	//分享邀请函
	public function inviteEwm(){
		$ucode = Db::name('user')->where('uid',session('userid'))->value('ucode');
		$url = Db::name('config') -> where('id',1) -> value('url');
		$inviteUrl = $url.'/mobile/online.html?ufrom='.$ucode;
		
     	$qrCode =  new QrCode();//创建生成二维码对象
     	$qrCode->setText($inviteUrl)
	     	->setSize(100)
	     	->setPadding(10)
	     	->setErrorCorrection('high')
	     	->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
	     	->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
	     	->setImageType(\Endroid\QrCode\QrCode::IMAGE_TYPE_PNG);
      
     	//>>>>>>>直接输出到浏览器>>>>>>>>>>
     	header("Content-type: image/png");
		//$qrCode->render(); //输入到浏览器
		
     	$qrCode->save('./posters/ewm/'.session('userid').'.png');
	}
	
	public function share(){
	    $id = input('id');
        $path = Db::name('template')->field('path')->where('is_show = 1 and id='.$id)->find();
		$url = Db::name('config') -> where('id',1) -> value('url');
		$uid = Db::name('user')->where('uid',session('userid'))->value('uid');

		$this->inviteEwm();
		$image = \think\Image::open('.'.$path['path']);
		//给图片添加文字
		//$image->text('宋唐好文玩','./posters/fonts/HYQingKongTiJ.ttf',20,'#ffffff',\Think\Image::WATER_NORTH,[0,300])->save('./posters/share/'.$uid.'.png');
		$image->water('./posters/ewm/'.$uid.'.png',[562,1100])->save('./posters/share/'.$uid.'.png');
		$img = $url.'/posters/share/'.$uid.'.png';
		$img = '/posters/share/'.$uid.'.png';

		$this->assign('img',$img);
		return view();
	}

	//选择模板
    public function checkTemplate(){
        $request = Request::instance();
        if($request->isPost()){
            $id = input('id');
            if($id){
                $this->redirect('/mobile/share',array("id"=>$id));
            }else{
                $this->redirect('/mobile/index');
            }
        }

	    $templateList = Db::name('template')->where('is_show = 1')->select();
        if($templateList){
            $this->assign('template',$templateList);
            return view('template');
        }else{
            $this->error('暂无模板','/mobile/index');
        }
    }
}
