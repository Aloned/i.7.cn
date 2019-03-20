layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;
	
	form.verify({
		//英文验证
		english:function(value,item){
			if(!new RegExp("^[a-zA-Z0-9]{3,12}$").test(value)){
				return '字段名只能是3到12个英文和数字的组合';
			}
		}
	});
	
	//添加自定义表单
	form.on('submit(add)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//编辑自定义表单
	form.on('submit(edit)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});
	
	//添加表字段
	form.on('submit(addfield)', function(data) {
		var url = $(this).data('url');
		
		base.handle({'url':url,'data':data.field});
		
		return false;
	});

	//删除表字段
	$('.del').click(function() {
		var field = $(this).data('field'),
			tablename = $(this).data('name'),
			url = $(this).data('url');
		
		layer.confirm('确定要删除吗', {
			icon: 3,
			title: '提示'
		}, function(index) {
			$.post(url, {
					'field':field,
					'tablename':tablename
				}, function(res) {
				if(res.status == 1) {
					layer.msg(res.msg, {
						icon: 1,
						time:1500
					},function(index){
						//location.href = res.url;
						location.reload();
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
	
	var tips = 999;
	$('.edit').hover(function(){
		tips = layer.tips('暂时无法修改，我的哥！', $(this));
	},function(){
		layer.close(tips);
	});

	exports('feedback', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
