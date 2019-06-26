<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;

class User extends BaseUser
{
	//个人中心
	public function my(){
		//获取当前用户邀请码
    	$ucode = Db::name('user')->where('uid',session('userid'))->value('ucode');
    	//我邀请的人
    	$list = Db::name('user')->where('ufrom',$ucode)->select();
        $uid = $_SESSION['think']['userid'];
		//查询所有人
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
LEFT JOIN md_user tu ON t.ufrom = tu.ucode where tu.uid is not null
ORDER BY
	t.invited DESC,
	tu.uid ASC limit 0,100;");
//        if(isset($_SESSION['think']['sortArr'.$uid])){
//            $sortlist = json_decode($_SESSION['think']['sortArr'.$uid],true);
//        }else{
//            $all = Db::name('user')->select();
//            foreach($all as $key=>$val){
//                $mylist[$key] = ['uid'=>$val['uid'],'name'=>$val['uname'],'invite'=>inviteNumber($val['ucode']),'invited'=>invitedNumber($val['ucode'])];
//            }
//            $sortlist = sortArr($mylist,'invite',SORT_DESC,SORT_NUMERIC);
//            $_SESSION['think']['sortArr'.$uid] = json_encode($sortlist);
//        }

        $ranking = 0;
//        foreach ($sortlist as $key => $value){
//            if($value['uid'] == $uid){
//                $ranking = $key+1;
//            }
//        }
        $detail = Db::name('user')->where('uid',session('userid'))->find();
        $this->assign('detail',$detail);
		$this->assign('sortlist',$res);
		$this->assign('list',$list);
		$this->assign('ranking',3);
		$this->assign('nav',99);
		$this->assign('nav2',1);
		$this->assign('arr',['未领票','已领票']);
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
}
