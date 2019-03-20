<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"G:\phpStudy\PHPTutorial\WWW\test/application/admin/view/login\login.html";i:1552988286;}*/ ?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>创业者大会 - 登录</title>
		<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" />
		<link rel="stylesheet" href="__PUBLIC__/admin/css/login.css" />
		<style>
			.layui-btn{
				width:100%;
				font-size:15px;
			}
			.lingpiao{
				font-size: 18px;
			}
		</style>
	</head>

	<body class="my-login-bg" onload="load()" >
		<div id="my-canvas"></div>
		<div class="my-login-box">
			<header>
				<h1>管理员后台登录</h1>
			</header>
			<div class="my-login-main">
					<div class="layui-form-item">
						<label class="my-login-icon">
                        	<i class="layui-icon">&#xe612;</i>
                   	 	</label>
						<div class="layui-btn layui-btn-lg layui-btn-normal" id="ukNotice">请插入加密锁进行登录...</div>
					</div>

					<div class="layui-form-item my-input-box">
						<button class="layui-btn my-btn" type="button" onclick="login_onclick()">登  录</button>
					</div>
			</div>
			<footer>
				<p class="lingpiao">领票点管理员登录>></p>
			</footer>
		</div>
		<script type="text/javascript" src="__PUBLIC__/plugins/layui/layui.js"></script>
		<script type="text/javascript" src="__PUBLIC__/admin/js/jquery.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/admin/js/particles.min.js"></script>
		<script type="text/javascript" src="__PUBLIC__/admin/js/Syunew3.js"></script>

		<script type="text/javascript">
			layui.config({
				base: '__PUBLIC__/admin/js/modules/' //你存放新模块的目录，注意，不是layui的模块目录
			}).use('login'); //加载入口

			function load()
			{
				//如果是IE10及以下浏览器，则跳过不处理
				if(navigator.userAgent.indexOf("MSIE")>0 && !navigator.userAgent.indexOf("opera") > -1) return;
				try
				{
					var s_pnp=new SoftKey3W();//创建UK类
					s_pnp.Socket_UK.onopen = function()
					{
						bConnect=1;//代表已经连接，用于判断是否安装了客户端服务
					}

					//在使用事件插拨时，注意，一定不要关掉Sockey，否则无法监测事件插拨
					s_pnp.Socket_UK.onmessage =function got_packet(Msg)
					{
						var PnpData = JSON.parse(Msg.data);
						if(PnpData.type=="PnpEvent")//如果是插拨事件处理消息
						{
							if(PnpData.IsIn)
							{
								$('#ukNotice').html('检测到密码锁，请点击登录进行访问')
							}
							else
							{
								$('#ukNotice').html('请插入加密锁进行登录...')
							}
						}
					}

					s_pnp.Socket_UK.onclose = function()
					{

					}
				}
				catch(e)
				{
					alert(e.name + ": " + e.message);
					return false;
				}
			}

			function login_onclick()
			{
				//如果是IE10及以下浏览器，则使用AVCTIVEX控件的方式
				if(navigator.userAgent.indexOf("MSIE")>0 && !navigator.userAgent.indexOf("opera") > -1) return Handle_IE10();

				//判断是否安装了服务程序，如果没有安装提示用户安装
				if(bConnect==0)
				{
					//询问框
					layer.confirm('未安装加密锁插件,请点击下载并安装', {
						btn: ['下载插件','取消']
					}, function(){
						window.open("__PUBLIC__/resource/SetUp.exe");
					}, function(){

					});
				}
				try
				{
					var DevicePath,ProduceDate,version;

					//由于是使用事件消息的方式与服务程序进行通讯，
					//好处是不用安装插件，不分系统及版本，控件也不会被拦截，同时安装服务程序后，可以立即使用，不用重启浏览器
					//不好的地方，就是但写代码会复杂一些
					var s_simnew1=new SoftKey3W(); //创建UK类

					s_simnew1.Socket_UK.onopen = function() {
						s_simnew1.ResetOrder();//这里调用ResetOrder将计数清零，这样，消息处理处就会收到0序号的消息，通过计数及序号的方式，从而生产流程
					}

					//写代码时一定要注意，每调用我们的一个UKEY函数，就会生产一个计数，即增加一个序号，较好的逻辑是一个序号的消息处理中，只调用我们一个UKEY的函数
					s_simnew1.Socket_UK.onmessage =function got_packet(Msg)
					{
						var UK_Data = JSON.parse(Msg.data);
						if(UK_Data.type!="Process")return ;//如果不是流程处理消息，则跳过
						switch(UK_Data.order)
						{
							case 0:
							{
								s_simnew1.FindPort(0); //发送命令取UK的路径
							}
								break;//!!!!!重要提示，如果在调试中，发现代码不对，一定要注意，是不是少了break,这个少了是很常见的错误
							case 1:
							{
								if( UK_Data.LastError!=0){window.alert ( "未发现加密锁，请插入加密锁");s_simnew1.Socket_UK.close();return false;}
								DevicePath=UK_Data.return_value;//获得返回的UK的路径
								s_simnew1.GetVersion(DevicePath); //发送命令取UK的版本
							}
								break;
							case 2:
							{
								if( UK_Data.LastError!=0){ window.alert("返回版本号错误，错误码为："+UK_Data.LastError.toString());s_simnew1.Socket_UK.close();return false;}
								version=UK_Data.return_value;//获得返回的UK的版本
								if(version>10)
								{
									//取得锁的出厂编码
									s_simnew1.GetProduceDate( DevicePath);//发送命令取UK的出厂编码
								}
								else
								{
									window.alert ("锁的版本少于11");
								}
							}
								break;
							case 3:
							{
								if( UK_Data.LastError!=0){ window.alert("取得锁的出厂编码错误，错误码为："+UK_Data.LastError.toString());s_simnew1.Socket_UK.close();return false;}
								ProduceDate=UK_Data.return_value;//获得返回的UK的出厂编码
								//发送编码进行登陆验证
								$.post("/admin/Login/adminLogin", { 'uKeySn':ProduceDate  } ,function (res) {
									if(res.status != 1001) {
										layer.msg(res.msg, {
											icon: 2
										});
									} else {
										//所有工作处理完成后，关掉Socket
										s_simnew1.Socket_UK.close();
										location.href = res.url;
									}
								},'JSON');

							}
								break;
						}
					}
					s_simnew1.Socket_UK.onclose = function(){

					}
					return true;
				}

				catch(e)
				{
					alert(e.name + ": " + e.message);
					return false;
				}

			}


		</script>
	</body>

</html>