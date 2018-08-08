<?php
namespace Back\Controller;
use My\MyController;
use Think\Crypt\Driver\Think;

class LoginController extends MyController{  //登录

    /*
     * 进入登录页面
     * */
    public  function sign()
    {
       // 判断用户上次是否记住用户名和编号
        $this->assign(
            array('id'=>$this->u_id,)
        );

        $this->display('Public/login');
    }
    /*
     * 验证登录信息
     * */
    public function  login()
    {
        $code=trim($_POST['code']);
        $bool=$this->check_verify($code);

        if($bool!=1)
          {echo '验证码错误';exit;}

        $id=trim($_POST['id']);

        if(empty($id))
        { echo "不存在此用户";exit;}

        $user=D('User');

       if($user->create())
       {
            $u_info=$user->find($id);

            if(!isset($u_info) || empty($u_info))
            {
                echo "不存在此用户";exit;
            }
           $u_name=$u_info['u_name'];
           /* if($u_info['u_name']!=$u_name)
            {
                echo "用户名错误";exit;
            }*/

            $pass=md5(add_slashes($_POST['u_pass']).C(PWD_PREFIX));

            if($u_info['u_pass']!=$pass)
            {
                echo "密码错误";exit;
            }
            else
            {
                $xtea=new Think();
                $xtea_id_key=$xtea->encrypt($this->s_pix,"id");
                $xtea_id_val=$xtea->encrypt($id,$this->s_pix);

                // 判断是否保存了编号，如果保存了清除 id ,n long-term,否则删除原 cookie 前缀的cookie
                // 用户存储 cookie 时间设置 常量
               $long_term=cookie("Long-term");
               if($long_term==true)
               {
                   cookie('token',null);
                   cookie('n',null);
                   cookie($xtea_id_key,null);// 清除原 cookie
               }else
               {
                   cookie(null,$this->s_pix);
               }
                $time=time();
                $bool=$user->login_time($id,$time);
                if($bool!=1)
                {echo $bool;exit;}

                $role=D('Role');
                $limit_id=$role->limit_id($u_info["role_id"]);

                $limit=D('Limit');
                $bool=$limit->limit_all($limit_id,$u_info["role_id"]); //创建权限文件

                if(!isset($bool))
                {echo $bool;exit;}

                if(isset($_POST['Long-term']) && ($_POST['Long-term']==1))
                {
                    cookie($xtea_id_key,$xtea_id_val,USER_LOGIN_TS,"/");// 用户信息保存一个月
                    cookie("Long-term",true,USER_LOGIN_TS,'/');
                }else
                {
                    cookie($xtea_id_key,$xtea_id_val,USER_LOGIN_T,"/");
                }
                cookie("n",$u_name,USER_LOGIN_T,"/");

                //时时登录用户
                $token=sha1($time.$id);
                cookie('token',$token,USER_LOGIN_T,'/');

                echo "okok";
            }

      }else
        {
            $this->ajaxReturn($user->getError());
        }
    }

    /*
     * 验证验证码
     * */
    public  function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    /*
     * 生成验证码
     * */
    public  function verify()
    {
        $config =    array(
            'fontSize'    =>   18,    // 验证码字体大小
            'length'      =>   5,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'useCurve' =>false,
            'imageW'=>100,
            'imageH'=>43,
        );
        $Verify =new \Think\Verify($config);
        $code=$Verify->entry();
        echo $code;
    }

    public  function  login_out() //-----------------------退出登录
   {
       $long_term=cookie("Long-term");
       $xtea=new Think();  // 加密
       $xtea_id_key=$xtea->encrypt($this->s_pix,"id");
       if($long_term==true)
       {
           cookie('token',null);
           cookie('n',null);
           cookie($xtea_id_key,null);// 清除原 cookie
       }else
       {
           cookie(null,$this->s_pix);
       }
       S($this->u_id."tast_w_n",NULL);
       S($this->u_id."pro_w_n",NULL);
       $xtea=null;
       echo "<script>window.location.href='".__CONTROLLER__."/sign'</script>";
       exit;
   }


}