<?php
define('ZIXUN',2); //咨询角色id
define('P_O_C',10); //分页 一页 数量
define('LINE_NUM',20);//限制用户一天最多推荐不得超过20 个
return array(
	//'配置项'=>'配置值'
    'SESSION_PREFIX'=>'cus_',
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => ':tips',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => ':tips',
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
    'DEFAULT_CONTROLLER' => 'Login', // 默认控制器名称
    'DEFAULT_ACTION' => 'sign', // 默认操作名称
   // 'SHOW_PAGE_TRACE'=>true,  // 跟踪日志
);