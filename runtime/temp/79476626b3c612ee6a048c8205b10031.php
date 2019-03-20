<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"G:\phpStudy\PHPTutorial\WWW\test/application/admin/view/category\index.html";i:1528856624;}*/ ?>
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
					<h5 class="my-title">分类列表</h5>
					<a href="<?php echo url('/admin/category/addcategory'); ?>" class="layui-btn layui-btn-small layui-btn-normal"><i class="layui-icon">&#xe654;</i>  添加</a>
				</div>
				<div class="my-content">
					<table class="layui-table">
						<thead>
							<tr>
			                   	<th>分类ID</th>
			                   	<th>分类名称</th>
			                   	<th>对应模型</th>
			                   	<th>是否显示</th>
	                           	<th>是否推荐</th>
			                   	<th>排序</th>
			                   	<th>操作</th>
			               	</tr>
						</thead>
						<tbody>
							<?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$vo): ?>
					  			<tr class="<?php echo $vo['level']; ?>" id="<?php echo $vo['level']; ?>_<?php echo $vo['cat_id']; ?>">
					  			 	<td><?php echo $vo['cat_id']; ?></td>
			                     	<td style="padding-left: <?php echo $vo['level'] * 3.5; ?>em"> 
				                      	<?php if(($vo['level']) == 1): ?>
					                   		<span class="my-status-bg layui-bg-blue" id="icon_<?php echo $vo['level']; ?>_<?php echo $vo['cat_id']; ?>" >&nbsp;+&nbsp;</span>&nbsp;				    
								      	<?php endif; if(($vo['level']) > 1): ?>
								      		|----
								      	<?php endif; ?>
			                            <span><?php echo $vo['cat_name']; ?></span>
					     		 	</td>
					     		 	<td>
					     		 		<?php if(is_array($module_list) || $module_list instanceof \think\Collection || $module_list instanceof \think\Paginator): $i = 0; $__LIST__ = $module_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$module): $mod = ($i % 2 );++$i;if($vo['mod_id'] == $module['id']): ?><b class="txt-color<?php echo $i; ?>"><?php echo $module['name']; ?></b><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					     		 	</td>
			                 		<td>
		                             	<?php if(($vo['is_show']) == '1'): ?><span class="my-status-bg layui-bg-green"><i class="layui-icon">&#xe605;</i></span><?php else: ?><span class="my-status-bg layui-bg-red"><i class="layui-icon">&#x1006;</i></span><?php endif; ?>                            
		                         	</td> 
		                         	<td>
		                            	<?php if(($vo['is_hot']) == '1'): ?><span class="my-status-bg layui-bg-green"><i class="layui-icon">&#xe605;</i></span><?php else: ?><span class="my-status-bg layui-bg-red"><i class="layui-icon">&#x1006;</i></span><?php endif; ?>
		                         	</td>
			                     	<td><?php echo $vo['sort_order']; ?></td>
			                     	<td>
			                      		<a class="layui-btn layui-btn-small" href="<?php echo url('/admin/category/editcategory',['cat_id'=>$vo['cat_id'],'mod_id'=>$vo['mod_id']]); ?>"><i class="layui-icon">&#xe642;</i></a>
			                      		<a class="layui-btn layui-btn-danger layui-btn-small del" href="javascript:;" data-url="<?php echo url('/admin/category/delcategory'); ?>" data-id="<?php echo $vo['cat_id']; ?>" data-mid="<?php echo $vo['mod_id']; ?>"><i class="layui-icon">&#xe640;</i></a>
					     			</td>
			                   	</tr>
			                <?php endforeach; endif; else: echo "" ;endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		layui.config({
			base: '__PUBLIC__/admin/js/modules/' //你存放新模块的目录，注意，不是layui的模块目录
		}).use('category'); //加载入口
	</script>
</html>