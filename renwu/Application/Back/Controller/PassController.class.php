<?php
namespace Back\Controller;
use Think\Controller;
class PassController extends Controller { // 忘记密码
    public function index(){
       //判断用户是否登录
        is_login(C('SESSION_PREFIX'));
        $tit="找回密码";
        $user=D("User");

        if(!isset($_POST) || empty($_POST))
        {
            $this->assign("pos","$tit ---- 验证账号");
            $this->display("one");
        }

        if($_POST["setup"]==1)// 执行第一步
        {
            $post=$this->add_slashes($_POST);
            $mail_state=$user->pass_one($post);
            if(!isset($mail_state))
            {
                $this->error("用户信息错误,或未绑定邮箱");
            }
            S("mail_state",$mail_state,3600);
            $this->assign(array("mail_state"=>$mail_state,"mail"=>$post["mail"],"pos"=>"$tit ---- 验证邮箱","id"=>$post["id"]));
            $this->display("two");
        }

        //------------------------------------------------------------------// 执行第二步
        if($_POST["setup"]==2)
        {
            $mail_ver=S("mail_ver");
            if(!isset($mail_ver) || empty($mail_ver))
            {
               $this->error("邮箱验证码已过期，请重新获取");
            }
            $post=$this->add_slashes($_POST);
            if($post["mail_ver"]!=$mail_ver)
            {
                $this->error("邮箱验证码错误");
            }

            $af=$user->pass_two($post);

            if(!isset($af) || $af!=1)
            {
                $this->error("邮箱验证失败");
            }
            $token=md5($post["mail"].C(PWD_PREFIX).'-'.$post["id"]);
            S("token",$token,3600);
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
            $s_token=S("token");
            if(!isset($s_token) || empty($s_token))
            {
                $this->error("你长时间未操作，请重新刷新页面");
            }
            $post=$this->add_slashes($_POST);
            $pattern='/^([\w\.\@\!\-\_]){6,12}$/i';
            preg_match($pattern,$post["u_pass"],$match);
            if($match[0]!==$post["u_pass2"])
            {
                $this->error("两次密码不一致");
            }
             $u_info=$user->pass_three($post["id"]);

             $token=md5($u_info["mail"].C(PWD_PREFIX).'-'.$post["id"]);
            if($token!==$s_token)
            {
                $this->error("用户信息或邮箱错误");
            }
            $n_pass=md5($post['u_pass'].C(PWD_PREFIX));
            if($n_pass===$u_info["u_pass"])
            {
                $this->success("修改密码成功",U("Login/sign"));
            }else
            {
                $af=$user->pass_return($post["id"],$n_pass);
                $af==1? $this->success("修改密码成功2",U("Login/sign")):$this->error("重置密码失败");
            }
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
        $mail_state=S("mail_state");
        if(!isset($mail_state) || empty($mail_state))
        {
          echo "mail_state_no";exit;
        }
        $post=$this->add_slashes($_POST);
        $post["jihuo"]=$mail_state;
        $user=D("User");
        $is_user=$user->pass_user_is($post);
        if($is_user!=1)
        {
            echo "user_is_no";exit;
        }

        //--------------------------------------发送邮件的时间
        $send_mail_t=S('send_mail_t');
        if(!isset($send_mail_t) || empty($send_mail_t))
        {
            S('send_mail_t',time(),60);
        }
        else
        {
            $sleep=60-(time()-$send_mail_t);
            if($sleep>0)
                sleep($sleep);
        }
        //--------------------------------------发送邮件的次数
        $send_mail_c= S("send_mail_c");
        if(!isset($send_mail_c) || empty($send_mail_c))
        {
            if(isset($mail_ver) && !empty($mail_ver))
            {
                S("mail_ver",NULL);
            }
            S("send_mail_c",1,3600*24);
        }else
        {
            $send_mail_c= S("send_mail_c")+1;
            if($send_mail_c>5)
            {
                echo "send_mail_c";exit;
            }
            else
            {
                S("send_mail_c",$send_mail_c,3600*24);
            }
        }

        $num=substr(sha1(uniqid(TRUE)),mt_rand(0,35),5); //------------邮箱验证生成
        S("mail_ver",$num,3600);
        $mail_ver=S("mail_ver");
        // 执行发送邮件
        //******************** 配置信息 ********************************
        $smtpserver = S_SERVER;//SMTP服务器
        $smtpserverport =S_PORT;//SMTP服务器端口
        $smtpusermail = S_MAIL;//SMTP服务器的用户邮箱
        $smtpemailto =$post["mail"];//发送给谁
        $smtpuser = S_MAIL;//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
        $smtppass = S_PASS;//SMTP服务器的用户密码
        $mailtitle = "验证邮箱";//邮件主题
        $mailcontent="忘记密码找回，非本人操作，请忽略。邮箱验证码 <a>".$mail_ver."</a>  验证邮箱";
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
            S("mail_ver",NULL);
            exit();
        }
        echo "mail_send_ok";
    }

    /**
     * 返回经addslashes处理过的字符串或数组
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    private  function add_slashes($string)
    {
        if(!is_array($string)) return addslashes(trim($string));
        foreach($string as $key => $val)
        {$string[$key] =$this->add_slashes($val);}
        return $string;
    }

    /**
     * 返回经stripslashes处理过的字符串或数组 函数删除由 addslashes() 函数添加的反斜杠。
     * @param $string 需要处理的字符串或数组
     * @return mixed
     */
    private function str_slashes($string){
        if(!is_array($string)) return stripslashes($string);
        foreach($string as $key => $val) $string[$key] =$this->str_slashes($val);
        return $string;
    }

}