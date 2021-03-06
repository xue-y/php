<?php
namespace Back\Controller;
use My\MyController;
use Think\Controller;

class LoginController extends MyController{  //登录

    /*
     * 进入登录页面
     * */
    public  function sign()
    {
      //  cookie(null,"my_");
		// 第一次安装完成后跳转页面
		if(isset($_GET["state"]) &&  $_GET["state"]=="Lock")
		{
			$old_name = MODULE_PATH.'Controller/LockController.class.php';
			$new_name = MODULE_PATH.'Controller/Lock_Controller.class.php';
			if(file_exists($new_name)!=TRUE)
			{
				$bool=rename($old_name,$new_name);
				if(!file_exists($new_name) ||  file_exists($old_name) || !isset($bool))
				{
					$info="锁定类文件删除失败: 安装完成后请将 ".$old_name." 修改为 ".$new_name;
					$this->write_log($info);
					exit('锁定类文件删除失败,请查看日志');
				}
			}
		}

       // 用户上次是否记住用户名和编号
        $id=cookie("id");
        $id=isset($id)?$id:'';
        $this->assign(
            array('id'=>$id)
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

        $id=add_slashes($_POST['id']);

        if(empty($id))
        { echo "不存在此用户";exit;}

        try{
            $user=D('User');
        }catch(\Exception $e){die($e->getMessage());}

       if($user->create())
       {
            $u_info=$user->find($id);

            if(!isset($u_info) || empty($u_info))
            {
                echo "不存在此用户";exit;
            }

            if($u_info['id']!=$id)
            {
                echo "用户编号错误";exit;
            }

            $pass=pass_md5(add_slashes($_POST['u_pass']));

            if($u_info['u_pass']!=$pass)
            {
                echo "密码错误";exit;
            }
            else
            {
                $time=time();
                $bool=$user->login_time($id,$time);
                if($bool!=1)
                {echo $bool;exit;} // 写入cookie  的token

                $role=D('Role');
                $limit_id=$role->limit_id($u_info["role_id"]);

                $limit=D('Limit');
                $bool=$limit->limit_all($limit_id,$u_info["role_id"]); //创建权限文件

                if(!isset($bool))
                {echo $bool;exit;} //
                $u_name=$u_info["u_name"];
                if(isset($_POST['Long-term']) && ($_POST['Long-term']==1))// 用户信息保存一个月
                {
                    cookie('Long-term',true,3600*24*30,'/');
                 //  cookie('n',$u_name,3600*24*30,'/');
                    cookie('id',$id,3600*24*30,'/');
                }
                session("id",$id);
                cookie('n',$u_name,USER_LOGIN_T,"/");
                //时时登录用户
                $token=sha1($time.$id);
                cookie('token',$token,USER_LOGIN_T,'/'); // 当前用户保存10个小时
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
       $long_term=cookie($this->s_pix.'Long-term');
       if(!isset($long_term) || $long_term!=true)
       {
           cookie('token',$token,time()-1,'/');
       }else
       {
           cookie(null,$this->s_pix,time()-1,'/');
       }
       session("id",null);
        // 清空指定前缀的所有cookie值
       echo "<script>window.location.href='".__CONTROLLER__."/sign'</script>";
       // echo "<script>window.location.href='/__CONTROLLER__/sign'</script>"; 跳转失败解析不了变量
   }

}