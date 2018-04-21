<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-10
 * Time: 下午2:27
 */

namespace app\back\controller\admin;
use app\back\controller\Auth;
use app\back\model\Admin;
use app\back\model\Roles;
use app\back\model\Users;

class User  extends  Auth{

    // 当前页面的列表
    protected  $cur_index="/back/admin/User/index";

    public function index()
    {
        $uid=cookie("login_uid");
        // 判断当前用户身份
        if(($uid==1 && cookie("login_role")==1) || config("no_admin_see_user"))
        {
            $is_allow=true; // 如果没有访问限制
        }else
        {
            $is_allow=false;  // 如果有访问限制
        }

        $gid=$this->request->param();
        // 角色下的所有用户 如果是超级管理员 ---> 除去 特别超级管理员
        if(isset($gid["rid"]) && intval($gid["rid"])>=1)
        {
            if(!$is_allow)
            {
                $where["founder"]=["founder","=",$uid];
            }
            $where["role"]=["role","=",intval($gid["rid"])];
        }
        // 创建人所创建的用户
        if($is_allow && isset($gid["fid"]) && intval($gid["fid"])>=1 )
        {
            $where["founder"]=["founder","=",intval($gid["fid"])];
        }
        // 按照用户名搜索用户
        if(isset($gid["n"]) && !empty($gid["n"]))
        {
            if(!$is_allow)
            {
                $where["founder"]=["founder","=",$uid];
            }
            $where["n"]=["n","like",'%'.trim_str($gid["n"]).'%'];
        }

        $page_size=config("page_size");
        $role=new Roles();
        // 组装当前用户自己的数据

        $c_user["uid"]=$uid;
        $c_user["role"]=cookie("login_role");
        $c_user["n"]=cookie("u_name");
        $c_user["r_n"]=$role->select_n(cookie("login_role"));

        // 判断用户身份
        // 如果当前登录的人员 是特别超级管理员
        if($uid==1 && cookie("login_role")==1)
        {
            $is_admin=1;
            $c_user["fid"]=$uid;
            $c_user["f_n"]=$c_user["n"];

            if(!isset($where) || empty($where))
            {
                $list=Users::alias("a")->field("a.uid,a.founder,a.role,a.n,c.r_n")
                    //    ->join(['ba_roles'=>'c'],'a.role=c.rid')->paginate($page_size);
                    ->join('roles c','a.role=c.rid')->paginate($page_size);
            }else
            {
                $list=Users::alias("a")->where($where)->field("a.uid,a.founder,a.role,a.n,c.r_n")
                    //    ->join(['ba_roles'=>'c'],'a.role=c.rid')->paginate($page_size);
                    ->join('roles c','a.role=c.rid')->paginate($page_size);
            }
        }else
        {
            $user=new Users();
            $f=$user->get_foun($uid); // 查询普通表
            if(!$f)
            {
                $this->error(lang("page_error"));
            }
            $c_user["fid"]=$f["fid"]; // 当前用户的创建人
            $c_user["f_n"]=$f["f_n"];

            // 如果可以查看非自己创建的用户
            if(config("no_admin_see_user"))
            {
                $where["uid"]=["uid","<>",$uid];
                $list=Users::alias("a")->where($where)->field("a.uid,a.founder,a.role,a.n,c.r_n")
                   // ->join(['ba_roles'=>'c'],'a.role=c.rid')->paginate($page_size);
                    ->join('roles c','a.role=c.rid')->paginate($page_size);
            }else
            {
                $where["founder"]=["founder","=",$uid];
                $list=Users::alias("a")->where($where)->field("a.uid,a.role,a.n,c.r_n")
                    ->join('roles c','a.role=c.rid')->paginate($page_size);
            }
        }
        $count = $list->total();
        $total=$list->lastPage();
        $page=$list->render();
        $this->assign(['list'=>$list,'page'=>$page,"c"=>$count,"total"=>$total,"c_user"=>$c_user]);
        return $this->fetch();
    }

