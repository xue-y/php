<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-10-1
 * Time: 上午10:35
 */
 function is_login($s_pix)        //验证是否登录
 {
     if(isset($_SESSION[$s_pix.'id']) && isset($_SESSION[$s_pix.'n']) && isset($_COOKIE[$s_pix.'token']))
     {
         $user=D('User');
         $info=$user->login_select($_SESSION[$s_pix.'id']);
         $token=sha1($info['times'].$_SESSION[$s_pix.'id']);
         if(($info['u_name']==$_SESSION[$s_pix.'n']) && ($_COOKIE[$s_pix.'token']==$token))
         {
           //  http://".$_SERVER["HTTP_HOST"]."
            echo "<script>window.location.href='".__ROOT__."/Back/Main/index'</script>";
         }
     }
 }
