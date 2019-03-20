layui.define(['element', 'layer', 'form','base','laydate','layedit'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		laydate = layui.laydate,
		layedit = layui.layedit,
		$ = layui.jquery;
	
	//编辑器
	var editIndex = layedit.build('content',{
		height:300,
		uploadImage: {url: '/admin/content/layeditupload', type: 'post'}
		//tool:['left','center','right','face']
	});
	
	//同步
	form.verify({
		content: function(value){
		  	layedit.sync(editIndex);//同步内容
		}
	});	
	
	
	//编辑单页内容
	form.on('submit(editcontent)', function(data) {
		var url = $(this).data('url');
		console.log(data.field);
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	exports('content', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
