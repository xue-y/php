<?php
define("L_A_D_F","./Application/Back/Conf/data.php");//权限 超级管理员 数据 文件
define("L_O_D_F","./Application/Back/Cache/");  //权限 普通用户 数据 文件
define("LOG_F","./Application/Log/admin.contrroll.txt");  //错误日志文件
define("L_MENU","menu");
define("L_ACTION","action");
define("L_ALL","limit_all"); //------------直接调用权限数组： 菜单 方法 全部
define("L_RES","result_"); //--------------取得权限数组
define("TAST","Tast");//------------------取得快捷操作下所有的方法
define("PRO_W","pro_warn"); //---------------用户是否有添加任务的权限
define("TAST_W","task_warn");//---------------用户是否有执行任务的权限
define("PRO_W_N",0); // 消息反馈个数
define("TAST_W_N",0);// 任务个数
define("LOCK_F","./Application/Back/Conf/lock.txt"); //--------------添加任务时的锁文件
define("OLD_PIC",'./attached/image/'); // 清理图片路径
define('S_MAIL_TYPE','HTML');//邮件格式（HTML/TXT）,TXT为文本邮件*/
define("MAIL_DENY_T",60);//  发送邮件间隔秒数

require "define.php"; // 引入自定义常量

return array(
	//'配置项'=>'配置值'
    'LOG_RECORD'            =>  TRUE,  // 进行日志记录
    'LOG_EXCEPTION_RECORD'  =>  TRUE,    // 是否记录异常信息日志
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',  // 允许记录的日志级别
    'LOG_PATH' =>'/log/',  //日志存放位置
    'SHOW_PAGE_TRACE'=>FALSE,// 开启追踪日志
    'SESSION_PREFIX'=>'my_',
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => ':tips',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => ':tips',

/*    'LAYOUT_ON'=>TRUE,  //全局模板布局
    'LAYOUT_NAME'=>'layout'*/
);