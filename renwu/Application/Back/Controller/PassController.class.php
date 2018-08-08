<?php
namespace Back\Controller;
use Think\Controller;
use Think\Crypt\Driver\Think;

class PassController extends Controller { // 忘记密码

    public function index(){

        $s_pix=C('COOKIE_PREFIX');
        $id=de_xtea($s_pix);
        if(!empty($id))   // 如果存在 id 判断用户是否登录
        {
            is_login($id,$s_pix);
        }

        $tit="找回密码";
        $user=D("User"); // 邮箱没有验证是否重复

        if(!isset($_POST) || empty($_POST))
        {
            $this->assign("pos","$tit ---- 验证账号");
            $this->display("one");
        }

        if($_POST["setup"]==1)// 执行第一步
        {
            $post=add_slashes($_POST);
            $this->en_xtea($post["id"]); // 加密 存储cookie
            $mail_state=$user->pass_one($post);
            if(!isset($mail_state))
            {
                $this->error("用户信息错误,或未绑定邮箱");
            }
            S($post["id"]."mail_state",$mail_state,PASS_T);  // ------------------邮箱状态

            $this->assign(array("mail_state"=>$mail_state,"mail"=>$post["mail"],"pos"=>"$tit ---- 验证邮箱","id"=>$post["id"],"mail_send_c"=>P_O_C,"mail_deny_t"=>MAIL_DENY_T));

            $this->display("two");
        }

        //------------------------------------------------------------------// 执行第二步
        if($_POST["setup"]==2)
        {
            $mail_ver=S($_POST["id"]."mail_ver");
            if(!isset($mail_ver) || empty($mail_ver))
            {
               $this->error("邮箱验证码已过期，请重新获取");
            }
            $post=add_slashes($_POST);
            if($post["mail_ver"]!=$mail_ver)
            {
                $this->error("邮箱验证码错误");
            }

            $af=$user->pass_two($post);

            if(!isset($af) || $af!=1)
            {
                $this->error("邮箱验证失败");
            }

            $token=$this->token($post["mail"],$post["id"]);
            S($post["id"]."token",$token,PASS_T);   // ------------------id_token

            $this->assign(array("pos"=>"$tit ---- 更改密码","id"=>$post["id"]));
            $this->display("three");
        }

        //------------------------------------------------------------------// 执行第三步
        if($_POST["setup"]==3 && isset($_POST["id"]) && !empty($_POST["id"]))
        {
            if(!filter_input(INPUT_POST,"u_pass") || !filter_input(INPUT_POST,"u_pass2"))
            {
                $this->error("用户密码错误"); // 非法 输入的密码
            }

            $id=de_xtea(C('COOKIE_PREFIX')); // 解密取得id
            $s_token=S($id."token"); //$post["id"]

            if(!isset($s_token) || empty($s_token))
            {
                $this->error("你长时间未操作，请返回上一步，重新发送邮件验证",10);
            }
            $post=add_slashes($_POST);
            $pattern='/^([\w\.\@\!\-\_]){6,12}$/i';
            preg_match($pattern,$post["u_pass"],$match);
            if($match[0]!==$post["u_pass2"])
            {
                $this->error("两次密码不一致");
            }
             $u_info=$user->pass_three($post["id"]);

            // 这里使用 post["id"] 不使用 $id ，已验证值是否一致
            $token=$this->token($u_info["mail"],$post["id"]);

            if($token!==$s_token)
            {
                $this->error("用户信息或邮箱错误");
            }
            $n_pass=md5($post['u_pass'].C(PWD_PREFIX));
			
			 if($n_pass===$u_info["u_pass"])
            {
                $this->success("修改密码成功",__MODULE__."/Login/sign");
            }else
            {
                $af=$user->pass_return($post["id"],$n_pass);
                $af==1? $this->success("修改密码成功",__MODULE__."/Login/sign"):$this->error("重置密码失败");
            }
			/* // U 方法会生成 带有html 后缀----nginx 默认不识别，需要额外配置
            if($n_pass===$u_info["u_pass"])
            {
                $this->success("修改密码成功",U("Login/sign"));
            }else
            {
                $af=$user->pass_return($post["id"],$n_pass);
                $af==1? $this->success("修改密码成功",U("Login/sign")):$this->error("重置密码失败");
            }*/
        }
    }

