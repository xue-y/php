<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-11
 * Time: 上午10:26
 * 微信端登录验证--客户登录
 */

namespace Home\Controller;
use My\WxController;
use Think\Controller;

class LoginController extends Controller{
    // 进入登录
    public function sign()
    {

        $s_pix=C('SESSION_PREFIX');
        if(isset($_SESSION[$s_pix.'phone']))
            $id=$_SESSION[$s_pix.'phone'];
        else
        {
            if(isset($_COOKIE[$s_pix.'phone']))
                $id=$_COOKIE[$s_pix.'phone'];
        }
        $this->assign("id",$id);
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

           $this->de_session($s_pix); //销毁登录的session

           $_SESSION[$s_pix.'login_status']=1; // 登录状态
           $_SESSION[$s_pix.'id']=$is_user["id"];
           $_SESSION[$s_pix.'phone']=$post["phone"];
           $_SESSION[$s_pix.'token']=pass_md5(sha1($is_user["id"]).$post["phone"]);

           if(isset($_POST['Long-term']) && ($_POST['Long-term']==1))
           {
               cookie($s_pix.'phone',$post["phone"],3600*24*30,'/');
           }// 用户信息保存一个月

           header("Location:http://".$_SERVER['SERVER_NAME'].__MODULE__."/Index/index");
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
        $this->de_session($s_pix); //销毁登录的session

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

    /** 删除session 销毁登录的session
    */
    private function de_session($s_pix)
    {
        unset($_SESSION[$s_pix.'login_status']);
        unset($_SESSION[$s_pix.'id']);
        unset($_SESSION[$s_pix.'phone']);
        unset($_SESSION[$s_pix.'token']);
        /*session($s_pix.'login_status',null);
        session($s_pix.'id',null);
        session($s_pix.'phone',null);
        session($s_pix.'token',null); */// 清空session
    }

}