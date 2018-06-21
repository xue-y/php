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

/**
 * 返回经addslashes处理过的字符串或数组
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
 function add_slashes($string)
{
    if(!is_array($string)) return addslashes(trim($string));
    foreach($string as $key => $val)
    {$string[$key] =add_slashes($val);}
    return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组 函数删除由 addslashes() 函数添加的反斜杠。
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
 function str_slashes($string){
    if(!is_array($string)) return stripslashes($string);
    foreach($string as $key => $val) $string[$key] =str_slashes($val);
    return $string;
}

/** 封装密码
 * @parem 原密码值
 * */
function pass_md5($pass)
{
    return md5($pass.C("PWD_PREFIX"));
//    md5($this->add_slashes($_POST['u_pass']).C(PWD_PREFIX));
}


