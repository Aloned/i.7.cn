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
		uploadImage: {url: '/admin/article/layeditupload', type: 'post'}
		//tool:['left','center','right','face']
	});

	//时间
	laydate.render({
	    elem: '#addtime'
	    ,type: 'datetime'
	});

	//顶级分类
	form.on('select(parent_id_1)', function(data) {
		var id = data.value;
		var url = '/admin/category/getCategory/parent_id/' + id;
		if(id != 0) {
			$.ajax({
				type: "GET",
				url: url,
				error: function(request) {
					layer.msg("服务器繁忙, 请联系管理员!");
					return;
				},
				success: function(v) {
					v = "<option value='0'>请选择分类</option>" + v;
					$('#parent_id_2').empty().html(v);
					//$('#parent_id_2').val(id);//默认选中
					form.render('select');
				}
			});
		} else {
			$('#parent_id_2').html('<option value="0">请选择分类</option>');
			form.render('select');
		}
	});

	//上传文章配图
	base.singleupload({'id':'#uploadtimg','url':'/admin/article/upload','inputname':'thumb'});

	//同步
	form.verify({
		content: function(value){
		  	layedit.sync(editIndex);//同步内容
		}
	});

	//添加文章
	form.on('submit(addarticle)', function(data) {
		var url = $(this).data('url');
		//console.log(data.field);
		base.handle({'url':url,'data':data.field});

		return false;
	});

	//编辑文章
	form.on('submit(editarticle)', function(data) {
		var url = $(this).data('url');
		//console.log(data.field);
		base.handle({'url':url,'data':data.field});

		return false;
	});

	//删除文章
	$('.del').click(function() {
		var id = $(this).data('id'),
			url = $(this).data('url');

		base.del({'url':url,'id':id});
	});

	exports('article', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
