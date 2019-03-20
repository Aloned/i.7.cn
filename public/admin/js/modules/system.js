layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;
	
	//上传网站logo
	base.singleupload({'id':'#uploadlogo','url':'/admin/system/upload','inputname':'logo'});
	
	//添加友情链接logo
	base.singleupload({'id':'#uploadlink','url':'/admin/system/upload','inputname':'link_logo'});
	
	//更新网站设置
	form.on('submit(config)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});

		return false;
	});

	//添加友情链接
	form.on('submit(addlink)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑友情链接
	form.on('submit(editlink)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除友情链接
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('system', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
