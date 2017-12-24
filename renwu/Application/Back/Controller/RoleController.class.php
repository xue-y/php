<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 下午1:18
 */

namespace Back\Controller;
use My\MyController;
class RoleController extends MyController{  //角色

    //角色列表
    public function index()
    {
        $this->pos_tag();  //当前位置标签
        $user=D('User');
        $role=D('Role');

        $r_id=$user->role_id($this->u_id);//取得当前用户角色ＩＤ  当前用户不可删除自己的角色
        if($this->is_admin($user)) //---------------判断是不是超级管理员
        {//超级管理员
            $is_admin=1;
            $list=$role->field("id,n,descr")->select();
        }else{
            $is_admin=NULL;
            if($r_id==P_R_ID)
            {//普通管理员
                $role_id=$r_id.','.P_R_ID_C;
                $w['id']=array("in",$role_id);
                $list=$role->where($w)->field("id,n,descr")->select();
            }else
            {//其他用户---当前普通用户查看自己的角色
                $is_admin=NULL;
                $list=$role->where("id=$r_id")->field("id,n,descr")->select();
            }
        }

        $this->assign(array(
            'list'=>$list,"r_id"=>$r_id,"is_admin"=>$is_admin
        ));
        $this->display();
    }

    public function add()
    {
        $user=D("User");
        $r_id=$user->role_id($this->u_id); // 当前用户角色ID
        $this->limit_deny($r_id);
        $this->pos_tag();  //当前位置标签
        $limit=D('Limit');
        $select_limit_all=$limit->select_limit_all($this->u_limit_id(),FALSE);
        $this->assign("s_l_a",$select_limit_all);
        $this->display();
    }

    //执行添加页面
   public function execAdd()
   {
       $role=D("Role");
       if(!$role->create())
       {
           $this->error($role->getError());
       }
       $user=D("User");
       $r_id=$user->role_id($this->u_id); // 当前用户角色ID
       $this->limit_deny($r_id);

       $post=$this->add_slashes($_POST);
       $data["n"]=$post['n'];
       $data['descr']=$post['descr'];

       $limit=D("Limit");
       $limit_id_is=$limit->limit_id_is($post['limit_id']);
       if(!isset($limit_id_is))
       {
          $this->error("选择权限出错,请刷新页面重新选择");
       }
       $data['limit_id']=$limit_id_is["l_id"];
       $last_id=$role->add($data);
       $bool=$limit->limit_all($limit_id_is["l_id"],$last_id);
       isset($bool)?$this->success("添加角色成功","index"): $this->error("添加角色失败");
   }

    //修改角色
    public function  update()
    {
        if(!isset($_GET["id"]))
        {
            $this->error("请选择修改的角色");
        }
        $g_id=$this->add_slashes($_GET["id"]);
        $this->limit_deny($g_id);
        $this->pos_tag();  //当前位置标签

        $user=D('User');
        $role=D('Role');

        $is_admin=$this->is_admin($user); // 判断当前用户是不是超级管理员
        $r_id=$user->role_id($this->u_id); // 当前用户角色ID

        $limit=D('Limit');
        if($is_admin) //如果当前用户是超级管理员
        {
            if($r_id==$g_id)  // 并且修改的是超级管理员角色--自己的
            {
                $r_info=$role->field("id,n,descr")->find($r_id);
            }else       // 并且修改的是其他角色
            {
                $allow=$role->limit_id($r_id); // 允许用户可以添加 修改的权限
                $r_info=$role->find($g_id);
                $s_l_a=$limit->select_limit_all($allow,FALSE); // 允许添加的的权限
                $my_l2=array();

                $my_l=$this->rid_lid($g_id,$r_info["limit_id"]);// 用当前拥有的权限
                $my_l=array_merge($my_l[L_MENU],$my_l[L_ACTION]);

                  foreach($my_l as $v)
                  {
                      $my_l2[]=$v["id"];
                  }
            }
        }else if($r_id==$g_id)  //普通管理员修改自己所属的角色
        {
            $s_l_a=NULL;
            $my_l2=NULL;
            $r_info=$role->field("id,n,descr")->find($r_id);

        }else{
            $this->error("您没有权限修改此角色");
        }
        $r_info=$this->str_slashes($r_info);
        $this->assign(array(
            "r_info"=>$r_info,"s_l_a"=>$s_l_a,"my_l"=>$my_l2
        ));
        $this->display();
    }

