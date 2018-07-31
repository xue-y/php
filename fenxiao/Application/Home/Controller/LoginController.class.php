<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-11
 * Time: 上午10:26
 * 微信端登录验证--客户登录
 */

namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller{
    // 进入登录
    public function sign()
    {
		$id=null;
        $s_pix=C('SESSION_PREFIX');
		
        if(isset($_COOKIE[$s_pix.'phone']))
            $id=$_COOKIE[$s_pix.'phone'];

        // 记录用从哪个页面跳转过来的
        if(isset($_GET["history"]) && !empty($_GET["history"]))
        {
            $old_url=$_GET["history"];
        }else
        {
            $old_url="";
        }

        $this->assign(array("id"=>$id,"history"=>$old_url));  // 用户带上 history
        $this->sign_is_login(); // 如果已经登录直接跳转个人主页
        $this->display("/login");
    }

    // 执行登录
    public function login()
    {
        $post=add_slashes($_POST);
        $cus=D("Customer");
        $is_user=$cus->yan_login($post["phone"]);
        if(empty($is_user))
        {
            $this->error("用户不存在");
        }

       if($is_user["pass"]==pass_md5($post["pass"]))
       {
           if(!isset($_SESSION))
           {session_start();}

           $s_pix=C('SESSION_PREFIX');

           de_session($s_pix); //销毁登录的session

           $_SESSION[$s_pix.'login_status']=1; // 登录状态
           $_SESSION[$s_pix.'id']=$is_user["id"];
           $_SESSION[$s_pix.'phone']=$post["phone"];
           $_SESSION[$s_pix.'token']=pass_md5(sha1($is_user["id"]).$post["phone"]);

           if(isset($post['Long-term']) && ($post['Long-term']==1))
           {
               cookie($s_pix.'phone',$post["phone"],3600*24*30,'/');
           }// 用户信息保存一个月

           if(isset($post["history"]) && !empty($post["history"]))
           {
               $old_url="/".$post["history"];
           }else
           {
               $old_url="/Index/index";
           }
           header("Location:http://".$_SERVER['SERVER_NAME'].__MODULE__.$old_url);
           exit;
           // $this->redirect('Home/Info/index','',0); nginx 不支持
           //登录成功跳转到信息个人主页

       }else
       {
           $this->error("用户名或密码错误");
       }
    }

    public  function  logout() //-----------------------退出登录
    {
        $s_pix=C('SESSION_PREFIX');
        de_session($s_pix); //销毁登录的session
        echo "<script>window.location.href='".__CONTROLLER__."/sign'</script>";
        exit;
    }

    /** 登录页面是否已经登录
     * */
    private function sign_is_login()
    {
        $s_pix=C('SESSION_PREFIX');
        if(isset($_SESSION[$s_pix.'login_status'])  &&  $_SESSION[$s_pix.'login_status']==1 && isset($_SESSION[$s_pix.'phone']) && isset($_SESSION[$s_pix.'token']) &&  (pass_md5(sha1($_SESSION[$s_pix.'id']).$_SESSION[$s_pix.'phone'])==$_SESSION[$s_pix.'token']))
        {
            echo "<script>window.location.href='".__MODULE__."/Index/index'</script>";
            exit;
        }
    }



}