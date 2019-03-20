<?php
use think\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//Route::domain('wx.com','admin');
return [
	'news' => 'home/news/index',
    'shownews/:id' => 'home/news/detail',
    'page/:id' => 'home/page/index',
    'guest' => 'home/guest/index',
    'partner/:id' => 'home/partner/index',
    'place' => 'home/address/index',
    'address/:id' => 'home/address/index',
    'online' => 'home/online/index',
    'sign'  => 'home/login/index',
    'my'     => 'home/user/my',
    'person'     => 'home/user/person',
    'edit'     => 'home/user/edit',
    'forget'     => 'home/login/forget',
    
	//移动端
    'mobile/news' => 'mobile/news/index',
    'mobile/shownews/:id' => 'mobile/news/detail',
    'mobile/page/:id' => 'mobile/page/index',
    'mobile/guest' => 'mobile/guest/index',
    'mobile/partner/:id' => 'mobile/partner/index',
    'mobile/place' => 'mobile/address/index',
    'mobile/address/:id' => 'mobile/address/index',
    'mobile/online' => 'mobile/online/index',
    'mobile/login'  => 'mobile/login/index',
    'mobile/logout'  => 'mobile/login/logout',
    'mobile/my'     => 'mobile/user/my',
    'mobile/person' => 'mobile/user/person',
    'mobile/edit'   => 'mobile/user/edit',
    'mobile/ticket' => 'mobile/user/ticket',
    'mobile/invite'   => 'mobile/user/invite',
    'mobile/share'   => 'mobile/user/share',
    'mobile/forget'     => 'mobile/login/forget',  
];
