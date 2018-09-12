<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-10-1
 * Time: 上午10:35
 */
 function is_login($s_pix)        //验证是否登录
 {
       //此函数里面取不到 cookie 配置文件中的前缀
     if(isset($_SESSION[$s_pix]['id']) && isset($_COOKIE[$s_pix.'n']) && isset($_COOKIE[$s_pix.'token']))
     {
         $user=D('User');
         $info=$user->login_select($_SESSION[$s_pix]['id']);

         $token=sha1($info['times'].$_COOKIE[$s_pix.'id']);

         if(($info['u_name']==$_COOKIE[$s_pix.'n']) && ($_COOKIE[$s_pix.'token']==$token))
         {
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

/**判断路径是否存在并且可写
 * @parem $dir  需要检测的路径
 * @return 成功返回true失败无返回值
 *  */
function dir_write($dir)
{
    if(!file_exists($dir))
    {
        @mkdir($dir,0777);
    }
    if(!is_writable($dir))
    {
        @chmod($dir,0777);
    }
    if(file_exists($dir) && is_writable($dir))
    {
        return true;
    }
}

/** 自定义调试格式
 * @parem $val 需要转换输出的数据
 * @return type string 返回输出后的数据
 * */
function format_debug($val)
{
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}


