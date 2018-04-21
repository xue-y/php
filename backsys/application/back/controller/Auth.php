<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-16
 * Time: 下午6:41
 * back 模块公共验证登录 与 访问权限
 */

namespace app\back\controller;
use think\Controller;

class Auth  extends  Controller{

    public function initialize()
    {
        // 初始化方法中使用 return redirect 失效
        parent::initialize();

        if(check_login())  // 验证是否登录 为ture  没有登录
        {
            session('HTTP_REFERER',true);
            $this->redirect('back/Login/index');
        }
        // 判断是否有访问权限
        $pos=is_power();

        if(!$pos)
        {
          //  $this->error(lang("index_deny"),"back/Index/index");
            $this->error(lang("index_deny"));
        }else
        {// 当前位置
            $this->assign("pos",$pos);
        }
    }

} 