<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"/private/var/www/committee/application/admin/view/public/dispatch_jump.html";i:1551960215;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<title>阡陌后台管理 - 跳转提示</title>
		
		<!--CSS-->
		<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" />
		<link rel="stylesheet" href="__PUBLIC__/admin/css/global.css" />
	</head>
	<body>
		<div class="tip-box">
<?php if($code == 1) {?>
		<!--处理成功-->
		<div class="tip-box-body">
			<div class="tip-box-main success">
	        	<h4 class="tip-box-msg"><?php echo(strip_tags($msg)); ?></h4>
	        	<p><b id="wait"><?php echo($wait); ?></b>秒后页面自动<a id="href" href="<?php echo($url); ?>">跳转</a></p>
	          	<a href="/" target="_parent" class="go">网站前台</a>
	          	<a href="/login/index" class="go" target="_parent">管理员后台</a>
	       	</div>
      	</div> 
<?php }else{?>
		<!--处理失败-->
       	<div class="tip-box-body">
       		<div class="tip-box-main failure">
       			<h4 class="tip-box-msg"><?php echo(strip_tags($msg)); ?></h4>
	          	<p><b id="wait"><?php echo($wait); ?></b>秒后页面自动<a id="href" href="<?php echo($url); ?>">跳转</a></p>
	          	<a href="/" target="_parent" class="go">网站前台</a>
	          	<a href="/admin/login/index" class="go" target="_parent">管理员后台</a>
       		</div>
      	</div>
<?php }?>
		</div>
		<div class="tip-center">
	        <div class="copyright">
	            2015-<?php echo date('Y'); ?> &copy; <a href="http://www.haowenwan.com/">宋唐科技</a> 出品
	        </div>
	    </div>
	    <script type="text/javascript">
			(function(){
				var wait = document.getElementById('wait'),href = document.getElementById('href').href;
				var interval = setInterval(function(){
					var time = --wait.innerHTML;
					if(time <= 0) {
						location.href = href;
						clearInterval(interval);
					};
				}, 1000);
			})();
		</script>    
	</body>
</html>
