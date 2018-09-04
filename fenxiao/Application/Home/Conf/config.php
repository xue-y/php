<?php
define('ZIXUN',2); //咨询角色id
define('P_O_C',10); //分页 一页 数量
define('LINE_NUM',20);//限制用户一天最多推荐不得超过20 个
define('U_HEAD_IMG',"/Public/headimg/"); // 用户头像文件夹
define("U_HEAD_DE","/Public/back/images/user.png");// 默认用户头像
define('WX_QR',"/Public/wximg/"); // 微信二维吗文件夹
define("WX_LOGIN","login");// wx 自定义标识
define("WX_PASS","pass");
define("WX_VALIWX","valiwx");
define("LOG_F",LOG_PATH."error.txt");  //错误日志文件
define("FEN_FU","_"); // 字段分割符
// 页面限制用户频繁刷新
define("REFRESH_C",8); //刷新次数
define("REFRESH_T",5);// 刷新时间间隔
define("REFRESH_CT",60); //累计刷新次数 存储时间限制 1 分钟
define("USER_LOGIN_T",36000);// 用户登录保存时间

define("ORDER_T",3600);// 订单有效期 1 个小时

return array(
	//'配置项'=>'配置值'
    'SESSION_PREFIX'=>'cus_',
    'COOKIE_PREFIX'=>'cus_',
    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => ':tips',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => ':tips',
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',
    'DEFAULT_CONTROLLER' => 'Login', // 默认控制器名称
    'DEFAULT_ACTION' => 'sign', // 默认操作名称
    'SHOW_PAGE_TRACE'=>false,  // 跟踪日志
    'LOG_TYPE' => 'File', // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,DEBUG,SQL',  // 允许记录的日志级别
);