-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 �?06 �?20 �?08:43
-- 服务器版本: 5.5.53-log
-- PHP 版本: 5.5.38

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `rwy`
--

-- --------------------------------------------------------

--
-- 表的结构 `rw_limit`
--

CREATE TABLE IF NOT EXISTS `rw_limit` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限名称',
  `execs` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限的类与方法',
  `pid` tinyint(4) unsigned NOT NULL COMMENT '权限分组',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表' AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `rw_limit`
--

INSERT INTO `rw_limit` (`id`, `n`, `execs`, `pid`) VALUES
(1, '系统管理', '', 1),
(2, '管理员管理', '', 2),
(3, '任务管理', '', 3),
(4, '系统设置', 'sysset', 1),
(5, '系统设置首页', 'sysset-index', 1),
(6, '清理文件', 'sysset-oldf', 1),
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
(25, '任务管理', 'task', 3),
(26, '任务列表', 'task-index', 3),
(27, '添加任务', 'task-add', 3),
(28, '添加任务', 'task-execAdd', 3),
(29, '修改任务', 'task-update', 3),
(30, '修改任务', 'task-execUate', 3),
(31, '删除任务', 'task-del', 3),
(32, '执行任务', 'task-ute', 3),
(33, '执行任务', 'task-execUte', 3),
(34, '任务统计', 'task-count', 3),
(35, '回收站', 'recovery', 3),
(36, '回收站列表', 'recovery-index', 3),
(37, '任务还原', 'recovery-restore', 3),
(38, '任务删除', 'recovery-del', 3);

-- --------------------------------------------------------

--
-- 表的结构 `rw_problem`
--

CREATE TABLE IF NOT EXISTS `rw_problem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '问题ID',
  `tit` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '问题名称',
  `descr` text COLLATE utf8_unicode_ci NOT NULL COMMENT '问题描述',
  `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '提问题人员ID',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否解决,0未解决,1解决',
  `isdel` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题是否删除,0未删除,1删除',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `rw_problem`
--

INSERT INTO `rw_problem` (`id`, `tit`, `descr`, `u_id`, `state`, `isdel`, `times`) VALUES
(1, 'sdfdsfds', 'sdfdsfdsfds', 00001, 1, 0, '1507894224'),
(2, 'sdfsdfdsf', 'sdfdsfdsf', 00001, 0, 0, '1510210704'),
(3, 'sdfdsfsd', 'sdfdsfds', 00001, 0, 0, '1510210711'),
(4, 'dsfdsfsf', '<img src=\\"/attached/image/201712/1514168281.jpg\\" alt=\\"\\" />', 00001, 0, 0, '1514168282');

-- --------------------------------------------------------

--
-- 表的结构 `rw_role`
--

CREATE TABLE IF NOT EXISTS `rw_role` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `n` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色描述',
  `limit_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `rw_role`
--

INSERT INTO `rw_role` (`id`, `n`, `descr`, `limit_id`) VALUES
(1, '超级管理员', '拥有所有权限', '-1'),
(2, 'pu', 'fdsfdsfdsf', '2,7,8,9,10,11,12,13,14,15,21,22,23,24,3,25,26,27,28,29,30,31,32,33,34,35,36,37,38'),
(3, 'you', '', '2,7,8,14,15,21,22,23,24');

-- --------------------------------------------------------

--
-- 表的结构 `rw_task`
--

CREATE TABLE IF NOT EXISTS `rw_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务表ID',
  `p_id` int(11) unsigned NOT NULL COMMENT '问题ID',
  `u_id` smallint(5) unsigned zerofill NOT NULL COMMENT '执行任务人员编号',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '时间',
  `state` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否验证,0未验证,1通过验证,2未通过',
  `plan` text COLLATE utf8_unicode_ci NOT NULL COMMENT '解决方法',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='执行任务表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `rw_task`
--

INSERT INTO `rw_task` (`id`, `p_id`, `u_id`, `times`, `state`, `plan`) VALUES
(1, 1, 00002, '1510208488', '1', 'sdfdsfdsf'),
(2, 3, 00002, '1510210739', '2', 'sdfdsfsdfdsf');

-- --------------------------------------------------------

--
-- 表的结构 `rw_user`
--

CREATE TABLE IF NOT EXISTS `rw_user` (
  `id` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT COMMENT '用户编号',
  `u_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名称',
  `u_pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `bumen` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '人员所在部门',
  `role_id` tinyint(2) unsigned NOT NULL COMMENT '用户角色ID',
  `times` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '登录时间',
  `found` smallint(5) unsigned zerofill NOT NULL COMMENT '用户创建人,部位管理人员',
  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用于忘记密码时用户找回密码',
  `is_jihuo` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '邮箱是否激活',
  `meg` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户信息提醒个数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `rw_user`
--

INSERT INTO `rw_user` (`id`, `u_name`, `u_pass`, `bumen`, `role_id`, `times`, `found`, `mail`, `is_jihuo`, `meg`) VALUES
(00001, 'admin', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '不受限', 1, '1529483905', 00001, '1922527784@qq.com', '1', 0),
(00002, 'pu', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '办公室', 2, '1510210729', 00001, '', '0', 0),
(00003, '123', 'be33d98a0d9f0dfc2d4b52f64a4603a2', '办公室', 3, '1507955808', 00001, '', '0', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
