<?php
namespace app\mobile\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;
use think\Loader;
use send\WsMessageSend;

class Ajax extends Controller
{
	//发送验证码
	public function send(){
		$request = Request::instance();
		//短信验证码
		$message = Db::name('message')->where('id',1)->find();
		
		//随机生成验证码
		$code = mt_rand(1000, 9999);
		
		if($request->isPost()){
			$tel = input('param.utel/s');
		
			//电话是否已经存在
			$user = Db::name('user')->where('utel',$tel)->find();
			//插入验证码
			$content = str_replace("@",$code,$message['msg']);
			
			if(isset($user)){
				$msg = ['status'=>-1,'msg'=>'电话号码已被使用'];
			}else{
				$sendMsg = new WsMessageSend();
				$result = $sendMsg->send($message['account'], md5($message['password']), $tel, $content,'','');
				
				if($result->StatusCode == 'OK'){
					$msg = ['status'=>1,'msg'=>'发送成功'];
					session('code',$code);
				}else{
					$msg = ['status'=>0,'msg'=>'发送失败','data'=>$result];
				}
			}
			
			return json($msg);
		}
	}
	//报名下一步
    public function step()
    {
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$tel = input('param.utel/s');
			$code = input('param.code/s');
			
			if(session('code') == $code){
				$msg = ['status'=>1,'msg'=>'验证通过'];
			}else{
				$msg = ['status'=>0,'msg'=>'验证码不正确'];
			}
			
			return json($msg);
		}
    }
	//第二步
	public function apply(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$data = $request->param();
//			$data['password'] = md5($data['password']);
			$data['addtime'] = time();
			$data['edittime'] = time();
			$data['ip'] = $request->ip();
			
			//判断数据库中是否已存在该邀请码
			$randomstr = randomkeys(5);
			$code = Db::name('user')->where('ucode',$randomstr)->value('ucode');
			
			if($code){
				$data['ucode'] = randomkeys(5);
			}else{
				$data['ucode'] = $randomstr;
			}
			
			//电话是否已经存在
			$user = Db::name('user')->where('utel',$data['utel'])->find();
			
			if(isset($user)){
				$msg = ['status'=>-1,'msg'=>'您已报过名'];
			}else{
				$res = Db::name('user')->insertGetId($data);
				if($res > 0){
				    $this->sendMsg($data['utel']);
					session('userid',$res);
					$msg = ['status'=>1,'msg'=>'报名成功','url'=>url('home/online/success')];
				}else{
					$msg = ['status'=>0,'msg'=>'报名失败'];
				}
			}
			
			return json($msg);
		}
	}
	
	//修改密码
	public function edit(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$password = input('password');
			
			$res = Db::name('user')->where('uid',session('userid'))->update(['password'=>md5($password)]);
			if($res){
				$msg = ['status'=>1,'msg'=>'密码修改成功','url'=>url('/my')];
			}else{
				$msg = ['status'=>0,'msg'=>'密码修改失败'];
			}
			
			return json($msg);
		}
	}
	
	//找回密码时发送验证码
	public function send2(){
		$request = Request::instance();
		//短信验证码
		$message = Db::name('message')->where('id',1)->find();
		
		//随机生成验证码
		$code = mt_rand(1000, 9999);
		
		if($request->isPost()){
			$tel = input('param.utel/s');
		
			//电话是否有此用户
			$user = Db::name('user')->where('utel',$tel)->find();
			//插入验证码
			$content = str_replace("@",$code,$message['msg']);
			
			if(!isset($user)){
				$msg = ['status'=>-1,'msg'=>'查无此人'];
			}else{
				$sendMsg = new WsMessageSend();
				$result = $sendMsg->send($message['account'], md5($message['password']), $tel, $content,'','');
				
				if($result->StatusCode == 'OK'){
					$msg = ['status'=>1,'msg'=>'发送成功'];
					session('fcode',$code);
					session('ftel',$user['utel']);
				}else{
					$msg = ['status'=>0,'msg'=>'发送失败','data'=>$result];
				}
			}
			
			return json($msg);
		}
	}
	
	//找回密码时点击下一步
    public function step2()
    {
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$tel = input('param.utel/s');
			$code = input('param.code/s');
			
			if(session('fcode') == $code){
				$msg = ['status'=>1,'msg'=>'验证通过'];
			}else{
				$msg = ['status'=>0,'msg'=>'验证码不正确'];
			}
			
			return json($msg);
		}
    }
	
	//修改密码
	public function forget(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$password = input('password');
			
			$res = Db::name('user')->where('utel',session('ftel'))->update(['password'=>md5($password)]);
			if($res){
				$userid = Db::name('user')->where('utel',session('ftel'))->value('uid');
				session('userid',$userid);
				$msg = ['status'=>1,'msg'=>'密码修改成功','url'=>url('/my')];
			}else{
				$msg = ['status'=>0,'msg'=>'密码修改失败'];
			}
			
			return json($msg);
		}
	}

    public function sendMsg($phone){
        $content = urlencode('【创业者大会】您已成功报名云和数据第六届创业者大会！请火速抢票！同时邀请十位好友报名领票即刻升为VIP门票');
        $url = 'https://dx.ipyy.net/sms.aspx?action=send&userid=&account=ZZ00321&password='.strtoupper(md5('ZZ0032188')).'&mobile='.$phone.'&content='.$content.'&sendTime=&extno=3';
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
    }
}