    // 执行修改角色
    public function  execUate()
    {
        if(!isset($_POST["id"]))
        {
            $this->error("请选择修改的角色");
        }
         $this->limit_deny($_POST["id"]);  // -----------------超级管理员 管理员 可以修改角色
         $post=$this->add_slashes($_POST);
 //       var_dump($post);
         $role=D("Role");
         $is_role_id=$role->is_role_id($post["id"]);
         if($is_role_id<1)
         {
             $this->error("修改角色出错");
         }
        if(!$role->create())
        {
            $this->error($role->getError());
        }//验证字段
   //     $data["id"]=$post["id"];
         if(!empty($post['n']))
         {
             $data['n']=$post['n'];
         }
         if(!empty($post['descr']))
         {
             $data['descr']=$post['descr'];
         }

         if(!empty($post['limit_id']))
         {
             $limit=D("Limit");
             $limit_id_is=$limit->limit_id_is($post['limit_id']);

             if(!isset($limit_id_is))
             {
                 $this->error("选择权限出错,请刷新页面重新选择");
             }
             $data['limit_id']=$limit_id_is["l_id"];
             $bool=$role->where("id={$post["id"]}")->save($data);

             if($bool<1)
             {
                 $this->error("修改角色失败，或没有修改");
             }
             //根据角色ID 删除权限文件
             $this->unlink_l_f($post["id"],"更新 ".$post['id']." 角色权限文件失败");

             $bool=$limit->limit_all($limit_id_is["l_id"],$post["id"]);
             isset($bool)?$this->success("修改角色成功","index"): $this->error("修改角色失败");
         }else
         {
             $bool=$role->where("id={$post["id"]}")->save($data);
             $user=D("User");
             $this->rw_identity($user,$role,$post["id"]); //----------重新更新当前用户身份
             $this->remind_meg();
             $bool==1?$this->success("修改角色成功","index"): $this->error("您没有修改角色或修改角色失败");
         }// 当前用户修改自己的角色
    }

    public function del()
    {
        if(!isset($_GET["id"]) && !isset($_POST["id"]))
        {
            $this->error("您没有选择角色，请选择要删除的角色");
        }
        $user=D("User");
        $u_r_id=$user->role_id($this->u_id);  // -当前用户自己的角色ID
        $this->limit_deny($u_r_id); //-------------判断当前用户身份
        if(isset($_GET["id"]))
        {
            $r_id=$this->add_slashes($_GET["id"]);
        }
        if(isset($_POST["id"]))
            $r_id=$this->add_slashes($_POST["id"]);

        $this->role_del($user,$r_id,$u_r_id);
    }


    //角色添加 修改 删除 限制
    private function  limit_deny($r_id)
    {
        $user=D("User");
        $is_admin=$this->is_admin($user);
        if(!isset($is_admin) && ($r_id!=P_R_ID))
        {
            $n=$_SESSION[$this->s_pix."n"];
            $this->write_log("$this->u_id | 用户名：$n |试图操作角色",1);
            $this->error("抱歉您没有权限");
        }
    }

    //
    /**
     * 根据角色ID 删除权限文件
     * @parem $r_id 角色ID
     * @parem $info 删除失败后的提示信息
     * */
    private function unlink_l_f($r_id,$info)
    {
        if(file_exists(L_O_D_F.$r_id.".php"))
        {
            $unlink_b=unlink(L_O_D_F.$r_id.".php");
            if(!isset($unlink_b))
                $this->error($info);
        }
    }


    /**
     * @parem $user 实例化 user
     * @parem $r_id 传值删除的 角色id
     * @parem $u_r_id 当前用户的角色ID
     * */
    private function role_del($user,$r_id,$u_r_id)
    {
        $role=D("Role");
        if($r_id==$u_r_id)
        {
            $this->error("您没要删除此角色的权限");
        }

        if(is_array($r_id))
        {
            if(in_array($u_r_id,$r_id))
            {
                $this->error("您没要删除自己角色的权限");
            }
            $r_id=implode(',',$r_id);
        }
         $r_info=$role->is_role_id($r_id);//-------------要删除的角色个数
         $c=$r_info["c"];
         $deny_n=implode(" , ",$r_info["n"]); // 角色下有用户的角色名称

        if($c<1)
        {
            $this->error("请选择要删除的角色");
        }

        $u_c=$user->rid_user($r_id);
        if($u_c>=1)
        {
            $this->error("[ $deny_n ] 角色用 $u_c 个用户正在使用，<br/>如需删除角色请先删除使用此角色的用户");
        }
        $d_c=$role->delete($r_id);
        if(!isset($d_c) || ($d_c!=$c))
        {
            $this->error("删除角色失败");
        }else
        {
            //根据角色ID 删除权限文件
            $this->unlink_l_f($r_id,"删除 $r_id 角色权限文件失败");
            $this->success("删除角色成功");
        }
    }

} 