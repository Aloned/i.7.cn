layui.define(['element', 'layer', 'form','base','laydate','layedit'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		laydate = layui.laydate,
		layedit = layui.layedit,
		$ = layui.jquery;
	
	//添加管理员
	form.on('submit(addman)', function(data) {
		var url = $(this).data('url');
		//console.log(data.field);
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑管理员
	form.on('submit(editman)', function(data) {
		var url = $(this).data('url');
		//console.log(data.field);
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除管理员
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('admin', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
