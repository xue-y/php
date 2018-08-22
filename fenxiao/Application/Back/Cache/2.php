<?php 
  header('Content-type: text/html; charset=utf-8'); 
 $limit_all=array (
  'menu' => 
  array (
    0 => 
    array (
      'id' => '2',
      'n' => '管理员管理',
      'execs' => '',
      'pid' => '2',
    ),
    1 => 
    array (
      'id' => '7',
      'n' => '用户管理',
      'execs' => 'User',
      'pid' => '2',
    ),
    2 => 
    array (
      'id' => '21',
      'n' => '权限管理',
      'execs' => 'Limit',
      'pid' => '2',
    ),
    3 => 
    array (
      'id' => '23',
      'n' => '用户信息',
      'execs' => 'Personal',
      'pid' => '2',
    ),
    4 => 
    array (
      'id' => '3',
      'n' => '客户管理',
      'execs' => '',
      'pid' => '3',
    ),
    5 => 
    array (
      'id' => '25',
      'n' => '客户管理',
      'execs' => 'Customer',
      'pid' => '3',
    ),
    6 => 
    array (
      'id' => '32',
      'n' => '客户回收站',
      'execs' => 'Recovery',
      'pid' => '3',
    ),
    7 => 
    array (
      'id' => '36',
      'n' => '客户下线',
      'execs' => 'Line',
      'pid' => '3',
    ),
    8 => 
    array (
      'id' => '38',
      'n' => '客服消息',
      'execs' => 'Info',
      'pid' => '3',
    ),
    9 => 
    array (
      'id' => '42',
      'n' => '客户佣金',
      'execs' => 'Money',
      'pid' => '3',
    ),
  ),
  'action' => 
  array (
    0 => 
    array (
      'id' => '8',
      'n' => '用户列表',
      'execs' => 'User-index',
      'pid' => '2',
    ),
    1 => 
    array (
      'id' => '9',
      'n' => '添加用户',
      'execs' => 'User-add',
      'pid' => '2',
    ),
    2 => 
    array (
      'id' => '10',
      'n' => '添加用户',
      'execs' => 'User-execAdd',
      'pid' => '2',
    ),
    3 => 
    array (
      'id' => '11',
      'n' => '修改用户',
      'execs' => 'User-update',
      'pid' => '2',
    ),
    4 => 
    array (
      'id' => '12',
      'n' => '修改用户',
      'execs' => 'User-execUate',
      'pid' => '2',
    ),
    5 => 
    array (
      'id' => '13',
      'n' => '删除用户',
      'execs' => 'User-del',
      'pid' => '2',
    ),
    6 => 
    array (
      'id' => '22',
      'n' => '权限列表',
      'execs' => 'Limit-index',
      'pid' => '2',
    ),
    7 => 
    array (
      'id' => '24',
      'n' => '个人资料',
      'execs' => 'Personal-index',
      'pid' => '2',
    ),
    8 => 
    array (
      'id' => '26',
      'n' => '客户列表',
      'execs' => 'Customer-index',
      'pid' => '3',
    ),
    9 => 
    array (
      'id' => '27',
      'n' => '添加客户',
      'execs' => 'Customer-add',
      'pid' => '3',
    ),
    10 => 
    array (
      'id' => '28',
      'n' => '添加客户',
      'execs' => 'Customer-execAdd',
      'pid' => '3',
    ),
    11 => 
    array (
      'id' => '29',
      'n' => '修改客户',
      'execs' => 'Customer-update',
      'pid' => '3',
    ),
    12 => 
    array (
      'id' => '30',
      'n' => '修改客户',
      'execs' => 'Customer-execUate',
      'pid' => '3',
    ),
    13 => 
    array (
      'id' => '31',
      'n' => '删除客户',
      'execs' => 'Customer-del',
      'pid' => '3',
    ),
    14 => 
    array (
      'id' => '33',
      'n' => '回收站列表',
      'execs' => 'Recovery-index',
      'pid' => '3',
    ),
    15 => 
    array (
      'id' => '34',
      'n' => '客户还原',
      'execs' => 'Recovery-restore',
      'pid' => '3',
    ),
    16 => 
    array (
      'id' => '35',
      'n' => '客户删除',
      'execs' => 'Recovery-del',
      'pid' => '3',
    ),
    17 => 
    array (
      'id' => '37',
      'n' => '客户审查',
      'execs' => 'Line-execEnsor',
      'pid' => '3',
    ),
    18 => 
    array (
      'id' => '39',
      'n' => '下线列表',
      'execs' => 'Line-index',
      'pid' => '3',
    ),
    19 => 
    array (
      'id' => '40',
      'n' => '消息列表',
      'execs' => 'Line-index',
      'pid' => '3',
    ),
    20 => 
    array (
      'id' => '41',
      'n' => '客户审查',
      'execs' => 'Line-censor',
      'pid' => '3',
    ),
    21 => 
    array (
      'id' => '43',
      'n' => '佣金列表',
      'execs' => 'Money-index',
      'pid' => '3',
    ),
    22 => 
    array (
      'id' => '44',
      'n' => '修改佣金',
      'execs' => 'Money-update',
      'pid' => '3',
    ),
    23 => 
    array (
      'id' => '45',
      'n' => '修改佣金',
      'execs' => 'Money-execUate',
      'pid' => '3',
    ),
  ),
);