<?php
define("L_A_D_F",APP_PATH."Back/Conf/data.php");//权限 超级管理员 数据 文件
define("L_O_D_F",APP_PATH."Back/Cache/");  //权限 普通用户 数据 文件
define("LOG_F",LOG_PATH."error.txt");  //错误日志文件
define("L_MENU","menu");
define("L_ACTION","action");
define("L_ALL","limit_all"); //------------直接调用权限数组： 菜单 方法 全部
define("L_RES","result_"); //--------------取得权限数组
define('S_MAIL_TYPE','HTML');//邮件格式（HTML/TXT）,TXT为文本邮件*/
define("MAIL_DENY_T",60);//  发送邮件间隔秒数
define("USER_LOGIN_T",36000);// 用户登录保存时间

require "define.php"; // 引入自定义常量
/* back 模快 配置*/
return array(
	//'配置项'=>'配置值'
    'LOG_RECORD'            =>  TRUE,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  =>  TRUE,    // 是否记录异常信息日志
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR,WARN,NOTIC',  // 允许记录的日志级别
    'SHOW_PAGE_TRACE'=>true,// 开启追踪日志
    'SESSION_PREFIX'=>'my_',  // session　前缀
    'COOKIE_PREFIX'=>"my_",  // cookie 前缀
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => ':tips',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => ':tips',
 	//'SHOW_PAGE_TRACE'=>TRUE,  // 跟踪日志
    'DEFAULT_CONTROLLER' => 'Login', // 默认控制器名称
    'DEFAULT_ACTION' => 'sign', // 默认操作名称
    'LAYOUT_NAME'=>'layout',
    'LOG_TYPE' => 'File', // 日志记录类型 默认为文件方式
);