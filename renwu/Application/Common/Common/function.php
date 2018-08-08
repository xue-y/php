<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-10-1
 * Time: 上午10:35
 */
 function is_login($id,$s_pix)        //验证是否登录
 {
     if(!empty($id) && isset($_COOKIE[$s_pix.'n']) && isset($_COOKIE[$s_pix.'token']))
     {
         $user=D('User');
         $info=$user->login_select($id);
         $token=sha1($info['times'].$id);
         if(($info['u_name']==$_COOKIE[$s_pix.'n']) && ($_COOKIE[$s_pix.'token']==$token))
         {
           //  http://".$_SERVER["HTTP_HOST"]."
            echo "<script>window.location.href='".__ROOT__."/Back/Main/index'</script>";
            exit;
         }
     }
 }

/** 解密函数
 * */
function de_xtea($s_pix)
{
    $id="";
    $xtea=new \Think\Crypt\Driver\Think();
    $xtea_id_key=$xtea->encrypt($s_pix,"id");
    $xtea_id_val=cookie($xtea_id_key);
    if(isset($xtea_id_val) && !empty($xtea_id_val))
    {
        $id=$xtea->decrypt($xtea_id_val,$s_pix);
    }
    return $id;
}

/** 删除文件 循环删除文件夹 文件
 * @parem $run_n 文件跟路径
 * @parem $v 要删除文件夹名称
 * */
function  unlink_f($run_n,$v)
{
    $f_v=opendir($run_n.$v);
    while(($f=readdir($f_v))!==FALSE)
    {
        file_put_contents(LOG_F,'--'.$f.'--',FILE_APPEND);
        if($f!="." && $f!="..")
        {
            $new_fn=$run_n.$v.'/'.$f;
            if(is_dir($new_fn))
            {
                unlink_f($run_n.$v.'/',$f);
            }else
            {
                $f_d=unlink($new_fn);
                if(!isset($f_d))
                    write_log("删除缓存文件失败，请手动删除 $new_fn");
            }
        }
    }
    closedir($f_v);
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

//打印错误信息
/**
 * @parem $info 错误信息
 * @parem $url 默认为NULL,不终止进程
 * */
function write_log($info,$url=NULL)
{
    $con= __FILE__."|".$info."|".date('Y-m-d H:i:s',time())."\r\n";
    file_put_contents(LOG_F,$con,FILE_APPEND);
    if(isset($url))
    {  exit; }
}

