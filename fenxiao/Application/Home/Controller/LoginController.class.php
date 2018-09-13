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
use Think\Crypt\Driver\Des;

class LoginController extends Controller{
    // 进入登录
    public function sign()
    {
        sign_is_login(); // 如果已经登录直接跳转个人主页

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

           //$des=new Des();
           /*$des_login_status=$des->encrypt($is_user["id"],"1",3600);
           echo $des_login_status;exit;*/

           session('login_status'.$is_user["id"],1); // 登录状态

           if(isset($post['Long-term']) && ($post['Long-term']==1))// 用户信息保存一个月
           {
               cookie('phone',$post["phone"],3600*24*30,'/');
               cookie('Long-term',true,3600*24*30,'/');
           }else
           {
               cookie('phone',$post["phone"],USER_LOGIN_T,'/');
           }
           session('id',$is_user["id"]);
           $token=pass_md5(sha1($is_user["id"]).$post["phone"]);
           cookie('token',$token,USER_LOGIN_T,'/');

           if(isset($post["history"]) && !empty($post["history"]))
           {
               $old_url=str_replace(FEN_FU,"/",$post["history"]);
               $old_url="/".$old_url;
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


}