<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-11
 * Time: 上午10:17
 * 微信登录验证
 */

namespace My;
use Think\Controller;

class WxController extends Controller{

    public $s_pix;
    public $uid;
    protected $login_status;// 登录状态 0 未登录 1 已经登录

    public  function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();

        if(!isset($_SESSION))
        {session_start();}
        $this->s_pix=C('SESSION_PREFIX');
        $this->is_sign(); // 验证是否登录 ----判断验证是否登录失效
        if(isset($_SESSION[$this->s_pix.'id']) && intval($_SESSION[$this->s_pix.'id'])>=1)
        {
            $this->uid=$_SESSION[$this->s_pix.'id'];
        }
        $cus=D("Customer");  // 消息个数
        $this->assign("meg",$cus->meg_c($this->uid));
        $cus=null;
        unset($cus);
    }

    // 验证是否登录
    protected function is_sign()
    {
        if(!isset($_SESSION[$this->s_pix.'login_status']) || $_SESSION[$this->s_pix.'login_status']!=1)
        {
            // 直接跳转登录页面
            echo "<script>window.location.href='".__MODULE__."/Login/sign'</script>";
            exit;
        }
         if(!isset($_SESSION[$this->s_pix.'id']) || !isset($_SESSION[$this->s_pix.'phone']) || !isset($_SESSION[$this->s_pix.'token']))
          {
              // 直接跳转登录页面
             echo "<script>window.location.href='".__MODULE__."/Login/sign'</script>";
             exit;
          }

           if(pass_md5(sha1($_SESSION[$this->s_pix.'id']).$_SESSION[$this->s_pix.'phone'])!=$_SESSION[$this->s_pix.'token'])
          {
             echo "<script>window.location.href='".__MODULE__."/Login/sign'</script>";
             exit;
          }

    }

    // 判断get id 是否合法
    protected function get_id($id)
    {
        if(isset($_GET[$id]) && intval($_GET[$id])>=1)
        {
            return intval($_GET[$id]);
        }else
        {
            return false;
        }
    }

    protected function post_id($id)
    {
        if(isset($_POST[$id]) && intval($_POST[$id])>=1)
        {
            return intval($_POST[$id]);
        }else
        {
            return false;
        }
    }

} 