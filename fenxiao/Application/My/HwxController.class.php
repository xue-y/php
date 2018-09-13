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

class HwxController extends Controller{

    public $s_pix;
    public $uid;
    public $login_status;// 登录状态 0 未登录 1 已经登录

    public  function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();
        visit_num();// 限制用户频繁刷新页面

        if(!isset($_SESSION))
        {session_start();}
        $this->s_pix=C('COOKIE_PREFIX');
        $this->uid=session("id");

        $this->is_sign(); // 验证是否登录 ----判断验证是否登录失效

        $cus=D("Customer");  // 消息个数
        $this->assign("meg",$cus->meg_c($this->uid));
        $cus=null;
        unset($cus);
    }

    // 验证是否登录
    protected function is_sign()
    {
         if(!isset($this->uid) || !isset($_COOKIE[$this->s_pix.'phone']) || !isset($_COOKIE[$this->s_pix.'token']))
          {
              // 直接跳转登录页面
             echo "<script>window.location.href='".__MODULE__."/Login/sign'</script>";
             exit;
          }
        $uid_status=session('login_status'.$this->uid);
        if(!isset($uid_status) || $uid_status!=1)
        {
            // 直接跳转登录页面
            echo "<script>window.location.href='".__MODULE__."/Login/sign'</script>";
            exit;
        }
        //pass_md5(sha1($is_user["id"]).$post["phone"]);
       if(pass_md5(sha1($this->uid).$_COOKIE[$this->s_pix.'phone'])!=$_COOKIE[$this->s_pix.'token'])
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