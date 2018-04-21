<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-18
 * Time: 下午12:14
 * 数据库管理
 */

namespace app\back\controller\sys;
use app\back\controller\Auth;

class Db extends  Auth {

    public function index()
    {
        return $this->fetch();
    }

    // 备份
    public function backup()
    {
        // 判断当前用户是否是 特别 超级管理员  显示所有表  除去 admin 表
        // 其他 超级管理员   除去 admin roles users 这个3个表
        return $this->fetch();
    }

    // 执行备份 -- 备份sql 文件格式  --- 数据表名称--   -- php  版本号  mysqL版本号  当前环境 -- 数据表结构 数据表数据
} 