var tab;

layui.config({
    base: '/public/admin/js/modules/',
    version: new Date().getTime()
}).use(['element', 'layer', 'navbar', 'tab','base'], function () {
    var element = layui.element,
        $ = layui.jquery,
        layer = layui.layer,
        base = layui.base,
        navbar = layui.navbar();
    tab = layui.tab({
        elem: '.admin-nav-card' //设置选项卡容器
        ,
        //maxSetting: {
        //	max: 5,
        //	tipMsg: '只能开5个哇，不能再开了。真的。'
        //},
        contextMenu: true,
        onSwitch: function (data) {
            console.log(data.id); //当前Tab的Id
            console.log(data.index); //得到当前Tab的所在下标
            console.log(data.elem); //得到当前的Tab大容器

            console.log(tab.getCurrentTabId())
        },
        closeBefore: function (obj) { //tab 关闭之前触发的事件
            console.log(obj);
            //obj.title  -- 标题
            //obj.url    -- 链接地址
            //obj.id     -- id
            //obj.tabId  -- lay-id
            if (obj.title === 'BTable') {
                layer.confirm('确定要关闭' + obj.title + '吗?', { icon: 3, title: '系统提示' }, function (index) {
                    //因为confirm是非阻塞的，所以这里关闭当前tab需要调用一下deleteTab方法
                    tab.deleteTab(obj.tabId);
                    layer.close(index);
                });
                //返回true会直接关闭当前tab
                return false;
            }else if(obj.title==='表单'){
                layer.confirm('未保存的数据可能会丢失哦，确定要关闭吗?', { icon: 3, title: '系统提示' }, function (index) {
                    tab.deleteTab(obj.tabId);
                    layer.close(index);
                });
                return false;
            }
            return true;
        }
    });
    //iframe自适应
    $(window).on('resize', function () {
        var $content = $('.admin-nav-card .layui-tab-content');
        $content.height($(this).height() - 166);
        $content.find('iframe').each(function () {
            $(this).height($content.height());
        });
    }).resize();

    //设置navbar
    navbar.set({
        spreadOne: true,
        elem: '#admin-navbar-side',
        cached: true,
        //data: navs
		cached:false,
		url: '/admin/index/menu'
    });
    //渲染navbar
    navbar.render();
    //监听点击事件
    navbar.on('click(side)', function (data) {
        tab.tabAdd(data.field);
    });
    
    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile'),
        shadeMobile = $('.site-mobile-shade');
    treeMobile.on('click', function () {
        $('body').addClass('site-mobile');
    });
    shadeMobile.on('click', function () {
        $('body').removeClass('site-mobile');
    });
    
    //退出登录
	$('.logout').click(function(){
		var url = $(this).data('url');
		
		$.post(url,function(res){
			if(res.status == 1) {
				layer.msg('退出成功', {
					icon: 1
				},function(){
					location.href = 'admin/login'
				});
			} else {
				layer.msg('退出失败', {
					icon: 2
				});
			}
		});
	});
	
	//清理缓存
	$('.clearcache').click(function(){
		$.post('/admin/system/cleanCache',function(res){
			if(res.status == 1) {
				layer.msg(res.msg);
			} else {
				layer.msg('清理失败', {
					icon: 2
				});
			}
		});
	});
	
	//修改密码
	$('.editpassword').click(function(){
		var url = $(this).data('url');
		//{title:'标题',width:'宽度',height:'高度',url:'地址'}
		base.iframe({'title':'修改密码',width:'660px',height:'480px',url:url});
	});
});