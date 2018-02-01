<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-27
 * Time: 上午10:07
 * 用户登录类
 */

class Login extends Com{

    private  $u_id="id";// 用户id --与数据表文件数据一致
    private  $u_n="n";//---- 用户名称
    //用户进入登录页面如果已经登录
    public function is_login()
    {
        $login_file=self::$conf_data["TEM_DIR"]."login.php";
        if(!isset($_SESSION["token"]) || !isset($_SESSION[$this->u_id]))
        {
            require $login_file;
            exit;
        }
        $g_u=explode(DE_LIMITER,$_SESSION[$this->u_id]); // 取得组id 用户数据文件编号   用户id
        $u_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.DATA_USER.DE_LIMITER.$g_u[1].self::$conf_data["DATA_EXT"];
        if(!is_file($u_file))
        {
            session_unset();
            require $login_file;;
            exit;
        }
        // 判断组数据是否存在
        $g_data=$this->read_data(DATA_GROUP);
        // 判断用户数据
        $u_data=$this->read_data(DATA_USER.DE_LIMITER.$g_u[0]);

        if((!isset($u_data[$g_u[1]-1])) || (!isset($g_data[$g_u[0]])) || ($u_data[$g_u[1]-1][$this->u_id]!=$_SESSION[$this->u_id]))
        {
            session_unset();
            require $login_file;;
            exit;
        }
        // 身份标识 验证
        $gid_token=$this->price_she_biaoshi($g_u[0],$_SESSION["token"],PHP_CON."Login.php");

        // 权限分配
        $error_fn=function($login_file){
            require $login_file;
            exit;
        };
        $this->Jurisdiction($gid_token,$error_fn);
    }

    // 执行--验证登录
    public function  exce_login()
    {

        $post=$this->add_slashes($_POST);
     // 验证验证码
        if(strtolower($post["code"])!==strtolower($_SESSION["code"]))
        {
            $this->skip("error",null,"验证码错误 登录");
        }

        $g_u=explode(DE_LIMITER,$post[$this->u_id]); // 取得组id 用户数据文件编号   用户id

        // 判断 用户数据文件是否存在
        $u_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.DATA_USER.DE_LIMITER.$g_u[1].self::$conf_data["DATA_EXT"];
        if(!is_file($u_file))
        {
            $this->skip("error",null,"用户编号错误 登录");
        };
        // 判断组数据是否存在
        $g_data=$this->read_data(DATA_GROUP);
        if(!isset($g_data[$g_u[0]]))
        {
            $this->skip("error",null,"用户编号错误 登录");
        }

        // 判断用户信息
        $u_data=$this->read_data(DATA_USER.DE_LIMITER.$g_u[0]);
        if(!isset($u_data[$g_u[1]-1]) && ($u_data[$g_u[1]-1][$this->u_id]==$post[$this->u_id]))
        {
            $this->skip("error",null,"用户编号或密码错误 登录");
        }
        // 身份标识
        $gid_token=$this->price_she_veri($g_u[0]);

        // session 处理
        session_unset();
        $_SESSION["token"]=$this->en_token($gid_token);
        $_SESSION[$this->u_id]=$post[$this->u_id];
        $_SESSION[$this->u_n]=$u_data[$g_u[1]-1][$this->u_n];

        if(isset($post["keep"]) && $post["keep"]==true)
        {
            setcookie($this->u_id,$post[$this->u_id],self::$conf_data["COOKIE_TIME"]);
        }//----------------------------------------记住编号
        unset($_SESSION["code"]);
        // 根据身份跳转页面
        $error_fn=function(){
            $this->skip("error",null,"session 存储失败 登录");
        };
        $this->Jurisdiction($gid_token,$error_fn);
    }

    //退出登录
    public function out_login()
    {
        session_destroy();
        header("Location:".PHP_CON."Login.php");
        exit;
    }

    /**权限分配
     * @parem $gid_token  用户的token
     * @parem $error_fn 错误处理函数
     * @parem $skip_file 错误跳转页面
    */
    private  function  Jurisdiction($gid_token,$error_fn,$skip_file=null)
    {
        $price_data=$this->price_data_field();
        $price_data_key=array_keys($price_data);
        if($gid_token==="Superadministrator")
        {
            header("location:".PHP_CON."Main.php");
            exit;
        }
        else if(in_array($gid_token,$price_data_key))
        {
            header("location:".PHP_CON."Price.php");
            exit;
        }else
        {
            $error_fn($skip_file); // 函数做为参数；
        }
    }

} 