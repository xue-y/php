<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

ini_set('session.gc_maxlifetime',"36000"); //设置时间

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',FALSE);
// 定义应用目录
define('APP_PATH','./Application/');

if(!isset($_GET['m']) || empty($_GET['m']))
    $_GET['m'] = 'Home';

if(!isset($_GET['c']) || empty($_GET['c']))
    $_GET['c'] = 'Login';

if(!isset($_GET['a']) || empty($_GET['a']))
    $_GET['a'] = 'sign';

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单