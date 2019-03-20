/*扩展一个base模块*/

layui.define(['element', 'layer', 'form','upload'],function(exports){
	var layer = layui.layer,
		form = layui.form,
		upload = layui.upload,
		$ = layui.jquery;
	
	var common = {
		//字符串转JSON
		strToJson: function(str) {
			var json = eval('(' + str + ')');
			return json;
		},
		
		//页面层  {title:'标题',width:'宽度',height:'高度',url:'地址'}
		iframe: function(options) {
			var p = options || {};
			return layer.open({
				type: 2,
				title: p.title || "信息",
				shadeClose: true,
				shade: 0.8,
				area: [p.width, p.height],
				content: p.url //iframe的url
			});
		},
		
		//此操作包括添加和删除 {url:'地址',data:'数据'} 
		handle: function(options) {
			var a = options || {};
	
			$.ajax({
				type: "post",
				url: a.url,
				data: a.data,
				dataType: 'json',
				success: function(res) {
					if(res.status == 1) {
						layer.msg(res.msg, {
							icon: 1,
							time:1500
						}, function(index) {
							location.href = res.url;
						});
					} else {
						layer.msg(res.msg, {
							icon: 2
						});
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					layer.msg('网络失败，请重试!', {
						icon: 2
					});
				}
			});
		},
	
		//删除 {id:'id',url:'地址'}
		del: function(options) {
			var d = options || {};
	
			layer.confirm('确定要删除吗', {
				icon: 3,
				title: '提示'
			}, function(index) {
				$.post(d.url, {
					'id': d.id
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
		},
		
		//单文件上传无预览图 {'id':'选择器','url':'上传接口','inputname':'地址显示的input'}
		singleupload:function(options){
			var su = options || {};
			
			upload.render({
				elem:su.id,
				url :su.url,
				size: 2000, //限制文件大小，单位 KB
				accept:'images',
				field:'image',
				done:function(res){
					if(res.status == 1){
						layer.msg(res.msg,{icon:1});
						$('input[name='+su.inputname+']').val(res.src);
					}else{
						layer.msg(res.msg,{icon:2});
					}
				},
				error:function(){
					$(su.id).addClass('layui-btn-danger').text('重试');
				}
			});
		},
		
		//多文件上传无预览图 {'id':'选择器','url':'上传接口','inputname':'地址保存的input的name','number':'上传张数'}
		doubleupload:function(options){
			var param = options || {};
			
			var number = 0;
			var imgslist = $('input[name='+param.inputname+']').val();

			if(imgslist == ""){
				var imgs = [];
			}else{
				var imgs = imgslist.split(',');
			}
			
			upload.render({
			    elem: param.id
			    ,url: param.url
			    ,multiple: true
			    ,field:'image[]'
			    ,size: 3000 //限制文件大小，单位 KB
			    ,before: function(){
			      	layer.load(1, {
		  				shade: [0.2,'#000'] //0.1透明度的白色背景
					});
			    }
			    ,done: function(res){
			       	if(res.status == 1){
			       		layer.closeAll('loading');
			       		layer.msg(res.msg);
			       		$('input[name='+param.inputname+']').val(res.src);
			       		$('#imgBox').show();
			       		$('#imgBox').find('ul').prepend('<li><img src="'+ res.src +'" /><a href="javascript:;" data-index="'+number+'" class="delimg"><i class="layui-icon">&#x1006;</i></a></li>')
			       		imgs[number] = res.src;
			       		$('input[name='+param.inputname+']').val(imgs);
			       		number = number + 1;
			       		if(number >= param.number){
			       			$('#imgBox .last').hide();
			       		}
			       	}else{
			       		layer.msg(res.msg,{icon:2});
			       	}
			    }
			});
			
			//删除
			$(document).on('click','.delimg',function(){
				var parent = $(this).parent('li');
				var index = $(this).data('index');
				parent.remove();
				imgs.splice(index,1);
				$('input[name='+param.inputname+']').val(imgs);
				$('#imgBox .last').show();
			});
		},
		
	};
	
	exports('base', common);
});