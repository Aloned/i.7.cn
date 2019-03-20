layui.define(['element', 'layer', 'form','base','laydate','layedit'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		laydate = layui.laydate,
		layedit = layui.layedit,
		$ = layui.jquery;
	
	
	//上传用户头像
	base.singleupload({'id':'#uploadport','url':'/admin/user/upload','inputname':'portrait'});
	
	//添加用户
	form.on('submit(add)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑用户
	form.on('submit(edit)', function(data) {
		var url = $(this).data('url');
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除用户
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('user', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
