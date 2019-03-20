<?php
define('URL_CALLBACK', 'http://www.xxx.com/home/oauth/callback/type/');
//第三方登录
return [
	//微信登录
    'LOGIN_SDK_WEIXIN' => [
        'APP_KEY' => '', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'weixin',
    ],
    //腾讯QQ登录配置
    'LOGIN_SDK_QQ' => [
        'APP_KEY' => '1106533743', //应用注册成功后分配的 APP ID
        'APP_SECRET' => '0w8VRSruHN7xM3Rw', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'qq',
    ],
    //新浪微博配置
    'LOGIN_SDK_SINA' => [
        'APP_KEY' => '2080418527', //应用注册成功后分配的 APP ID
        'APP_SECRET' => 'e775f934f048f0a4e18f012635cdbec2', //应用注册成功后分配的KEY
        'CALLBACK' => URL_CALLBACK . 'sina',
    ]
];
