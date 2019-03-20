layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;
	
	//上传banner
	base.singleupload({'id':'#uploadbimg','url':'/admin/banner/upload','inputname':'img'});
	
	//添加banner
	form.on('submit(addbanner)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑banner
	form.on('submit(editbanner)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除banner
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('banner', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
