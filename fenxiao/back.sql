-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 �?09 �?12 �?09:19
-- 服务器版本: 5.5.53-log
-- PHP 版本: 5.5.38

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `back`
--

-- --------------------------------------------------------

--
-- 表的结构 `self_cus_base`
--

CREATE TABLE IF NOT EXISTS `self_cus_base` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '客户id',
  `phone` char(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `pass` char(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `is_del` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否删除,删除为1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='客户登录表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `self_cus_base`
--

INSERT INTO `self_cus_base` (`id`, `phone`, `pass`, `is_del`) VALUES
(1, '12345678915', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '0'),
(2, '12345678913', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '0'),
(4, '', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '0'),
(5, '12345612345', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '0');

-- --------------------------------------------------------

--
-- 表的结构 `self_cus_detailed`
--

CREATE TABLE IF NOT EXISTS `self_cus_detailed` (
  `id` smallint(5) unsigned NOT NULL COMMENT '客户id PRIMARY KEY',
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
  KEY `detailed_base` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料详细表';

--
-- 转存表中的数据 `self_cus_detailed`
--

INSERT INTO `self_cus_detailed` (`id`, `cid`, `tid`, `wx`, `n`, `is_wx`, `money`, `t`, `age`, `sex`, `openid`, `headimg`, `descr`) VALUES
(1, 00001, 0, '2342', 'aaaa', '0', '20', '2018-06-21 14:35:19', 0, '女', '', '', ''),
(2, 00001, 0, '12345', 'bb31', '0', '0', '2018-06-21 14:38:18', 0, '男', '', '', ''),
(4, 00001, 0, 'sdfdsf', 'sdfdsf', '0', '0', '2018-08-06 13:16:16', 0, '女', '', '', ''),
(5, 00002, 0, '', 'sdf', '0', '0', '2018-08-07 12:26:31', 0, '女', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `self_cus_downline`
--

CREATE TABLE IF NOT EXISTS `self_cus_downline` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='推荐下线表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `self_cus_downline`
--

INSERT INTO `self_cus_downline` (`id`, `tid`, `phone`, `wx`, `n`, `age`, `sex`, `t`, `remarks`, `cid`, `state`, `s_t`, `descr`) VALUES
(1, 1, '15254123441', '2323', 'dsfdf', 0, '女', '2018-06-21 14:44:47', '        ', 00001, '3', '2018-08-03 10:05:35', '33333'),
(3, 2, '', 'sdf', 'dsfdsf', 0, '女', '2018-08-03 14:56:37', '    ', 00001, '3', '2018-08-03 15:00:17', ''),
(4, 5, '', 'sdfdsfdsf', 'sdfdsf', 0, '女', '2018-08-07 12:32:42', '    ', 00002, '1', '0000-00-00 00:00:00', ''),
(5, 5, '12345612127', 'xcvfd', 'dfsd', 0, '女', '2018-09-12 15:08:48', '        ', 00002, '1', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- 表的结构 `self_cus_info`
--

CREATE TABLE IF NOT EXISTS `self_cus_info` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息通知id',
  `cid` smallint(5) unsigned NOT NULL COMMENT '客户id',
  `easy` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '消息简介',
  `con` varchar(253) COLLATE utf8_unicode_ci NOT NULL COMMENT '消息内容',
  `is_read` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '用户是否读取,默认1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='客户消息通知表' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `self_cus_info`
--

INSERT INTO `self_cus_info` (`id`, `cid`, `easy`, `con`, `is_read`) VALUES
(1, 1, '<b>[ aaaa ]</b> 用户佣金 <b>-20</b> ', '<b>[ aaaa ]</b> 用户，由管理员 <b>admin</b> 在 <b>2018-07-23 17:32:12</b> 时间更新佣金 <b>-20</b>\r\n		<br/>佣金操作说明: dsfdsfds', '1'),
(2, 1, '<b>[ aaaa ]</b> 用户佣金 <b>30</b> ', '<b>[ aaaa ]</b> 用户，由管理员 <b>admin</b> 在 <b>2018-07-23 17:32:56</b> 时间更新佣金 <b>30</b>\r\n		<br/>佣金操作说明: sdfsf', '1'),
(3, 2, '<b>您推荐的[ dsfdsfs ]</b> 用户 <b>未通过</b> 审核', '<b>您推荐的[ dsfdsfs ]</b> 用户，由管理员 <b>admin</b> 在 <b></b> 时间 <b>未通过</b> 审核\r\n		<br/> 审核说明: sdfdsfsf', '2'),
(4, 1, '<b>您推荐的[ dsfdf ]</b> 用户 <b>未通过</b> 审核', '<b>您推荐的[ dsfdf ]</b> 用户，由管理员 <b>admin</b> 在 <b>2018-08-03 10:05:35</b> 时间 <b>未通过</b> 审核\r\n		<br/> 审核说明: 33333', '1'),
(5, 2, '<b>您推荐的[ dsfdsf ]</b> 用户 <b>未通过</b> 审核', '<b>您推荐的[ dsfdsf ]</b> 用户，由管理员 <b>admin</b> 在 <b>2018-08-03 15:00:17</b> 时间 <b>未通过</b> 审核\r\n		<br/> 审核说明: ', '2'),
(6, 1, '<b>[ aaaa ]</b> 用户佣金 <b>10</b> ', '<b>[ aaaa ]</b> 用户，由管理员 <b>admin</b> 在 <b>2018-09-12 16:09:05</b> 时间更新佣金 <b>10</b>\r\n		<br/>佣金操作说明: 测试加10', '1');

-- --------------------------------------------------------

--
-- 表的结构 `self_cus_money`
--

CREATE TABLE IF NOT EXISTS `self_cus_money` (
  `id` smallint(5) unsigned NOT NULL COMMENT '客户id',
  `cid` smallint(5) unsigned zerofill NOT NULL COMMENT '操作员id',
  `num` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作金额',
  `jine` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '每次修改后的金额',
  `t` datetime NOT NULL COMMENT '操作时间',
  `info` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT '操作说明',
  UNIQUE KEY `t` (`t`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='佣金记录表';

--
-- 转存表中的数据 `self_cus_money`
--

INSERT INTO `self_cus_money` (`id`, `cid`, `num`, `jine`, `t`, `info`) VALUES
(1, 00001, '-20', '-20', '2018-07-23 17:32:12', 'dsfdsfds'),
(1, 00001, '30', '10', '2018-07-23 17:32:56', 'sdfsf'),
(1, 00001, '10', '20', '2018-09-12 16:09:05', '测试加10');

-- --------------------------------------------------------

--
-- 表的结构 `self_limit`
--

CREATE TABLE IF NOT EXISTS `self_limit` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
  `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表' AUTO_INCREMENT=49 ;

--
-- 转存表中的数据 `self_limit`
--

INSERT INTO `self_limit` (`id`, `n`, `execs`, `pid`) VALUES
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
(48, '文件清理', 'file-file', 1);

-- --------------------------------------------------------

--
-- 表的结构 `self_role`
--

CREATE TABLE IF NOT EXISTS `self_role` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
  `limit_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `self_role`
--

INSERT INTO `self_role` (`id`, `n`, `descr`, `limit_id`) VALUES
(1, '超级管理员', '拥有所有权限', '-1'),
(2, '主管', '', '2,7,8,9,10,11,12,13,21,22,23,24,3,25,26,27,28,29,30,31,32,33,34,35,36,37,39,40,41,38,42,43,44,45');

-- --------------------------------------------------------

--
-- 表的结构 `self_user`
--

CREATE TABLE IF NOT EXISTS `self_user` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `self_user`
--

INSERT INTO `self_user` (`id`, `u_name`, `u_pass`, `bumen`, `role_id`, `times`, `found`, `mail`, `is_jihuo`) VALUES
(00001, 'admin', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '不受限', 1, '1536740914', 00001, '1922527784@qq.com', '1'),
(00002, 'aaa', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '主管', 2, '1533616050', 00001, '', '0');

-- --------------------------------------------------------

--
-- 表的结构 `self_user_fj`
--

CREATE TABLE IF NOT EXISTS `self_user_fj` (
  `id` smallint(5) unsigned zerofill NOT NULL COMMENT '管理id',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `wx` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '微信号',
  `info` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '个人说明',
  `meg` tinyint(4) NOT NULL COMMENT '消息通知个数',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员信息附加表';

--
-- 转存表中的数据 `self_user_fj`
--

INSERT INTO `self_user_fj` (`id`, `phone`, `wx`, `info`, `meg`) VALUES
(00001, '', '', '', -1),
(00002, '', '', '☆☆☆☆☆ 颜字符φ(>ω<*)', 2);

--
-- 限制导出的表
--

--
-- 限制表 `self_cus_detailed`
--
ALTER TABLE `self_cus_detailed`
  ADD CONSTRAINT `detailed_base` FOREIGN KEY (`id`) REFERENCES `self_cus_base` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `self_user_fj`
--
ALTER TABLE `self_user_fj`
  ADD CONSTRAINT `fj_user` FOREIGN KEY (`id`) REFERENCES `self_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
