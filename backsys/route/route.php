<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// 定义路由
use think\facade\Route;

Route::resource("back/user","/back/admin/User");
Route::resource("back/power","/back/admin/Power");
Route::resource("back/role","/back/admin/Role");
return [
   // 'AuthCheck'	=>	app\back\http\middleware\LoginCheck::class,
];
