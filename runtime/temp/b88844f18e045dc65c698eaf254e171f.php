<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/private/var/www/committee/application/admin/view/login/login.html";i:1528856626;}*/ ?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>创业者大会 - 登录</title>
		<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" />
		<link rel="stylesheet" href="__PUBLIC__/admin/css/login.css" />
	</head>

	<body class="my-login-bg">
		<div id="my-canvas"></div>
		<div class="my-login-box">
			<header>
				<h1>后台登录</h1>
			</header>
			<div class="my-login-main">
				<form class="layui-form" method="post">
					<div class="layui-form-item">
						<label class="my-login-icon">
                        	<i class="layui-icon">&#xe612;</i>
                   	 	</label>
						<input type="text" name="user_name" lay-verify="required|userName" autocomplete="off" placeholder="这里输入登录名" class="layui-input">
					</div>
					<div class="layui-form-item">
						<label class="my-login-icon">
                        	<i class="layui-icon">&#xe642;</i>
                    	</label>
						<input type="password" name="password" lay-verify="required|password" autocomplete="off" placeholder="这里输入密码" class="layui-input">
					</div>
					<div class="layui-form-item">
						<label class="my-login-icon">
							<i class="layui-icon">&#xe62e;</i>
						</label>
						<input type="text" name="code" lay-verify="required" autocomplete="off" placeholder="验证码" class="layui-input my-code">
						<a href="javascript:;" class="change"><img id="codeimg" alt="captcha" src="<?php echo captcha_src(); ?>" class="code-img"/></a>
					</div>
					<div class="layui-form-item my-input-box">
						<button class="layui-btn my-btn" lay-submit="" lay-filter="login">登  录</button>
					</div>
				</form>
			</div>
			<footer>
				<p>宋唐科技&nbsp;&nbsp;©&nbsp;&nbsp;www.haowenwan.com</p>
			</footer>
		</div>
		<script type="text/javascript" src="__PUBLIC__/plugins/layui/layui.js"></script>
		<script type="text/javascript" src="__PUBLIC__/admin/js/particles.min.js"></script>
		
		<script type="text/javascript">
			layui.config({
				base: '__PUBLIC__/admin/js/modules/' //你存放新模块的目录，注意，不是layui的模块目录
			}).use('login'); //加载入口
		</script>
	</body>

</html>