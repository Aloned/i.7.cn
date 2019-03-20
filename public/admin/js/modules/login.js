layui.define(['element', 'layer', 'form'], function(exports) {
	var layer = layui.layer,
		form = layui.form,
		$ = layui.jquery;

	//改变验证码
	$('.change').click(function(){
		//获取当前时间戳
		var t = Date.parse(new Date()) / 1000;
		$('#codeimg').attr('src','/captcha?id=' + t);
	});

	form.on('submit(login)', function(data) {
		$.ajax({
			type: "post",
			url: 'index',
			data: data.field,
			async: false,
			dataType: 'json',
			success: function(res) {
				console.log(res);
				if(res.status != 1001) {
					layer.msg(res.msg, {
						icon: 2
					});
					$('#codeimg').attr('src','/captcha?id=' + Math.floor(Math.random() * 100));
				} else {
					location.href = res.url;
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				layer.msg('网络失败，请重试!', {
					icon: 2
				});
			}
		});
		
		return false;
	});

	exports('login', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});

particlesJS('my-canvas', {
	particles: {
		color: '#fff',
		shape: 'circle', // "circle", "edge" or "triangle"
		opacity: 1,
		size: 4,
		size_random: true,
		nb: 30,
		line_linked: {
			enable_auto: true,
			distance: 100,
			color: '#fff',
			opacity: 1,
			width: 1,
			condensed_mode: {
				enable: false,
				rotateX: 600,
				rotateY: 600
			}
		},
		anim: {
			enable: true,
			speed: 1
		}
	},
	interactivity: {
		enable: true,
		mouse: {
			distance: 300
		},
		detect_on: 'canvas', // "canvas" or "window"
		mode: 'grab',
		line_linked: {
			opacity: .5
		},
		events: {
			onclick: {
				enable: true,
				mode: 'push', // "push" or "remove"
				nb: 4
			}
		}
	},
	/* Retina Display Support */
	retina_detect: true
});