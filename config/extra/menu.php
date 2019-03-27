<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

return [
	[
		'id'=>'1','title'=>'栏目管理','icon'=>'&#xe857;','spread'=>false,
		'children' => [
			['title'=>'栏目列表','icon'=>'&#xe621;','id'=>'2','href'=>'/admin/category/index']
		]
	],
	[
		'id'=>'3','title'=>'内容管理','icon'=>'&#xe705;','spread'=>false,
		'children' => [
			['title'=>'文章列表','icon'=>'&#xe621;','id'=>'4','href'=>'/admin/article/index'],
			['title'=>'嘉宾列表','icon'=>'&#xe621;','id'=>'5','href'=>'/admin/photo/index/type/3'],
			['title'=>'合作伙伴','icon'=>'&#xe621;','id'=>'24','href'=>'/admin/photo/index/type/7'],
			['title'=>'合作机构','icon'=>'&#xe621;','id'=>'25','href'=>'/admin/photo/index/type/4'],
			['title'=>'单页内容','icon'=>'&#xe621;','id'=>'6','href'=>'/admin/content/index']
		]
	],
    [
        'id'=>'100','title'=>'区块链峰会管理','icon'=>'&#xe705;','spread'=>false,
        'children' => [
            ['title'=>'嘉宾列表','icon'=>'&#xe621;','id'=>'101','href'=>'/admin/photo/index/type/50'],
            ['title'=>'冠名','icon'=>'&#xe621;','id'=>'102','href'=>'/admin/photo/index/type/51'],
            ['title'=>'联合主办','icon'=>'&#xe621;','id'=>'102','href'=>'/admin/photo/index/type/52'],
            ['title'=>'战略合作','icon'=>'&#xe621;','id'=>'102','href'=>'/admin/photo/index/type/53'],
            ['title'=>'特邀媒体','icon'=>'&#xe621;','id'=>'102','href'=>'/admin/photo/index/type/54'],
            ['title'=>'媒体','icon'=>'&#xe621;','id'=>'102','href'=>'/admin/photo/index/type/55'],
        ]
    ],
	[
		'id'=>'18','title'=>'报名管理','icon'=>'&#xe650;','spread'=>false,
		'children' => [
			['title'=>'会员列表','icon'=>'&#xe621;','id'=>'19','href'=>'/admin/user/index']
		]
	],
	[
		'id'=>'7','title'=>'管理员管理','icon'=>'&#xe770;','spread'=>false,
		'children' => [
			['title'=>'管理员','icon'=>'&#xe621;','id'=>'8','href'=>'/admin/member/index'],
			['title'=>'角色管理','icon'=>'&#xe621;','id'=>'9','href'=>'/admin/member/role'],
			['title'=>'登录日志','icon'=>'&#xe621;','id'=>'28','href'=>'/admin/log/index']
		]
	],
	[
		'id'=>'20','title'=>'领票点管理','icon'=>'&#xe715;','spread'=>false,
		'children' => [
			['title'=>'领票点列表','icon'=>'&#xe621;','id'=>'21','href'=>'/admin/store/index'],
			['title'=>'领票点资源列表','icon'=>'&#xe621;','id'=>'29','href'=>'/admin/store/resource']
		]
	],
	[
		'id'=>'12','title'=>'Banner管理','icon'=>'&#xe600;','spread'=>false,
		'children' => [
			['title'=>'电脑端','icon'=>'&#xe621;','id'=>'13','href'=>'/admin/banner/index/type/1'],
			['title'=>'移动端','icon'=>'&#xe621;','id'=>'14','href'=>'/admin/banner/index/type/0']
		]
	],
	[
		'id'=>'10','title'=>'留言反馈','icon'=>'&#xe609;','spread'=>false,
		'children' => [
			['title'=>'自定义表单','icon'=>'&#xe621;','id'=>'11','href'=>'/admin/feedback/index']
		]
	],
	[
		'id'=>'15','title'=>'系统设置','icon'=>'&#xe620;','spread'=>false,
		'children' => [
			['title'=>'网站设置','icon'=>'&#xe621;','id'=>'16','href'=>'/admin/system/index'],
			['title'=>'友情链接','icon'=>'&#xe621;','id'=>'17','href'=>'/admin/system/linklist'],
			['title'=>'城市管理','icon'=>'&#xe621;','id'=>'22','href'=>'/admin/system/citylist'],
			['title'=>'短信管理','icon'=>'&#xe621;','id'=>'23','href'=>'/admin/system/message']
		]
	]
];
