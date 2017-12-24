<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 下午1:18
 */

namespace Back\Controller;
use My\MyController;
class MainController extends MyController{  //后台主页面
    public function index()
    {
        $n=$_SESSION[$this->s_pix.'n'];
        $this->user_limit(L_MENU); //判断用户身份读取相应权限左边菜单
        $ico=$this->arr_data("ico");
        $this->assign(
            array('ico'=>$ico,"n"=>$n)
        ); //权限图标
        $role=D("Role");
        $user=D("User");
        $r_id=$user->role_id($this->u_id);

        $this->rw_identity($user,$role,$r_id); //---执行添加任务身份权限
        $this->remind_meg();
        $this->display('Public/main');
    }
} 