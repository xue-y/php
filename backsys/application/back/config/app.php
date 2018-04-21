<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-9
 * Time: 下午1:10
 * 此处的文件名称 与 application 下config 文件一一对应 也与  核心目录下的convention.php 一级数组key 值对应
 */
return [
    // 禁止 新标签 窗口打开页面  默认允许
    //   "page_new_open"=>"false",
    // 其他管理员是否有查看操作非自己创建用户的权限,  默认true  开启后其他用户也是只可修改角色权重（大于）级别小于自己的
        "no_admin_see_user"=>true,
        "page_size"=>5,
    // 添加用户是如果密码为空默认值
        "user_pass_default"=>123456,
];

