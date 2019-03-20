layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;

	//上传封面
	base.singleupload({'id':'#uploadcimg','url':'/admin/category/upload','inputname':'cat_thumb'});

	//指定模型
	form.on('radio(module)',function(data){
		var id = data.value;
		var url = '/admin/category/getmCategory/mod_id/' + id;
		if(id != 0) {
			$.ajax({
				type: "GET",
				url: url,
				error: function(request) {
					layer.msg("服务器繁忙, 请联系管理员!");
					return;
				},
				success: function(v) {
					v = "<option value='0'>请选择顶级分类</option>" + v;
					v2 = "<option value='0'>请选择分类</option>";
					$('#parent_id_1').empty().html(v);
					$('#parent_id_2').empty().html(v2);
					form.render('select');
				}
			});
		} else {
			$('#parent_id_1').html('<option value="0">请选择顶级分类</option>');
			form.render('select');
		}
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

	//添加分类
	form.on('submit(addcategory)', function(data) {
		var url = $(this).data('url');

		base.handle({'url':url,'data':data.field});

		return false;
	});

	//编辑分类
	form.on('submit(editcategory)', function(data) {
		var url = $(this).data('url');

		base.handle({'url':url,'data':data.field});

		return false;
	});

	//删除分类
	$('.del').click(function() {
		var id = $(this).data('id'),
			mid = $(this).data('mid'),
			url = $(this).data('url');

		//base.del({'url':url,'id':id,'mid':mid});

		layer.confirm('确定要删除吗', {
			icon: 3,
			title: '提示'
		}, function(index) {
			$.post(url, {
				'id': id,
				'mid':mid
			}, function(res) {
				if(res.status == 1) {
					layer.msg(res.msg, {
						icon: 1,
						time:1500
					},function(index){
						location.href = res.url;
					});
				} else {
					layer.msg(res.msg, {
						icon: 2
					});
				}
			});
			layer.close(index);
		});
	});

	exports('category', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