    //添加
    public  function  create()
    {
        // 添加的用户 显示的角色必须是自己的角色权重> = 自己的  值越大权限级别越小
        $role=new Roles();
        $role_arr=$role->get_role(cookie("login_role"));
        $this->assign("role_arr",$role_arr);
        return $this->fetch();
    }
    //执行添加
    public function save()
    {
        $post=trim_str($this->request->only(['n','pw','pw2','role'],'post'));
        $post["ip"]=request()->ip();
        $post["founder"]=cookie("login_uid");

        $vail=$this->validate($post,
            [
                'n' => 'require|unique:users|regex:[\s\S]{2,30}',
                'pw' => 'regex:([\w\.\@\!\-\_]){6,10}',
                'pw2' => 'confirm:pw',
                'role'=>'min:1',
            ],
             [
                'n.require' => 'n_require',
                'n.regex' => 'n_regex',
                'n.unique' => 'user_unique',
                'pw.regex' => 'pw_regex',
                'pw2.confirm'=>'user_w_nopw2',
                'role.min'=>'user_p_role'
            ]
        );
        if(true !== $vail){
            // 验证失败 输出错误信息
           $this->error($vail);
        }
        // 判断角色是否存在
        $role=new Roles();
        $role_arr=$role->get_role(cookie("login_role"));
        foreach($role_arr as $k=>$v)
        {
            $role_arr2[$k]=$v["rid"];
        }
        if(!in_array($post["role"],$role_arr2))
        {
            $this->error(lang("user_nois_rid"));
        }
        // 添加用户是如果没有密码使用默认密码
        if(empty($post["pw"]))
        {
            $post["pw"]=encry(config("user_pass_default"));
        }else
        {
            $post["pw"]=encry($post["pw"]);
        }

        $user=new Users();
        $uid=$user->save($post);
        if($uid==1)
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
        $uid=$this->request->param("uid/d");
        if(!isset($uid) || $uid<1)
        {
            $this->error(lang("user_nois"));
        }
        $user=new Users();

        // 如果当前操作用户是特别超级管理员
        if(cookie("login_uid")==1 && cookie("login_role")==1)
        {
            //如果修改的是自己的
            if(cookie("login_uid")==$uid)
            {
                $admin=new Admin();
                $u_info=$admin->selct_admin();
            }else
            { // 验证用户是否存在
                $u_info=$user->is_user($uid);
            }
        }else
        {  // 其他管理员 //验证判断是否有操作权限
            $this->vail($user,$uid);
            $u_info=$user->is_user($uid);
        }
        if(empty($u_info)) // 防止查询不到数据
        {
            $this->error(lang("user_deny_edit"));
        }

        $u_info["uid"]=$uid;
        session("temp",encry($uid));// 存储临时 修改用户的 uid
        session("temp2",md5($u_info["pw"]));
        // 添加的用户 显示的角色必须是自己的角色权重> = 自己的  值越大权限级别越小
        $role=new Roles();
        $role_arr=$role->get_role(cookie("login_role"));
        $this->assign(["role_arr"=>$role_arr,"u_info"=>$u_info]);

        // 特别超级管理员有修改创建人的权限
        if(cookie("login_uid")==1 && cookie("login_role")==1 && cookie("login_uid")!=$uid)
        {
            // 特别超级管理员 --创建人信息
            $admin_f["uid"]=intval(cookie("login_uid"));
            $admin_f["n"]=cookie("u_name");

            $u_all=$user->select_all();
            array_unshift($u_all,$admin_f);

            $this->assign("u_arr",$u_all);
        }
        return $this->fetch();
    }

    //执行修改
    public function update()
    {
       // 验证uid 是否合法
        $uid=$this->request->param("uid/d");
        if(!isset($uid) || $uid<1 || session("temp")!=encry($uid))
        {
            $this->error(lang("user_nois"));
        }
        //---------------------------------标识身份-------验证权限
        $user=new Users();
        // 如果是超级管理员
        if(cookie("login_uid")==1 && cookie("login_role")==1)
        {
            //如果修改的是自己的
            if(cookie("login_uid")==$uid)
            {
                $shen_biaoshi=1;
                $post=$this->request->only(['n','old_pw','pw','pw2'],'post');
            }
            else
            {
                $shen_biaoshi=2;
                $post=$this->request->only(['uid','n','old_pw','pw','pw2','role','founder'],'post');
            }
        }else{    //其他管理员
            //验证判断是否有修改权限
            $this->vail($user,$uid);
            if(cookie("login_uid")==$uid)
            {
                $shen_biaoshi=3;
                $post=$this->request->only(['uid','n','old_pw','pw','pw2',],'post');
            }else
            {
                $shen_biaoshi=4;
                $post=$this->request->only(['uid','n','old_pw','pw','pw2','role'],'post');
            }
        }

        $post=trim_str($post);
        $vail=$this->validate($post,
            [
                'n' => 'require|unique:users|regex:[\s\S]{2,30}',
                'pw' => 'regex:([\w\.\@\!\-\_]){6,10}',
                'pw2' => 'confirm:pw',
                'role'=>'min:1',
            ],
            [
                'n.require' => 'n_require',
                'n.regex' => 'n_regex',
                'n.unique' => 'user_unique',
                'pw.regex' => 'pw_regex',
                'pw2.confirm'=>'user_w_nopw2',
                'role.min'=>'user_p_role'
            ]
        );
        if(true !== $vail){
            // 验证失败 输出错误信息
            $this->error($vail);
        }
        if(!empty($post["old_pw"]) && !empty($post["pw"]) && !empty($post["pw2"]))
        {
            if(md5(encry($post["old_pw"]))!=session("temp2"))
            {
                  $this->error("原密码错误");
            }
            $post["pw"]=encry($post["pw"]);
        }else
        {
            unset($post["pw"]);
        }
        unset($post["old_pw"]);
        unset($post["pw2"]);

        // 判断身份根据身份写入数据
        $post["t"]=date('Y-m-d H:i:s',time());
        $post["ip"]=$this->request->ip();

        //根据身份修改用户
        if($shen_biaoshi==1)
        {
           $admin=new Admin();
           $is_save=$admin->where("n","=",cookie("u_name"))->update($post);
           if($is_save!=1)
           {
             $this->error(lang("update_error"));
           }
        }else
        {
            $is_save=$user->where("uid","=",$uid)->update($post);
            if($is_save!=1)
            {
                $this->error(lang("update_error"));
            }
        }
        //当前用户修改自己的 重新设置cookie 信息
        if(($shen_biaoshi==1 || $shen_biaoshi==3) &&  $is_save==1)
        {
            cookie("login_t",$post["t"]);
            cookie("login_ip",$post["ip"]);
            cookie("u_name",$post["n"],36000*7);
            cookie("login_token",create_token());;// 存储登录 token
        }
        $this->success(lang("update_sucess"),$this->cur_index);
    }

