<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-18
 * Time: 下午12:15
 * 日志管理
 */

namespace app\back\controller\sys;
use app\back\controller\Auth;

class Log extends  Auth{

    public function index()
    {
        return $this->fetch();
    }
} 