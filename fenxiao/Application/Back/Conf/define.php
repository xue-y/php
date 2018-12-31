<?php
define('QUICK',3);// 快捷操作
define('ZIXUN',2); //咨询角色id
define('P_R_ID',3); // 普通管理员角色ID--可以添加用户的管理员
define('P_R_ID_C','4');// 普通管理员可以添加的角色[下级]
define('A_S_A',FALSE);//是否允许添加多个超级管理员 ,false 不允许
define('A_S_C',FALSE);// 普通管理员是否允许添加其他部门人员 false 不允许
define('P_O_C',10); //分页 一页 数量 --- 验证邮箱发送次数
define('DENY_L_ID','1'); //除超级管理员拒绝其他用户访问的权限 一级id /二级id /三级ID
define('WBE_NAME','管理系统'); //系统名称
define('T_INT',420); //任务提示时间间隔 ---时间
define('S_SERVER','');//SMTP服务器
define('S_PORT',25);//SMTP服务器端口
define('S_MAIL','');//SMTP服务器的用户邮箱
define('S_USER','');//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
define('S_PASS','');//SMTP服务器的用户密码
define("PASS_DE",111111);// 客户默认密码