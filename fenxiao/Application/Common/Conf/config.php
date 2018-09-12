<?php
       return array(
            //'配置项'=>'配置值'
            'URL_MODEL' =>2, //REWRITE模式
            'DB_TYPE'   => 'mysql', // 数据库类型
            'DB_HOST'   => 'localhost', // 服务器地址
            'DB_USER'   => 'root', // 用户名
            'DB_PWD'    => 'admin', // 密码
            'DB_NAME'   => 'back', // 数据库名
            'DB_PREFIX' => 'self_', // 数据库表前缀
            'DB_PORT'   => '3306', // 端口
            'DB_CHARSET'=> 'utf8', // 字符集
            'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
            'PWD_PREFIX'=> '&^*(*&(@..!', //密码加密秘钥
            'DEFAULT_TIMEZONE'=>'PRC', //设置时区时间
            'URL_CASE_INSENSITIVE' =>FALSE,  //url 区分大小写
		//	'TMPL_EXCEPTION_FILE' => 'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/404.html',//空模块--空控制器---空方法 调试阶段起作用
		//	'URL_404_REDIRECT' =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/404.html', // 404 跳转页面 部署模式
		//	'ERROR_PAGE' =>'http://'.$_SERVER['HTTP_HOST'].__ROOT__.'/404.html', // 错误定向页面  部署模式
         //   'DB_FIELDS_CACHE'=>TRUE,  //启用字段缓存 默认false 关闭缓存
           'SHOW_ERROR_MSG'=> FALSE, //  关闭显示错误信息 默认true 开启

        );