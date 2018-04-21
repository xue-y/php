<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-18
 * Time: 上午11:25
 */

namespace app\back\controller\admin;
use app\back\controller\Auth;
use app\back\model\Roles;
use app\back\model\Users;
use app\back\validate\Vbackrole;

class Role  extends Auth{

    // 当前页面的列表
    protected  $cur_index="/back/admin/Role/index";
    //列表
    public function index()
    {
         $role=new Roles();
        //  如果是超级管理员
        // 此处if 语句可以省略-- 但是where 加条件查询会慢一点所有 分开查询
        if(cookie("login_role")==1)
        {
            $role_all=$role->select_all();
        }
        else
        {
            $role_all=$role->select_all(cookie("login_role"));
        }
        if(!empty($role_all))
        {
            $role_all=str_slashes($role_all);
            $this->assign(["role_all"=>$role_all]);
        }
        return $this->fetch();
    }

    //添加
    public  function  create()
    {
        $power=power_get();
        $menu_data=power_list($power,true);
        $this->assign("menu_data",$menu_data);
        return $this->fetch();
    }
    //执行添加
    public function save()
    {
        // 只接受的值
        $post=trim_str($this->request->only(['r_n','r_de','power'],'post'));
        $validate=new Vbackrole();
        if (!$validate->scene('create')->check($post)) {
            $this->error($validate->getError());
        }
        $role=new Roles(); // 默认自己的角色权重+1

        $post["r_w"]=intval($role->get_rw(cookie("login_role")))+1;

        $post["power"]=implode("|",$post["power"]);
        $role=new Roles();
        $rid=$role->save($post); //Roles::save($post);   没有此方法
        if($rid==1)
        {
            $this->success(lang("add_sucess"),$this->cur_index);
        }else
        {
            $this->error(lang("add_error"));
        }
    }

    //修改
    public function edit()
    {
        $rid=$this->request->param("rid/d");
        if(!isset($rid) || $rid<1)
        {
            $this->error(lang("power_role_nois"));
        }

        $role=new Roles();
        $role_one=$role->select_one($rid);
        if(empty($role_one))
        {
            $this->error(lang("power_role_nois"));
        }
        //当前用户只可修改角色但是不可修改所属角色的权限
        //只有超级管理员可以修改其他用户的权限
        if($rid!=cookie("login_role") && cookie("login_role")==1)
        {
            $power=$role->select_power($rid);
            if(!empty($power) )
            {
                $power=explode("|",$power);
                $menu_data=power_list("-1",true);
                $this->assign(["menu_data"=>$menu_data,"power"=>$power]);
            }
        }
        session("temp",encry($rid));// 临时保存rid
        $this->assign("role",$role_one);
        return $this->fetch();
    }

    // 执行修改
    public function update()
    {

        // 只接受的值  超级管理员
        if(cookie("login_role")==1)
        {
            $post=trim_str($this->request->only(['rid','r_n','r_de','power'],'post'));
            $post["power"]=implode("|",$post["power"]);
        }else
        {           // 其他管理员
            $post=trim_str($this->request->only(['rid','r_n','r_de'],'post'));
        }
        if($post["rid"]=="1") // 超级管理员 禁止修改
        {
            $this->error(lang("power_role_nois"));
        }
        $validate=new Vbackrole();
        if (!$validate->scene('create')->check($post)) {
            $this->error($validate->getError());
        }

        //  防止伪造角色id
        if(session("temp")!=encry($post["rid"]))
        {
          $this->error(lang("power_role_nois"));
        }
        session("temp",null);

        $role=new Roles();
        $rid=$role->where("rid","=",$post["rid"])->update($post);
        if($rid==1)
        {
            $this->success(lang("update_sucess"),$this->cur_index);
        }else
        {
            $this->error(lang("update_error").lang("r_no_edit"));
        }
    }

    // 删除单个角色  用户不得删除 超级管理员角色 和 自己当前的角色
    public function delete()
    {
        $rid=$this->request->param("rid/d");
        if(!isset($rid) || $rid<=1 || cookie("login_role")==$rid)
        {
            $this->error(lang("r_nois_noedit"));
        }
        //判断该角色下是否有用户正在使用
        $user=new Users();
        $r_usere=$user->select_user($rid);
        if($r_usere!==0)
        {
           $this->error(lang("r_no_del"));
        }
        $is_rid=Roles::destroy($rid);
        if($is_rid==1)
        {
            $this->success(lang("del_sucess"),$this->cur_index);
        }else
        {
            $this->error(lang("del_error"));
        }
    }

    //删除多个角色
    public function  deletes()
    {
        $rid=$this->request->post();
        if(empty($rid) || empty($rid["rid"]))
        {
            $this->error(lang("r_s_del"));
        }
        $rid=trim_str(array_unique(array_filter($rid["rid"])));
        if(empty($rid))
        {
            $this->error(lang("r_s_del"));
        }
        // 判断是否有当前操作用户的角色id ,如果存在从数组中移除
        if(cookie("login_role")!=1)
        {
            $norid = array_search(cookie("login_role"),$rid);
            if($norid!==false)
            {
                unset($rid[$norid]);
            }
        }
        // 判断是否有超级管理员id ,如果存在从数组中移除
        $norid = array_search(1,$rid);
        if($norid!==false)
        {
            unset($rid[$norid]);
        }

        // 判断是否有用户是在使用准备删除的角色 如果用直接从要删除的角色id 中移除
        $user=new Users();
        $diff_rid=$user->select_users($rid);
        if(!empty($diff_rid))
        {
            foreach($diff_rid as $k=>$v)
            {
                $diff_rid[$k]=$v["role"];
            }
        }
        $n_rid=implode(",",array_diff($rid,$diff_rid));
        if(empty($n_rid))
        {
            $diff_rid=implode(",",$diff_rid);
           $this->error(lang("role").$diff_rid."<br/>".lang("r_deny_del"),null,null,10);
        }
        $is_rid=Roles::destroy($n_rid);
        if($is_rid>=1)
        {
            $this->success(lang("meun_a_delete").$is_rid.lang("r_del_s"));
        }else
        {
           $this->error(lang("del_error"));
        }
    }
} 