    /**发送邮件
     * */
    public  function emailSend()
    {
        if (!filter_input(INPUT_POST, 'mail', FILTER_VALIDATE_EMAIL))
        {
            echo "mail_error";exit;
        }
        $id=de_xtea(C('COOKIE_PREFIX')); // 解密取得id
        if(empty($id))
        {
            echo "timeout";exit;
        }
        $mail_state=S($id."mail_state");
        if(!isset($mail_state) || empty($mail_state))
        {
          echo "mail_state_no";exit;
        }
        $post=add_slashes($_POST);
        $post["jihuo"]=$mail_state;
        $user=D("User");
        $is_user=$user->pass_user_is($post);
        if($is_user!=1)
        {
            echo "user_is_no";exit;
        }

        //--------------------------------------发送邮件的时间间隔
        $send_mail_t=S($id.'send_mail_t');
        if(!isset($send_mail_t) || empty($send_mail_t))
        {
            S($id.'send_mail_t',time(),MAIL_DENY_T);
        }
        else
        {			
            $sleep=MAIL_DENY_T-(time()-$send_mail_t);
            if($sleep>0)
			{
				  echo "delay_time";exit;
			}
        }
        //--------------------------------------发送邮件的次数
        $send_mail_c= S($_SESSION[$post["id"]]."send_mail_c");
        if(!isset($send_mail_c) || empty($send_mail_c))
        {
            if(isset($mail_ver) && !empty($mail_ver))
            {
                S($id."mail_ver",NULL);
            }
            S($id."send_mail_c",1,3600*24); // ------------------发送邮件次数
        }else
        {
            $send_mail_c= S($id."send_mail_c")+1;
            if($send_mail_c>=P_O_C)  // 限制邮箱发送次数 // 发送邮件次数包含 绑定邮箱发送邮件
            {
                echo "send_mail_c";exit;
            }
            else
            {
                S($id."send_mail_c",$send_mail_c,3600*24);
            }
        }

        $num=substr(sha1(uniqid(TRUE)),mt_rand(0,35),5); //------------邮箱验证生成 唯一值
        S($id."mail_ver",$num,3600);
        $mail_ver=S($id."mail_ver");
        // 执行发送邮件
        //******************** 配置信息 ********************************
        $smtpserver = S_SERVER;//SMTP服务器
        $smtpserverport =S_PORT;//SMTP服务器端口
        $smtpusermail = S_MAIL;//SMTP服务器的用户邮箱
        $smtpemailto =$post["mail"];//发送给谁
        $smtpuser = S_MAIL;//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
        $smtppass = S_PASS;//SMTP服务器的用户密码
        $mailtitle = "验证邮箱";//邮件主题

        //$_SERVER['HTTP_HOST']
        $mailcontent="来自 <a href='".PROTOCOL.$_SERVER['SERVER_NAME']."'>".WBE_NAME."</a> 网站，忘记密码找回，非本人操作，请忽略。邮箱验证码 <a>".$mail_ver."</a>  验证邮箱";
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
            S($id."mail_ver",NULL); // 如果发送邮件失败 --  //------------邮箱验证 唯一值
            exit();
        }
        echo "mail_send_ok";
    }

    /**  加密 存储cookie
     * @parem $id int  要加密的值
     * */
    private function en_xtea($id)
    {
        $xtea=new Think();
        $s_pix=C('COOKIE_PREFIX');
        $xtea_id_key=$xtea->encrypt($s_pix,"id");
        $xtea_id_val=$xtea->encrypt($id,$s_pix);
        cookie($xtea_id_key,$xtea_id_val,PASS_T,"/");
    }

    /** 封装临时token
     * */
    private function token($mail,$id)
    {
        return md5($mail.C(PWD_PREFIX).'-'.$id);
    }

}