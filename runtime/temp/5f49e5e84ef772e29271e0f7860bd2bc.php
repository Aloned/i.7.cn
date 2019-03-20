<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:72:"G:\phpStudy\PHPTutorial\WWW\test/application/admin/view/index\index.html";i:1528856626;s:74:"G:\phpStudy\PHPTutorial\WWW\test/application/admin/view/public\header.html";i:1528856628;s:74:"G:\phpStudy\PHPTutorial\WWW\test/application/admin/view/public\footer.html";i:1528856626;}*/ ?>
<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
		<title>创业者大会 - 管理后台</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">

		<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" media="all" />
		<link rel="stylesheet" href="__PUBLIC__/admin/css/global.css" media="all">

	</head>

	<body>
		<div class="layui-layout layui-layout-admin">
			<div class="layui-header header header-demo">
				<div class="layui-main">
					<div class="admin-login-box">
						<a class="logo" href="<?php echo url('admin/index/index'); ?>">
							<img alt="" src="__PUBLIC__/admin/images/logo.png"/>
						</a>
					</div>
					<ul class="layui-nav admin-header-item">
						<li class="layui-nav-item">
							<a href="javascript:;" class="clearcache">清除缓存</a>
						</li>
						<li class="layui-nav-item">
							<a target="_blank" href="<?php echo $siteurl; ?>">浏览网站</a>
						</li>
						<li class="layui-nav-item">
							<a href="javascript:;" class="admin-header-user">
								<img alt="admin" src="__PUBLIC__/admin/images/default_user.png" />
								<span><?php echo $admin_info['user_name']; ?></span>
							</a>
							<dl class="layui-nav-child">
								<dd>
									<a href="javascript:;" class="editpassword" data-url="<?php echo url('admin/login/editpwd',['admin_id'=>$admin_info['admin_id']]); ?>"><i class="layui-icon">&#xe642;</i> 修改密码</a>
								</dd>
								<dd>
									<a href="javascript:;" class="logout" data-url="<?php echo url('login/logout'); ?>"><i class="layui-icon">&#x1006;</i> 注销</a>
								</dd>
							</dl>
						</li>
					</ul>
					<ul class="layui-nav admin-header-item-mobile">
						<li class="layui-nav-item">
							<a href="<?php echo url('login/logout'); ?>"><i class="layui-icon">&#x1006;</i> 注销</a>
						</li>
					</ul>
				</div>
			</div>
<div class="layui-side layui-bg-black" id="admin-side">
	<div class="layui-side-scroll" id="admin-navbar-side" lay-filter="side"></div>
</div>
<div class="layui-body" id="admin-body">
	<div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="admin-tab">
		<ul class="layui-tab-title">
			<li class="layui-this">
				<i class="layui-icon">&#xe68e;</i>
				<cite>控制面板</cite>
			</li>
		</ul>
		<div class="layui-tab-content" style="min-height: 150px; padding: 5px 0 0 0;">
			<div class="layui-tab-item layui-show">
				<iframe src="<?php echo url('admin/index/main'); ?>" frameborder="0" style="width: 100%;"></iframe>
			</div>
		</div>
	</div>
</div>
<div class="layui-footer footer footer-demo" id="admin-footer">
				<div class="layui-main">
					<p><?php echo date('Y'); ?> &copy;
						<a href="http://www.haowenwan.com/">www.haowenwan.com</a> 宋唐科技
					</p>
				</div>
			</div>
			<div class="site-tree-mobile layui-hide">
				<i class="layui-icon">&#xe602;</i>
			</div>
			<div class="site-mobile-shade"></div>
			
			<script type="text/javascript" src="__PUBLIC__/plugins/layui/layui.js"></script>
			<script src="__PUBLIC__/admin/js/fun.js"></script>
		</div>
	</body>
</html>