<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-29
 * Time: 上午9:00
 * 个人资料-管理员
 */

namespace Back\Controller;
use My\MyController;

class PersonalController extends MyController{  //用户信息
// 函数名使用 email  字段名/变量名称 使用 mail
    public function index(){ //个人资料---基本信息

        $user=D("User");
        $u_info=$user->user_data($this->u_id);
        $role=D("Role");
        $role_n=$role->role_n($u_info["role_id"]);
        $u_info["role_n"]=$role_n[0]["n"];

        // 取得附件表信息
        $u_fj=$user->user_fj($this->u_id);

        if($u_info["is_jihuo"]==1)// 如果等于1 ---已经绑定
        {
            $u_info["mail_state"]=1;
        }else                       //未验证 或已验证未绑定
        {
            $u_info["mail_state"]=0;
        }

        $pos["c"]="个人资料";
        $is_admin=$this->is_admin($user);
        $this->assign(array("info"=>$u_info,"pos"=>$pos,"is_admin"=>$is_admin,"u_fj"=>$u_fj,"mail_send_c"=>P_O_C,"mail_deny_t"=>MAIL_DENY_T));
        $this->display();
    }

    public function email() //邮箱操作
    {
       $this->ver_eid();
       $mail=$this->add_slashes($_POST["mail"]);
       $user=D("User");
       $af=$user->mail_ver($mail,$this->u_id);
       if(!isset($af) || $af!=1)
       {
           echo "mail_error";exit;
       }
        echo "ok";exit;
    }

    public function emailSend() //验证邮箱---发送邮件
    {
        set_time_limit(0);
        $this->ver_eid();
        $user=D("User");
        $post=$this->add_slashes($_POST);
        $t=$user->mail_ver2($post);

        if($post["state"]==0  &&  ($t==0 || $t==1)) //------------通过 state 参数判断用户是绑定还是解除绑定
        {
            echo "mail_error";exit;
        }//加入绑定

        if($post["state"]==1 && ($t!=1))
        {
            echo "mail_error";exit;
        }//解除绑定

        //--------------------------------------发送邮件的时间
         $send_mail_t=S($this->u_id.'send_mail_t');
         if(!isset($send_mail_t) || empty($send_mail_t))
         {
             S($this->u_id.'send_mail_t',time(),MAIL_DENY_T);
         }
         else
         {
             $sleep=MAIL_DENY_T-(time()-$send_mail_t); // 发送一个邮件时间间隔为 60 秒
             if($sleep>0)
             {
                 echo "delay_time";exit;
             }
         }
        //--------------------------------------发送邮件的次数
        $send_mail_c= S($this->u_id."send_mail_c");
        if(!isset($send_mail_c) || empty($send_mail_c))
        {
            if(isset($mail_ver) && !empty($mail_ver))
            {
                S($this->u_id."mail_ver",NULL);
            }
            S($this->u_id."send_mail_c",1,3600*24);
        }else
        {
            $send_mail_c= S($this->u_id."send_mail_c")+1;
            if($send_mail_c>=P_O_C) // 限制一天发送邮件的个数 包含找回密码的发送邮件次数
            {
                echo "send_mail_c";exit;
            }
            else
            {
                S($this->u_id."send_mail_c",$send_mail_c,3600*24);
            }
        }
        $num=substr(sha1(uniqid(TRUE)),mt_rand(0,35),5);
        S($this->u_id."mail_ver",$num,3600);
        $mail_ver=S($this->u_id."mail_ver");  // 邮箱验证时间 1个小时

        // 执行发送邮件
        //******************** 配置信息 ********************************
        $smtpserver = S_SERVER;//SMTP服务器
        $smtpserverport =S_PORT;//SMTP服务器端口
        $smtpusermail = S_MAIL;//SMTP服务器的用户邮箱
        $smtpemailto =$post["mail"];//发送给谁
        $smtpuser = S_MAIL;//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
        $smtppass = S_PASS;//SMTP服务器的用户密码
        $mailtitle = "验证邮箱";//邮件主题
        $mailcontent="请点击一下链接<a href='http://".$_SERVER["HTTP_HOST"].__CONTROLLER__."/emailVer?jihuo=".$t."&id=".$this->u_id."&state=".$post["state"]."'>http://".$_SERVER["HTTP_HOST"].__CONTROLLER__."/emailVer?jihuo=".$t."&id=".$this->u_id."</a>，输入验证码 <a>".$mail_ver."</a>  验证邮箱";
        //邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new \Think\Smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
        //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false;//是否显示发送的调试信息
        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
        if($state=="")
        {
            echo "mail_send_error";
            S($this->u_id."mail_ver",NULL);
            $send_mail_c-=1;
            S($this->u_id."send_mail_c",$send_mail_c);
            S($this->u_id.'send_mail_t',NULL);
            exit();
        }
        echo "mail_send_ok";
    }

