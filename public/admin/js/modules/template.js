layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;
	
	//上传
	base.singleupload({'id':'#uploadbimg','url':'/admin/template/upload','inputname':'path'});
	
	//添加
	form.on('submit(add)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑
	form.on('submit(edit)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('template', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
