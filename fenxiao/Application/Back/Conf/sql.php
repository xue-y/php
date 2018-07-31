<?php
/* sql 语句--- 数据库*/

	$create_table=array();
	/* 创建数据表结构*/
	$create_table[]="
	CREATE TABLE IF NOT EXISTS `{$prefix}limit` (
	   `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
		`n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
		`execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
		`pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
		 PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表' AUTO_INCREMENT=1 ;
	"; //表的结构 权限表

	$create_table[]="
	CREATE TABLE IF NOT EXISTS `{$prefix}role` (
		  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
		  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
		  `descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
		  `limit_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID',
		  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表' AUTO_INCREMENT=1 ;
	";//--表的结构 角色

	$create_table[]="
	CREATE TABLE IF NOT EXISTS `{$prefix}user` (
		  `id` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '用户编号',
		  `u_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名称',
		  `u_pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
		  `bumen` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '人员所在部门',
		  `role_id` tinyint(2) unsigned NOT NULL COMMENT '用户角色ID',
		  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录时间',
		  `found` smallint(5) unsigned zerofill NOT NULL COMMENT '用户创建人,部位管理人员',
		  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用于忘记密码时用户找回密码',
		  `is_jihuo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '邮箱是否激活',
		  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=1 ;
	";//----------------------- 管理员
	
	$create_table[]="CREATE TABLE IF NOT EXISTS `{$prefix}user_fj` (
	  `id` smallint(5) unsigned zerofill NOT NULL COMMENT '管理id' UNIQUE KEY,
	  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
	  `wx` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '微信号',
	  `info` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '个人说明',
	  `meg` tinyint(4) NOT NULL COMMENT '消息通知个数',
	   constraint `fj_user` foreign key(`id`) references `{$prefix}user`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员信息附加表';";
	//-------------------------- 管理员附加信息表
	
	$create_table[]="
	CREATE TABLE IF NOT EXISTS `{$prefix}cus_base` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户id',
	  `phone` char(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
	  `pass` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
	  `is_del` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否删除,删除为1',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='客户登录表' AUTO_INCREMENT=1 ;
	";// -----------------------客户登录表 my_cus_base
	
	$create_table[]="CREATE TABLE IF NOT EXISTS `{$prefix}cus_detailed` (
	  `id` smallint(5)  unsigned NOT NULL COMMENT '客户id PRIMARY KEY',
	  `cid` smallint(5) unsigned zerofill NOT NULL COMMENT '客户所属咨询师id',
	  `tid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '客户的推荐人id',
	  `wx` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '客户微信号',
	  `n` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '客户名称',
	  `is_wx` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '客户微信是否验证，验证后为1',
	  `money` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '佣金',
	  `t` datetime NOT NULL COMMENT '注册时间',
	  `age` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '客户年龄',
	  `sex` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT '客户性别',
	  `openid` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户openid',
      `headimg` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户头像',
	  `descr` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户备注',
	  constraint `detailed_base` foreign key(`id`) references `{$prefix}cus_base`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料详细表'"
	;//---------------------------客户资料表my_cus_detailed 微信号是否验证 验证通过1 未验证未0 默认未0
	
	$create_table[]="CREATE TABLE IF NOT EXISTS `{$prefix}cus_downline` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '推荐id',
	  `tid` smallint(5) unsigned NOT NULL COMMENT '推荐人id',
	  `phone` char(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '下线手机号',
	  `wx` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '下线微信号',
	  `n` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '下线姓名',
	  `age` tinyint(3) unsigned NOT NULL COMMENT '推荐下线的年龄',
	  `sex` varchar(3) COLLATE utf8_unicode_ci NOT NULL COMMENT '推荐下线的性别',
	  `t` datetime NOT NULL COMMENT '推荐时间',
	  `remarks` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '备注说明',
	  `cid` smallint(5) unsigned zerofill NOT NULL COMMENT '推荐人的咨询师id',
	  `state` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '审核状态',
	  `s_t` datetime NOT NULL COMMENT '审核时间',
	  `descr` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '审核说明',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推荐下线表' AUTO_INCREMENT=1 ;
	"; //------------------------- 客户推荐下线表 审核状态 默认1 为审核 2 通过审核 3 未通过审核
	
	$create_table[]="CREATE TABLE IF NOT EXISTS `{$prefix}cus_info` (
	  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息通知id',
	  `cid` smallint(5) unsigned NOT NULL COMMENT '客户id',
	  `easy` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '消息简介',
	  `con` varchar(253) COLLATE utf8_unicode_ci NOT NULL COMMENT '消息内容',
	  `is_read` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '用户是否读取,默认1',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='客户消息通知表' AUTO_INCREMENT=1 ;";
	// ------------------------------客户消息表 消息客户是否读取 默认未1， 读取为2
	
	$create_table[]="CREATE TABLE IF NOT EXISTS `{$prefix}cus_money` (
	  `id` smallint(5) unsigned NOT NULL COMMENT '客户id',
	  `cid` smallint(5) unsigned zerofill NOT NULL COMMENT '操作员id',
	  `num` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作金额',
	  `jine` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '每次修改后的金额',
	  `t` datetime NOT NULL COMMENT '操作时间',
	  `info` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作说明',
	  UNIQUE KEY `t` (`t`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='佣金记录表' AUTO_INCREMENT=1 ;;";
	//----------------------------客户佣金表
	
	
	$add_limit=array(); // 添加权限数据
	$add_limit="INSERT INTO `{$prefix}limit` (`id`, `n`, `execs`, `pid`) VALUES
		(1, '系统管理', '', 1),
		(2, '管理员管理', '', 2),
		(3, '客户管理', '', 3),
		(4, '系统设置', 'sysset', 1),
		(5, '系统设置首页', 'sysset-index', 1),
		(7, '用户管理', 'user', 2),
		(8, '用户列表', 'user-index', 2),
		(9, '添加用户', 'user-add', 2),
		(10, '添加用户', 'user-execAdd', 2),
		(11, '修改用户', 'user-update', 2),
		(12, '修改用户', 'user-execUate', 2),
		(13, '删除用户', 'user-del', 2),
		(14, '角色管理', 'role', 2),
		(15, '角色列表', 'role-index', 2),
		(16, '添加角色', 'role-add', 2),
		(17, '添加角色', 'role-execAdd', 2),
		(18, '修改角色', 'role-update', 2),
		(19, '修改角色', 'role-execUate', 2),
		(20, '删除角色', 'role-del', 2),
		(21, '权限管理', 'limit', 2),
		(22, '权限列表', 'limit-index', 2),
		(23, '用户信息', 'personal', 2),
		(24, '个人资料', 'personal-index', 2),
		(25, '客户管理', 'customer', 3),
		(26, '客户列表', 'customer-index', 3),
		(27, '添加客户', 'customer-add', 3),
		(28, '添加客户', 'customer-execAdd', 3),
		(29, '修改客户', 'customer-update', 3),
		(30, '修改客户', 'customer-execUate', 3),
		(31, '删除客户', 'customer-del', 3),
		(32, '客户回收站', 'recovery', 3),
		(33, '回收站列表', 'recovery-index', 3),
		(34, '客户还原', 'recovery-restore', 3),
		(35, '客户删除', 'recovery-del', 3),
		(36, '客户下线', 'line', 3),
		(37, '客户审查', 'line-execEnsor', 3),
		(38, '客服消息', 'info', 3),
		(39, '下线列表', 'line-index', 3),
		(40, '消息列表', 'line-index', 3),
		(41, '客户审查', 'line-censor', 3),
		(42, '客户佣金', 'money', 3),
		(43, '佣金列表', 'money-index', 3),
		(44, '修改佣金', 'money-update', 3),
		(45, '修改佣金', 'money-execUate', 3),
        (46, '文件管理', 'file', 1),
        (47, '文件管理首页', 'file-index', 1),
        (48, '文件清理', 'file-file', 1)";
		
	$add_role="INSERT INTO `{$prefix}role`( `n`, `descr`, `limit_id`) VALUES ('超级管理员','拥有所有权限','-1')";// 添加角色数据
	$add_user="INSERT INTO `{$prefix}user` (`u_name`, `u_pass`, `bumen`, `role_id`,`found`) VALUES ('$u_name','$u_pass', '$bu_men', 1,'00001')";// 添加管理员
	$add_user_fj="INSERT INTO `{$prefix}user_fj` (`id`) VALUES ('00001')"; // 管理员附加表

	/*$referencse_del="SET FOREIGN_KEY_CHECKS =0";// 先解除外键约束
	
	$references_set="SET FOREIGN_KEY_CHECKS =1"; //从新设置外键、创建约束
	*/
		
		
		