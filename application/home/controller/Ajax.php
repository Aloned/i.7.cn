<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use think\Paginator;
use think\Loader;
use send\WsMessageSend;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Ajax extends Controller
{
	//发送验证码
	public function send1(){
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
	public function send(){
                $toemail = '269899062@qq.com';//定义收件人的邮箱

                $mail = new PHPMailer();

                $mail->isSMTP();// 使用SMTP服务
                $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
                $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
                $mail->SMTPAuth = true;// 是否使用身份验证
                $mail->Username = "13520806649@163.com";// 发送方的QQ邮箱用户名，就是自己的邮箱名
                $mail->Password = "a262254131";// 发送方的邮箱密码，不是登录密码,是qq的第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
                $mail->SMTPSecure = "ssl";// 使用ssl协议方式,
                $mail->Port = 465;// QQ邮箱的ssl协议方式端口号是465/587

                $mail->setFrom("13520806649@163.com","创业者大会");// 设置发件人信息，如邮件格式说明中的发件人,
                $mail->addAddress($toemail,'尊敬的用户');// 设置收件人信息，如邮件格式说明中的收件人
                $mail->addReplyTo("13520806649@163.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
                //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
                //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
                //$mail->addAttachment("bug0.jpg");// 添加附件


                $mail->Subject = "这是一个测试邮件";// 邮件标题
                $mail->Body = "您的验证码为 <b>384733</b>，哈哈哈！";// 邮件正文
                //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

                if(!$mail->send()){// 发送邮件
                    echo "Message could not be sent.";
                    echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
                }else{
                    echo '发送成功';
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
