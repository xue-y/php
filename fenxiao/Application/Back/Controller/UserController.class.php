<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 下午1:18
 */

namespace Back\Controller;
use My\MyController;
class UserController extends MyController{  //用户
    //列表页面
    function index()
    {
        $this->pos_tag();  //当前位置标签
        $bu_men=$this->arr_data("bu_men");
        $user=D('User');
        $a_id=$user->selec_admin();
        $w=array();
        $u_role_id=$user->role_id($this->u_id);// 当前用户角色ID--判断是不是普通管理员
        // && ($a_id==$this->u_id || $u_role_id==P_R_ID)----------加上这个超级管理员与普通管理员可以搜索
        if(isset($_GET))
        {
            $get=$this->add_slashes($_GET);                                     //普通管理员可以看--但是限制了是否可以其他部门成员
            if(isset($get["bumen"]) && in_array($get["bumen"],$bu_men))
            {
                  $w["bumen"]=$get["bumen"];
            }
            if(isset($get["role"]) && $get["role"]!="-1")
            {
                $w["role_id"]=$get["role"];
            }
            if(isset($get["found"]) && $get["found"]!="-1")
            {
                $w["found"]=$get["found"];
            }
            if(isset($get["key"]) && !empty($get["key"]))
            {
                $w["u_name"]=array("like","%{$get["key"]}%");
            }
        }//-------------------搜索用户

        if($a_id==$this->u_id)
        {
            //超级管理员
            if(count($w)>0)
            {
                $count=$user->where($w)->count();
                $Page= new \Think\Page($count,P_O_C);
                $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
                $list = $user->where($w)->field("id,u_name,bumen,role_id,found")->limit($Page->firstRow.','.$Page->listRows)->select();
            }
            else
            {
                $count=$user->count();
                $Page= new \Think\Page($count,P_O_C);
                $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
                $list = $user->field("id,u_name,bumen,role_id,found")->limit($Page->firstRow.','.$Page->listRows)->select();
            }
        }else{
            //其他用户---取得自己的与自己创建的用户
            if(empty($w))  // 查看用户列表是
            {
                if($u_role_id==P_R_ID) // 如果是普通管理员 默认现在自己创建的用户或者 自己部门的用户
                {
                    $u_bumen=$user->u_bumen($this->u_id);
                    $w['bumen']=array('eq',$u_bumen);
                    $w['found']=array('eq',$this->u_id);
                    $w['_logic'] = 'or';
                }else
                {
                    $w['id']=array('eq',$this->u_id);
                }
            }
            $count=$user->where($w)->count();
            $Page= new \Think\Page($count,P_O_C);
            $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $user->where($w)->field("id,u_name,bumen,role_id,found")->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        $list=$this->str_slashes($list);
        $this->assign('list',$list);// 赋值数据集$this->assign('page',$show);// 赋值分页输出

        $role=D('Role');
        /*foreach($list as $v)
        {
            $role_id[]=$v['role_id'];
        }
        $role_n=$role->role_n($role_id);*/ //---查询自己权限下的所有角色
        if($a_id==$this->u_id)
        {
            $role_n=$role->role_all(); // 查询所有角色名称
        }else
        {
            $a_r_id=$user->role_id($a_id); // 取得超级管理ID ----去除超级管理角色
            $role_n=$role->role_all($a_r_id); // 查询所有角色名称
        }

        $found=$user->found_all();//查询所有创建用户人员

        $this->assign(
            array("list"=>$list,"role_n"=>$role_n,"bu_men"=>$bu_men,"u_id"=>$this->u_id,"found"=>$found,"a_id"=>$a_id,"show"=>$show,"u_role_id"=>$u_role_id,"count"=>$count)
        );
        $this->display();
    }

    //进入用户添加页面
    public function add()
    {
        $this->pos_tag();  //当前位置标签
        $role_all=$this->o_role();   // 取得自己角色以外的角色
        $user=D('User');
        $is_amdin=$this->is_admin($user);
        $u_role_id=$user->role_id($this->u_id);
        if(isset($is_amdin) || A_S_C==TRUE)  //----------------------超级管理员或对普通管理员开放权限
        {
            $bu_men=$this->arr_data("bu_men");//部门
        }
        else if($u_role_id==P_R_ID) // 普通管理员只可添加自己部门的人员
        {
            $bu_men=$user->u_bumen($this->u_id);
        }

        $this->assign(
            array("bu_men"=>$bu_men,"u_id"=>$this->u_id,'role'=>$role_all)
        ); // 取得用户信息
        $this->display();
    }

    // 执行添加
    public function execAdd()
    {
        $post=add_slashes($_POST);
        if($post['role_id']=="-1")
            $this->error("请选择一个角色");
        if($post['bumen']=="-1")
            $this->error("请选择用户所在部门");

        $user=D('User');
        $is_amdin=$this->is_admin($user);
        $u_role_id=$user->role_id($this->u_id);

        if(isset($is_amdin) ||  A_S_C==TRUE)//---------------------超级管理员
        {
            $bumen=$this->arr_data('bu_men');
            if(!in_array($post['bumen'],$bumen))
                $this->error("部门选择错误");
        }
        else if($u_role_id==P_R_ID)  //----------------------普通管理员
        {
            $bu_men=$user->u_bumen($this->u_id);
            if($bu_men!=$_POST['bumen'] &&  A_S_C==FALSE)
            {
                $this->error("您只能添加自己部门的人员");
            }
            $child_user=explode(',',P_R_ID_C);
            if(!in_array($post['role_id'],$child_user))
            {
                $this->error("您选择的角色不存在");
            }
        }
        if(!$user->create())
        {
            $this->error($user->getError());
        }//验证字段

        $role=D('Role');
        $role_id=$role->is_role_id($_POST['role_id']);
        if($role_id<1)
            $this->error("选择的角色角色不存在");

        $data['u_name']=$post['u_name'];
        $data['u_pass']=pass_md5($post["u_pass"]);
        $data['bumen']=$post['bumen'];
        $data['role_id']=intval($post['role_id']);
        $data['found']=$this->u_id;
		
		
        $shiwu=M();// 开启事务
        $shiwu->startTrans();
		
		$user_id=$user->add($data);
		if(!isset($user_id) || (intval($user_id)<1))
		{
			  $shiwu->rollback();
			  $this->error("添加用户失败");
		}
		
		$user_fj=D("User_fj");  // -----------------附加表添加管理员数据
		$fj_data["id"]=$user_id;
		$fj_id=$user_fj->add($fj_data);
		if(!isset($fj_id))
		{
			 $shiwu->rollback();
			  $this->error("添加用户失败");
		}
        $shiwu->commit(); //提交事务
        $shiwu=null;
        unset($shiwu);
        $this->success("添加用户成功","index");
    }

    //修改用户

    public function update()
    {
       if(isset($_GET['id']))
        {
            $user=D('User');
            $get_id=$this->add_slashes($_GET['id']);

            $u_info=$user->u_id_is($get_id);
            $u_info=$this->str_slashes($u_info);
            if(!isset($u_info) || empty($u_info))
            {
                $this->error('不存在此用户');exit;
            }

            $this->pos_tag();  //当前位置标签

            $role=D('Role');
            $role_all=$this->o_role();
            $u_role_id=$user->role_id($this->u_id); // 当前用户角色ID；

            $is_admin=$this->is_admin($user);   // 判断当前用户是不是超级管理员
             if(isset($is_admin))
            {
                $bu_men=$this->arr_data("bu_men");//部门
                if($this->u_id!=$get_id)
                {
                    $user_identity="all";  // 修改自己权限下的用户
                }else
                {
                    $user_identity="self";           // 修改自己
                }

            }else if($u_role_id==P_R_ID)  //  普通管理员
             {
                 if($this->u_id!=$get_id)
                 {
                     if(A_S_C==TRUE)
                     {
                         $bu_men=$this->arr_data("bu_men");//部门
                     }
                     else
                     {
                         $bu_men=$user->u_bumen($this->u_id);
                     }
                     $u_bumen=$user->u_bumen($this->u_id); // 当前用户所在部门
                     $get_c=$user->u_child_u($this->u_id,$get_id,$u_bumen);  //只能修改自己创建的用户  或自己部门的
                     if($get_c!=1)
                     {
                         $this->error("修改用户不在您的权限内");
                     }
                     $user_identity="part";
                 }
                 else
                 {
                     $bu_men=$this->arr_data("bu_men");//部门-----用户可以修改自己的部门
                     $user_identity="self";
                 }

             }else                  //----------------其他用户
             {
                 $bu_men=$this->arr_data("bu_men");//部门-----用户可以修改自己的部门
                 $user_identity="self";
             }

            $my_role_all=$role->role_n($u_info['role_id']);
            $u_info['role_n']=$my_role_all[0]['n']; //-----当前用户的角色名称
            $u_info['id']=$_GET['id'];

            //取得附件信息表
            $user_fj=D("User_fj");
            $u_fj=$user_fj->field("id",true)->find($get_id);
			if(!isset($u_fj))  // 如果不存在附件表
			{
				$u_fj["phone"]=$u_fj['wx']=$u_fj['info']='';
				$u_fj['meg']="暂无消息";
			}

            $this->assign(
                array("bu_men"=>$bu_men,"u_id"=>$_GET['id'],"u_info"=>$u_info,"role_all"=>$role_all,"u_ide"=>$user_identity,"u_fj"=>$u_fj)
            );
            $this->display();
        }else
        {
           $this->error('不存在此用户');exit;
        }
    }

    // 执行修改
    public function execUate()
    {
        if(isset($_POST['id']))
        {
            $post=$this->add_slashes($_POST);
            $user=D('User');
            $u_info=$user->u_id_is($post['id']);
            if(!isset($u_info) || empty($u_info))
            {
                $this->error('不存在此用户');
            }//-----------------------------------------------验证用户是否存在

            $is_admin=$this->is_admin($user);   // 判断当前用户是不是超级管理员
            $u_role_id=$user->role_id($this->u_id); // 当前用户角色ID；
            $u_bumen=$user->u_bumen($this->u_id); // 当前用户所在部门

            // 判断当前操作者是不是超级管理员 普通管理员只可修改自己创建的或者自己部门的  用户修改自己的
            if(!isset($is_admin) && $post['id']!=$this->u_id && $u_role_id!=P_R_ID)
            {
                $this->error("您没有权限修改此用户");
            }else if($u_role_id==P_R_ID && ($post['id']!=$this->u_id))
            {
                $get_c=$user->u_child_u($this->u_id,$post['id'],$u_bumen);  //普通管理员可修改自己创建的用户 或自己部门的
                  if($get_c!=1)
                    $this->error("修改用户不在您的权限内里");
            }
            $user=D('User');
            if(!$user->create()) // 不是自己表中的字段只执行验证，但不收件数据
            {
                $this->error($user->getError());
            }//验证字段


            //-------超级管理员/普通管理员允许添加其他部门成员 / 自己修改自己的
            if(isset($is_admin) || ($u_role_id==P_R_ID && A_S_C==TRUE && $this->u_id!=$post['id']) || ($this->u_id==$post['id']))
            {
                $bu_men=$this->arr_data("bu_men");//部门
                if(!in_array($post['bumen'],$bu_men))
                    $this->error('请选择一个部门');
            }else if($u_role_id==P_R_ID && ($this->u_id!=$post['id']) && A_S_C==FALSE)  //---------------------普通管理员
            {
                $bu_men=$user->u_bumen($this->u_id);
                if($post['bumen']!=$bu_men)
                {$this->error("您没有权限修改此用户为其他部门，<br/>请用户自己修改或超级管理员修改");}
            }

            $role_id_all=$this->o_role();//取得自己角色以外的角色--并且有权限添加用户的管理员

            $role_id_all2=array();
            foreach($role_id_all as $k=>$v)
            {
                $role_id_all2[$k]=$v['id'];
            }           //---------------------------判断用户权限下拥有的角色ID

            $role_id=intval($post["role_id"]);
            $is_admin=$this->is_admin($user);  // 如果当前修改的不是自己角色，并且操作者是用户创建者或者超级管理员
           if($this->u_id!=$post['id'] && !in_array($role_id,$role_id_all2) && !isset($is_admin))
            {
               $this->error('请选择一个角色');
            }

           if(!empty($post['old_pass']) && !empty($post["u_pass"]) && !empty($post["u_pass2"]))
          {// 修改密码
              $old_pass=pass_md5($post['old_pass']);
              $data_pass=$user->getFieldById($post['id'],"u_pass");
              if($old_pass!==$data_pass)
              {
                  $this->error('原密码错误');exit;
              }
              $new_pass=pass_md5($post["u_pass"]);
              if($old_pass!==$new_pass)
              {
                  $data["u_pass"]=$new_pass;
              }
          }
           $new_name=$this->add_slashes($post["u_name"]);
           if($new_name!=$u_info["u_name"])
          {
              $data["u_name"]=$new_name;
          }//-------------------------------------修改用户名

           if($u_info["bumen"]!=$post["bumen"])
           {
              $data["bumen"]=$this->add_slashes($_POST["bumen"]);
           }
           $found=$user->getFieldById($post['id'],"found"); //---------验证权限 不是修改自己的角色 角色做过更改
           if(($found!=$post['id']) && ($post['id']!=$this->u_id) && ($post["role_id"]!=$u_info["role_id"])) //不可修改自己的角色
           {
            $data["role_id"]=$role_id;
           }

          $af=$this->user_fj($post,$post['id']); // 修改附加表

           if(empty($data))
          {
			  if(!isset($af) || intval($af)<1)
			  {
				  $this->success("您没有修改或修改失败","index",5); 
			  }else
			  {
				   $this->success("修改成功","index");  
			  }
          }
          else     // 执行修改
          {
              $w["id"]=array("eq",$post['id']);
              $bool=$user->where($w)->save($data);
              if(!isset($bool))
              {  $this->error('修改用户信息失败');}
              else
              {
                  if(($this->u_id==$post['id']) && ($new_name!=$u_info["u_name"])) //如果修改自己的用户名
                  {
                      cookie('n',$new_name,36000,"/");
                  }
				  if(!isset($af) || intval($af)<1)
				  {
					   $this->success("其他信息修改成功附加信息没有修改","index",10);
				  };
                  $this->success("修改用户成功","index");
              }
          }
        }
        else
        {
            $this->error('不存在此用户');
        }
    }

    //删除用户
    public function del()
    {
         if((!isset($_GET["id"])) && (!isset($_POST["id"])))
          {
            $this->error("请选择要删除的用户");
          }
         $user=D("User");

         if(isset($_POST["id"]))
         {
            $p_id=$this->add_slashes($_POST["id"]);
         }
          $a_id=$user->selec_admin(); //  取得超级管理员的id
          $u_bumen=$user->u_bumen($this->u_id); // 当前用户自己的部门
          if(isset($_GET["id"]))
          {
              $g_id=$this->add_slashes($_GET["id"]);
              $this->del_one($user,$g_id,$a_id,$u_bumen);
          }
        $p_id_len=count($p_id);
        if($p_id_len==1)
         {
             $this->del_one($user,$p_id[0],$a_id,$u_bumen);
         }
        else
         {          //------------- 一次删除多个用户
            if(in_array($this->u_id,$p_id))
            {
                $this->error("抱歉，您没有权限删除自己");
            }
            if(in_array($a_id,$p_id))
            {
                $this->write_log("$this->u_id: 试图删除超级管理员",1);
                $this->error("其中某个用户不存在,不可删除");die;
            }

             //---------------------如果当前用户为其他用户的创建者不可删除
            $child_u=array();
            foreach($p_id as $k=>$v)
            {
                $child_u_is=$user->getFieldByFound($v,'id');
                if(!empty($child_u_is))
                $child_u[$k]=$v;
            }//-------------------------------------取得有子用户的 用户id
             if(!empty($child_u))
             {
                 $child_u=implode(',',$child_u);
                 $this->error("用户编号：$child_u 的用户".'<br/>'."为其他用户的创建者".'<br/>'."如需删除请先删除".'<br/>'." $child_u 用户所创建的用户","index",10);
             }
              $p_id_str=implode(',',$p_id);

              $w["id"]=array("in",$p_id_str);
              $del_arr=$user->where($w)->field("id")->select();
             if(count($del_arr)!=$p_id_len)
             {
                 foreach($del_arr as $v)
                 {
                     $del_arr2[]=$v["id"];
                 }
                 $diff=array_diff($p_id,$del_arr2);
                 $diff=implode(',',$diff);
                 $this->error("编号为 $diff 用户不存在");
             }else
             {

                 $d_id_len=$user->delete($p_id_str);
                 if($d_id_len!=$p_id_len)
                 {
                     $this->error("删除用户出错");
                 }else
                 {
                     $this->success("共删除 $d_id_len 用户完成");
                 }
             }
        }
    }

    //取得自己角色以外的角色--并且有权限添加用户的管理员
    protected   function  o_role()
    {
        $user=D('User');
        $role_id=$user->role_id($this->u_id); // 取得当前用户的角色ID
        if($this->is_admin($user))
        {
           if(A_S_A!=TRUE)
             $w['id']=array("neq",$role_id);
           else
               $w=array();
        }
        else if(P_R_ID==$role_id)
        {
            $w['id']=array("in",P_R_ID_C);
        }
        $role=D("Role");

        $role=$role->where($w)->field('id,n')->select();
        return $role;
    }


    /** 删除单个用户
     * @parem $user 实例化user表
     * @parem $del_id 要删除的单个用户id
     * @parem $a_id 超级管理id
     * */
    protected function del_one($user,$del_id,$a_id,$u_bumen)
    {
        if($del_id==$a_id)
        {
            $this->write_log("$this->u_id: 试图删除超级管理员",1);
            $this->error("此用户不存在,不可删除");exit;
        }//----------------------------------------------防止意外删除超级管理员

        $user_is=$user->field("id")->find($del_id);
        if(empty($user_is))
        {
            $this->error("此用户不存在");exit;
        }
        if($del_id==$this->u_id)//------------------------不能删除自己
        {
            $this->error("抱歉，您没有权限删除自己");exit;
        }

        $w["found"]=array("eq",$this->u_id);
        $w["bumen"]=array("eq",$u_bumen);
        $w['_logic'] = 'or';
        $found=$user->field("id")->where($w)->select();//--取得当前用户创建的或自己部门的 子用户ID
        $found2=array();
        foreach($found as $v)
        {
            $found2[]=$v["id"];
        }
        $w2["found"]=array("eq",$del_id);
        $w['_logic'] = 'or';
        $child_u=$user->field("id")->where($w2)->select();//------------删除的用户为其他用户的创建人--先删除当前用户创建的用户

        //如果要删除的用户不是自己创建的 并且不是超级管理员不能删除 $del_id 【此用户】
        if(!in_array($del_id,$found2) &&  !$this->is_admin($user))
        {
            $this->error("抱歉，您没有权限");exit;
        }else if(!empty($child_u))
        {
           $this->error("当前用户为其他用户创建人，".'<br/>'."如需删除请先删除当前用户创建的用户");exit;
        }
        else{

            $bool=$user->delete($del_id);
            if($bool!=1)
            {
                $this->error("删除用户失败");exit;
            }else
            {
                $this->success("删除用户成功","index");exit;
            }
        }
    }

    /** 管理员用户自己修改附加信息
     * @parem  $post
     * @parem $id 用户id
     * @return 失败直接跳出 成功返回 受影响的行数
     * */
    private function user_fj($post,$id)
    {
        if(isset($post["phone"]) && !empty($post["phone"]))
        {
            $user_fj["phone"]=$post["phone"];
        }
        if(isset($post["wx"]) && !empty($post["wx"]))
        {
            $user_fj["wx"]=$post["wx"];
        }
        if(isset($post["info"]) && !empty($post["info"]))
        {
            $user_fj["info"]=$post["info"];
        }
        if(isset($user_fj) && !empty($user_fj)) // 更新管理员附加表
        {
            $u_fj=D("User_fj");
            $af=$u_fj->where("id=$id")->save($user_fj);

            /*if($af!=1)
            {
                $this->error("修改附加信息失败");
            }*/
            return $af;
        }
    }
}

