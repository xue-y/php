<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-15
 * Time: 下午1:34
 * 后台管理首页
 */

namespace app\back\controller;
use think\Controller;

class Index extends Controller {

    public  function index()
    {
       // 如果之前没有验证是否登录过 并且 验证后 没有登录
      if(!(request()->param("check_login")) &&  check_login())
      {
          return redirect('Login/index');
      };
      // 如果为true 禁止 新标签 窗口打开页面
        /*  if(config("page_new_open"))
          {

          }*/

      // 验证权限----根据权限 显示左边菜单
        $power=power_get();
        $menu_data=power_list($power);
        $this->assign("menu_data",$menu_data);
        return $this->fetch("/index");
    }
    //后台首页
    public function  backHome()
    {
        $strTimeToString = "000111222334455556666667";
        $strWenhou = array(lang('index_night'),lang('index_early'),lang('index_morning'),lang('index_forenoon'),lang('index_noon'),lang('index_afternoon'),lang('index_evening'),lang('index_night'));
        $time=$strWenhou[(int)$strTimeToString[(int)date('G',time())]];

        $this->assign("time",$time);
        return $this->fetch("/backHome");
    }
} 