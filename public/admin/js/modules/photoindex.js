layui.define(['element', 'layer', 'form','base','laydate','layedit','upload'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		laydate = layui.laydate,
		layedit = layui.layedit,
		upload  = layui.upload,
		$ = layui.jquery;

	//删除图片
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');
			
		base.del({'url':url,'id':id});
	});

	exports('photoindex', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