    // 删除单个用户
    public function delete()
    {
        $uid=$this->request->param("uid/d");
        if(!isset($uid) || $uid<=1 || cookie("login_uid")==$uid)
        {
            $this->error(lang("user_deny_del"));
        }
        // 如果不是特别超级管理员 验证权限
        if(!(cookie("login_uid")==1 && cookie("login_role")==1))
        {
            $user=new Users();
            $this->vail($user,$uid);
        }
         $is_del=Users::destroy($uid);
         if($is_del==1)
         {
             $this->success(lang("del_sucess"));
         }else
         {
            $this->error(lang("del_error"));
         }
    }

    // 删除多个用户
    public function deletes()
    {
        $uid=$this->request->post();
        if(empty($uid) || empty($uid["uid"]))
        {
             $this->error(lang("u_s_del"));
        }
        $uid=trim_str(array_unique(array_filter($uid["uid"])));
        if(empty($uid))
        {
             $this->error(lang("u_s_del"));
        }
        // 判断当前用户身份
        if(cookie("login_uid")==1 && cookie("login_role")==1)
        {
            $shen_biaoshi=1;
        }else
        {
            $shen_biaoshi=2;
            $key=array_search(cookie("login_uid"),$uid);
            if($key !== false)
            {
                array_splice($uid,$key,1);
            }
        }
        $key=array_search(1,$uid);
        if($key !== false)
        {
            array_splice($uid,$key,1);
        }
        if(empty($uid))
        {
            $this->error(lang("u_s_del"));
        }

        $uid=implode(",",$uid);

        // 当前用户的角色权重
        $role=new Roles();
        $cur_rw=$role->get_rw(cookie("login_role"));

        if($shen_biaoshi==1)  // 如果是特别超级管理员删除用户
        {
            $is_del=Users::destroy($uid);
        }else               // 其他管理员删除用户
        {
            $user=new Users();
            if(config("no_admin_see_user"))
            {
                // 取得要删除用户的角色权重 大于当前用户的角色权重 的用户
                $n_uid=$user->get_rw($uid,$cur_rw);
                if(empty($n_uid))
                {
                    $this->error(lang("page_error"));
                }
            }else
            {
                // 取得自己创建的用户
                $n_uid=$user->get_f($uid,cookie("login_uid"));
                if(empty($n_uid))
                {
                    $this->error(lang("user_deny_del"));
                }
            }
            $n_uid=implode(",",$n_uid);
            $is_del=Users::destroy($n_uid);
        }
        if($is_del>=1)
        {
            $this->success(lang("meun_a_delete").$is_del.lang("u_del_s"));
        }else
        {
            $this->error(lang("del_error"));
        }
    }


    //验证判断是否有操作权限 --- 修改删除权限
    private function vail($user,$uid)
    {
        if($uid!=cookie("login_uid")) // 如果修改的不是自己
        {
            // 可修改角色权重小于自己---- 如果开启可以访问非自己创建用户
            if(config("no_admin_see_user"))
            {
                // 判断是否有权修改用户----通过角色权重判断
                $is_edit=$user->compare_rw(cookie("login_uid"),$uid);
                if(!$is_edit)
                {
                    $this->error(lang("user_deny_edit"));
                }
            }else
            {
                //判断用户是不是自己创建的
                $is_f=$user->is_founder(cookie("login_uid"),$uid);
                if(!$is_f)
                {
                    $this->error(lang("user_deny_edit"));
                }
            }
        }
    }


} 