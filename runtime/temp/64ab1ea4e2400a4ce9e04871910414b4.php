<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:66:"/private/var/www/committee/application/admin/view/photo/index.html";i:1531222513;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="format-detection" content="telephone=no">
		<title>主页</title>

		<!--CSS-->
		<link rel="stylesheet" href="__PUBLIC__/plugins/layui/css/layui.css" />
		<link rel="stylesheet" href="__PUBLIC__/admin/css/global.css" />

		<!--JS-->
		<script type="text/javascript" src="__PUBLIC__/plugins/layui/layui.js"></script>
	</head>

	<body>
		<div class="layui-main my-admin">
			<div class="layui-tab my-tab">
				<div class="my-title-box">
					<h5 class="my-title">图片列表</h5>
					<a href="<?php echo url('photo/addphoto',['type'=>$type]); ?>" class="layui-btn layui-btn-small layui-btn-normal"><i class="layui-icon">&#xe654;</i>  添加</a>
				</div>
				<div class="my-content">
					<table class="layui-table">
						<thead>
							<tr>
			                   	<th>ID</th>
			                   	<th>缩略图</th>
			                   	<th>图片标题</th>
			                   	<th>所属分类</th>
			                   	<th>是否显示</th>
			                   	<th>是否热门</th>
			                   	<th>是否推荐</th>
			                   	<th>发布时间</th>
			                   	<th>操作</th>
			               	</tr>
						</thead>
						<tbody>
							<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>
					  			<tr>
					  			 	<td><?php echo $vo['id']; ?></td>
					  			 	<td>
					  			 		<?php if(empty($vo['thumb']) || (($vo['thumb'] instanceof \think\Collection || $vo['thumb'] instanceof \think\Paginator ) && $vo['thumb']->isEmpty())): else: ?>
											<img src="<?php echo $vo['thumb']; ?>" width="50"/>
										<?php endif; ?>
					  			 	</td>
			                     	<td> 
				                      	<a href="javascript:;"><?php echo mb_substr($vo['title'],0,20,'utf-8'); ?></a>
					     		 	</td>
					     		 	<td><?php echo $vo['cat_name']; ?></td>
			                 		<td>
		                             	<?php if(($vo['is_show']) == '1'): ?><span class="my-status-bg layui-bg-green"><i class="layui-icon">&#xe605;</i></span><?php else: ?><span class="my-status-bg layui-bg-red"><i class="layui-icon">&#x1006;</i></span><?php endif; ?>                            
		                         	</td> 
		                         	<td>
		                            	<?php if(($vo['is_hot']) == '1'): ?><span class="my-status-bg layui-bg-green"><i class="layui-icon">&#xe605;</i></span><?php else: ?><span class="my-status-bg layui-bg-red"><i class="layui-icon">&#x1006;</i></span><?php endif; ?>
		                         	</td>
		                         	<td>
		                            	<?php if(($vo['is_recommend']) == '1'): ?><span class="my-status-bg layui-bg-green"><i class="layui-icon">&#xe605;</i></span><?php else: ?><span class="my-status-bg layui-bg-red"><i class="layui-icon">&#x1006;</i></span><?php endif; ?>
		                         	</td>
			                     	<td><?php echo date('Y-m-d H:i:s',$vo['addtime']); ?></td>
			                     	<td>
			                      		<a class="layui-btn layui-btn-small" href="<?php echo url('photo/editphoto',['id'=>$vo['id'],'type'=>$type]); ?>"><i class="layui-icon">&#xe642;</i></a>
			                      		<a class="layui-btn layui-btn-danger layui-btn-small del" href="javascript:;" data-url="<?php echo url('photo/delphoto'); ?>" data-id="<?php echo $vo['id']; ?>-<?php echo $vo['cat_id']; ?>"><i class="layui-icon">&#xe640;</i></a>
					     			</td>
			                   	</tr>
			                <?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
					<div class="my-pager">
						<?php echo $pager; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		
		layui.config({
			base: '__PUBLIC__/admin/js/modules/' //你存放新模块的目录，注意，不是layui的模块目录
		}).use('photoindex'); //加载入口
	</script>
</html>