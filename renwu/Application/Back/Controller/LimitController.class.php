<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 下午1:18
 */

namespace Back\Controller;
use My\MyController;
class LimitController extends MyController{  //用户

    function index()
    {
        $role=D("Role");
        $user=D("User");
        $r_id=$user->role_id($this->u_id);//当前用户自己的角色
        $is_admin=$this->is_admin($user);
        if(isset($_GET["id"]) && !empty($_GET["id"]))
        {
            $get_id=add_slashes($_GET["id"]);
            $r_c_id=implode(",",P_R_ID_C);
            if($get_id!=$r_id && (P_R_ID==$r_id && in_array($get_id,$r_c_id)))// 普通管理员--查看其它用户
            {
                $r_id=$get_id;
            }
            if(isset($is_admin)) // 超级管理员可以查看其它人员的
            {
                $r_id=$get_id;
            }
        }

        $limit_id=$role->limit_id($r_id);
        if(!isset($limit_id) || empty($limit_id))
        {
            $this->error("不存在此角色");
        }
        $this->pos_tag();  //当前位置标签

        if($this->is_admin($user))
        {
            $shenfen=TRUE;  //-----------如果当前用户是超级管理，并且查看的是自己
        }else
        {
            $shenfen=FALSE;
        }
        $limit=D("Limit");
        $select_limit_all=$limit->select_limit_all($limit_id,$shenfen);

        $this->assign("s_l_a",$select_limit_all);
        $this->display();
    }

} 