    public function emailVer() //验证邮箱---进入验证码
   {
      if(!isset($_GET) || empty($_GET))
       {
          $this->error("页面错误");
       }
       $get=$this->add_slashes($_GET);
       if($get["id"]!=$this->u_id)
       {
           $n=$_SESSION[$this->s_pix.'n'];
           $this->write_log("篡改其他用户邮箱： ID: $this->u_id | name: $n 修改邮箱用户 {$get['id']}");
           $this->error("个人信息错误");// 用户id出错
       }

       $mail_ver=S($this->u_id."mail_ver");
       if(!isset($mail_ver) || empty($mail_ver))
       {
           $this->error("验证码已过期请重新验证",__MODULE__."/Main/index#Personal");
       }

       $user=D("User");
       $u_arr["id"]=$this->u_id;
       $u_arr["is_jihuo"]=$get["jihuo"];
       $mail=$user->mail_state($u_arr);
       if(!isset($mail) || empty($mail))
       {
           $this->error("用户邮箱错误");
       }
       $this->assign(array("pos"=>"验证邮箱","mail"=>$mail,"id"=>$this->u_id,"state"=>$get["state"]));
       $this->display();
   }

    public function execeEailVer() // 执行邮箱验证
    {
        if(!isset($_POST) || empty($_POST))
        {
            $this->error("用户信息以及用户邮箱信息不全");
        }
        $mail_ver=S($this->u_id."mail_ver");
        if(!isset($mail_ver) || empty($mail_ver))
        {
            $this->error("验证码已过期请重新验证",__MODULE__."/Main/index#Personal");
        }

        $post=$this->add_slashes($_POST);
        if($post["mail_ver"]!=$mail_ver)
        {
            $this->error("邮箱验证码错误");
        }

        $user=D("User");
        if($post["state"]==0) //-----------------------加入绑定
        {
            $af=$user->mail_save($post["id"],$post["mail"]);
            if($af!=1)
            {
                $this->error("邮箱激活失败");
            }else
            {     if(isset($mail_ver))
                  {
                    S($this->u_id."mail_ver",NULL);
                  }
                $this->success("邮箱激活成功",__MODULE__."/Main/index#Personal");
            };
        }else               //---------------------------解除绑定
        {
            $af=$user->mail_relieve($post["id"],$post["mail"]);
            if($af!=1)
            {
                $this->error("邮箱解除绑定失败");
            }else
            {
                if(isset($mail_ver))
                {
                    S($this->u_id."mail_ver",NULL);
                }
                $this->success("邮箱解除绑定成功",__MODULE__."/Main/index#Personal");
            };
        }

    }

    /**邮箱验证 用户ID 邮箱
    */
    private function ver_eid()
    {
        if (!filter_input(INPUT_POST, "mail", FILTER_VALIDATE_EMAIL))
        {
            echo "mail_error";exit;
        }
        if(!filter_has_var(INPUT_POST, "id") || ($_POST["id"]!=$this->u_id))
        {
            echo "id_error";exit;
        }
    }
} 