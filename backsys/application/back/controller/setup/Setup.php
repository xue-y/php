<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-18
 * Time: 下午1:41
 * 系统设置
 */

namespace app\back\controller\setup;
use app\back\controller\Auth;

class Setup extends Auth {

    public function index()
    {
      return  $this->fetch("/setup/index");
    }
} 