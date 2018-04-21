<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-4-13
 * Time: 下午3:40
 */

namespace app\back\controller;
use app\back\model\Admin;
use app\back\model\Users;
use app\back\validate\Vbackuser;
use think\Controller;
use think\Exception;
use code\Code;
use think\facade\Cookie;


class Login extends Controller {

    public function index()
    {
       if(request()->param("lock")==1) // 判断从lock 文件跳转来的页面 更改 Lock  文件名 为 .Lock
       {
           $dir=config("root_dir")."back/controller/";
           $old_f=$dir."Lock.php";
           $new_f=$dir.".Lock.php";
           try{
               if(is_file($old_f))  // 防止用户多次访问 /back/login/index/lock/1.html
                    rename($old_f,$new_f);
           }catch (\Exception $e)
           {
                echo lang("login_edit_lock");
                log_info($e);
                exit;
           }
       }
        // 记录用户是直接访问的登录页面，还是已经登录之后再次访问登录页面 验证是否登录
        // 不存在!session("?HTTP_REFERER")代表需要验证用户是否登录过 check_login() 为 false 是已经登录，ture 未登录
       if(!session("?HTTP_REFERER") && !check_login())
       {    // 参数代表不需要验证登录
           return redirect('Index/index',["check_login" => 1]);// 进入后台首页
       }else
       {
           session("HTTP_REFERER",null); //验证完清除 标记
           return $this->fetch("/login");
       }
    }

    // 执行用户登录
    public function  sign()
    {
        if(!request()->isPost() || !session("?code"))
        {
            return $this->fetch("/login");
        }
        // 验证是否登录
        $code=session("code");
        $post=request()->post();

        if(strtolower($post["code"])!=strtolower($code))
        {
            session("code",null);
            $this->error(lang("login_code_error"));
        }
        session("code",null);
        $data = [
            'n'  => $post["n"],
            'pw' => $post["pw"],
        ];
        // 验证数据
        Cookie::clear("login_");
        $validate=new Vbackuser();
        if (!$validate->scene('login')->check($data))
        {
            $this->error($validate->getError());
        }
        $admin=new Admin();
        $admin_user=$admin->login($post["n"]);

        if(empty($admin_user))
        {
            $user= new Users();
            $pu_user=$user->login($post["n"]);
            if(empty($pu_user))
            {
                $this->error(lang("login_usernot_exist"));
            }
            // 如果是普通管理员
            if(encry($post["pw"])!==$pu_user[0]["pw"])
            {
                $this->error(lang("login_pw_error"));
            }
            $is_update=$user->update_login($pu_user[0]["uid"]);
            if(!$is_update)
            {
                $this->error(lang("login_error"));
            }
            Cookie::set("login_t",$pu_user[0]["t"]);
            Cookie::set("login_ip",$pu_user[0]["ip"]);
            Cookie::set("login_uid",$pu_user[0]["uid"]);
            Cookie::set("login_role",$pu_user[0]["role"]);
        }else
        {  // 用户是超级管理员

           if(encry($post["pw"])!==$admin_user[0]["pw"])
            {
                $this->error(lang("login_pw_error"));
                exit;
            }
            $is_update=$admin->update_login($post["n"]);

            if(!$is_update)
            {
                $this->error(lang("login_error"));
            }
            Cookie::set("login_t",$admin_user[0]["t"]);
            Cookie::set("login_ip",$admin_user[0]["ip"]);
            Cookie::set("login_uid",1);
            Cookie::set("login_role",$admin_user[0]["role"]);
        }
        Cookie::set("u_name",$post["n"],36000*7);
        cookie("login_token",create_token());;// 存储登录 token
     //   return redirect('Index/index');// 进入后台首页
        return redirect('Index/index',["check_login" => 1]);
    }

    /** 生成验证码
     * */
    public function verify()
    {
       $veri=new Code();
       return   $veri->cimg(100,43,20,5);
    }

    // 退出登录
    public  function outLogin()
    {
        Cookie::clear("login_");
        session('HTTP_REFERER',true);
        return redirect('Login/index');
    }

    //ajax 请求  用户删除 记住的账号
    public function delName()
    {
        if(request()->isPost() && request()->post("del_name"))
        {
            Cookie::clear("u_name");
            echo "success";
        }else
        {
           echo "del name error" ;
        }
    }

} 