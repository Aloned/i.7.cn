<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;

class User extends Base
{
	//用户列表
    public function index()
    {
    	$admininfo = getAdminInfo(session('admin_id'));
		//关键字
		$keywords = input('keywords');
		$search_name = input('search_name');
		
    	switch($admininfo['role_id']){
			case 1:
				if($keywords){
					$map['utel'] = ['like','%'.$keywords.'%'];
				}elseif($search_name){
					$map['uname'] = ['like','%'.$search_name.'%'];
				}else{
					$map = '';
				}
			break;
			case 2:
				if($keywords){
					$map['utel'] = $keywords;
				}elseif($search_name){
					$map['uname'] = $search_name;
				}else{
					$map['utel'] = 'aaa';
				}
			break;
			default:
				if($keywords){
					$map['utel'] = $keywords;
				}elseif($search_name){
					$map['uname'] = $search_name;
				}else{
					$map['utel'] = 'aaa';
				}
			break;
		}
//		var_dump($map);die;
		
    	//select 得到二维数组  find 得到一维数组
    	$count = Db::name('user')->where($map)->count();
    	
		$list = Db::name('user')->where($map)->paginate(21,$count);
		$page = $list->render();
		
		$this -> assign('list',$list);
		$this -> assign('roleId',$admininfo['role_id']);
		$this->assign('pager',$page);
		
    	return view();
    }
	
	//添加会员
	public function add(){
		//是否为POST请求
		$request = Request::instance();
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			$data['addtime'] = time();
			$data['edittime'] = time();
			$data['ip'] = $request->ip();
			$password = mt_rand(100000,999999);
			$data['password'] = md5($password);
			//判断数据库中是否已存在该邀请码
			$randomstr = randomkeys(5);
			$code = Db::name('user')->where('ucode',$randomstr)->value('ucode');
			
			if($code){
				$data['ucode'] = randomkeys(5);
			}else{
				$data['ucode'] = $randomstr;
			}
			
			$res = Db::name('user')->insert($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'添加会员成功','url'=>url('admin/user/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加会员失败'];
			}
			return json($msg);
		}
		
		return view();
	}
	
	//编辑会员
	public function edit(){
		//是否为POST请求
		$request = Request::instance();
		
		$uid = input('uid');
		$user = Db::name('user')->where('uid',$uid)->find();
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			$data['edittime'] = time();
			
			if($data['is_show'] == 1){
				$data['store_id'] = session('store_id');
                $tot = Db::name('store')->field('store_amount')->where('store_id',$data['store_id'])->find();
                $count = specTicket($data['store_id']);
                if((intval($tot['store_amount']) - $count) <= 0){
                    $msg = ['status'=>0,'msg'=>'领票点没有余票'];
                    return json($msg);
                }
			}
			
			$res = Db::name('user')->update($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'修改会员成功','url'=>url('admin/user/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'修改会员失败'];
			}
			return json($msg);
		}
        $admininfo = getAdminInfo(session('admin_id'));
        $this -> assign('roleId',$admininfo['role_id']);
		$this->assign('user',$user);
		
		return view();
	}
	
	//删除角色
	public function del(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			// 删除文章
	    	$res = Db::name('user')->where('uid',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除会员成功','url'=>url('admin/user/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除会员失败'];
			}
			
			return json($msg);
		}
	}
	
	//导出报名数据
	public function export()
    {
        $list = Db::name('user')->select();
		
    	$strTable ='<table width="500" border="1">';
    	$strTable .= '<tr>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">姓名</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">电话</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="150">公司</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">职位</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">票号</td>';
		$strTable .= '<td style="text-align:center;font-size:12px;" width="*">邀请码</td>';
		$strTable .= '<td style="text-align:center;font-size:12px;" width="150">身份证</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">微信号</td>';
		$strTable .= '<td style="text-align:center;font-size:12px;" width="*">QQ</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">是否领票</td>';
    	$strTable .= '<td style="text-align:center;font-size:12px;" width="*">报名时间</td>';
    	$strTable .= '</tr>';
	    if(is_array($list)){
	    	foreach($list as $k=>$val){
	    		$strTable .= '<tr>';
	    		$strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['uname'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['utel'].' </td>';	    		
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['ucompany'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['uposition'].' </td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['uticket'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['ucode'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['unumber'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['uweixin'].'</td>';
				$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['uqq'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.$val['is_show'].'</td>';
	    		$strTable .= '<td style="text-align:left;font-size:12px;">'.date("Y-m-d H:i:s",$val['addtime']).'</td>';
	    		$strTable .= '</tr>';
	    	}
	    }
    	$strTable .='</table>';
    	unset($list);
    	downloadExcel($strTable,'Apply');
    	exit();
    }
}
