<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-20
 * Time: 下午1:33
 * 微信登录
 * 网页授权域名
   域名/Application
 * https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/function&action=function&token=782251143&lang=zh_CN&token=782251143&lang=zh_CN
 */

namespace Home\Controller;
use Think\Controller;
use Think\QRcode;
use Think\Curl;
use Think\Log;

class WxController extends Controller {

    private $appid;
    private $appsecret;
    private $re_url;
    private $state;
    private $scope;
    private $s_pix;//session 前缀
    private $fengefu;    // 自定义分割符

    public function  __construct()
    {
        header("Content-Type:text/html;charset=utf-8");
        parent:: __construct();
        visit_num();// 限制用户频繁刷新页面

        // 开发者ID(AppID)
        $this->appid="";
        //开发者密码(AppSecret)
        $this->appsecret="";
        // 回调地址 wx 端登录

        $this->re_url=urlencode("http://".$_SERVER['SERVER_NAME']."/Home/Wx/User");

        $this->scope="snsapi_userinfo"; //应用授权作用域
        //$scope="snsapi_base"; // 不弹出授权页面，直接跳转，只能获取用户openid

        $this->fengefu=FEN_FU;

       $this->s_pix=C('COOKIE_PREFIX'); // cookie 前缀
    }

     //获得 wx  code
     public function  index()
     {
         // 自定义标识
         if(isset($_GET["state"]) && !empty($_GET["state"]))
         {
             $this->state["state"]=add_slashes($_GET["state"]);
         }else
         {
             $this->state["state"]=WX_LOGIN;
         }

         if($this->state["state"]==WX_PASS)
         {
             $title="找回密码";
         }else if($this->state["state"] ==WX_VALIWX)
         {
             $title="验证微信";
         }else
         {
           //  sign_is_login(); // 如果是登录 判断是否已经登录
             $title="登录";
         }
         // 记录用从哪个页面跳转过来的
         if(isset($_GET["history"]) && !empty($_GET["history"]))
         {
             // 转换地址 Set_pass 转换为 Set/pass
             //$old_url=ucfirst($_GET["history"]);
             $old_url=str_replace($this->fengefu,"/",$_GET["history"]);

             // 如果用户是在微信端 识别二维码时记录session---从电脑端扫码无效
             if(isset($_SESSION[$this->s_pix]["id"]) && !empty($_SESSION[$this->s_pix]["id"]))
             {
                 $id=session("id");
                 cookie($id."history",$old_url);
             }
             // 如果存在历史记录页面在后面添加上
             // 如果用访问其他的页面 需要支付是判断用户是否登录，如果没有登录 或忘记密码 state=login_Te/index
             // 如果是 忘记原密码或 验证微信 state=valiwx_Set/pass
             $this->state["id"]=$id;
             $this->state["history"]=$old_url;
         }
         $this->state=json_encode($this->state);

         $code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$this->appid&redirect_uri=$this->re_url&response_type=code&scope=$this->scope&state=$this->state#wechat_redirect";

        $time_wx_qr_dir=substr(WX_QR,1); // 绝对路径创建失败

        $is_dir=dir_write($time_wx_qr_dir); // 判断文件是否存在并可写
         if(!isset($is_dir))
         {
             Log::write("二维码生成目录失败或没有写入权限 -- $time_wx_qr_dir","SELF_ERROR");
            exit("二维码刷新失败,请使用使用账户密码登录或联系客服");
         }

         $QR=WX_QR.time().'.png';  // 二维码图片名称
         $file=$_SERVER["DOCUMENT_ROOT"].$QR; // 图片根路径
         $errorLevel = "L"; //定义纠错级别
         $size = "4";  //定义生成内容
         QRcode::png($code_url, $file, $errorLevel, $size, 2);  // 执行生成图片
         $this->assign(array("img"=>$QR,"title"=>$title));
         $this->display();
       //  echo '<img src="'.$QR.'">';  //输出二维码

     }

    // 获取用户信息
     public function User()
     {

         if(!isset($_GET['code']) || !isset($_GET["state"]))
         {
             exit("wx user login error");
         }
         $code=$_GET['code'];

         $token_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->appsecret&code=$code&grant_type=authorization_code";

         try{
             $curl=new Curl();
             $token_resturn=$curl->https_get($token_url);
             $token_data=json_decode($token_resturn,true);
         }catch (\Exception $e){
             // dump($e->getMessage());
             exit("暂时不可访问");
             // 如果请求失败记得开启443 端口
         }
         //如果请求 access_token 失败
         if(isset($token_data["errcode"]))
         {
             exit($token_data["errmsg"]);
         }
         $openid=$token_data["openid"];
         $web_access_token=$token_data["access_token"];
         $refresh_token=$token_data["refresh_token"];

        // 验证access_token 是否有效
         $refresh_url="https://api.weixin.qq.com/sns/auth?access_token=$web_access_token&openid=$openid";
         $refresh_info=$curl->https_get($refresh_url);
         $refresh_info=json_decode($refresh_info,true);
        // 如果access_token 过期
         if($refresh_info["errcode"]!==0)
         {
             //  刷新 access_token
             $refresh_access_token="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$appid&grant_type=refresh_token&refresh_token=$refresh_token";
             $refresh_resturn=$curl->https_get($refresh_access_token);
             $refresh_data=json_decode($refresh_resturn,true);
             // 如果刷新获取失败
             if(isset($refresh_data["errcode"]))
             {
                 exit($refresh_data["errmsg"]);
             }
             // 如果成功再次赋值
             $web_access_token=$refresh_data["access_token"];
             $refresh_token=$refresh_data["refresh_token"];
         }

        // 拉取用户信息
         $user_url="https://api.weixin.qq.com/sns/userinfo?access_token=$web_access_token&openid=$openid&lang=zh_CN";
         $user_info=$curl->https_get($user_url);
         $user_info=json_decode($user_info,true);
        // 如果获取用户信息失败
         if(isset($user_info["errcode"]))
         {
             exit($user_info["errmsg"]);
         }
         $openid=$user_info["openid"];
         $new_user_info=array();  // 取得用户信息数组

         // 判断用户是登录 还是 忘记原密码 还是验证微信
         // login  pass valiwx
         $this->state=json_decode($_GET["state"],true);
         $this->state=add_slashes($this->state);

         $cus=D("Customer");
        // 如果 用户验证微信
         if($this->state["state"]==WX_VALIWX)  // 验证微信-- 绑定微信---允许一个微信号绑定多个账号
         {
             $new_user_info["openid"]=$openid;
             $new_user_info["is_wx"]=1;

             if(isset($this->state["id"])  && !empty($this->state["id"]))
             {
                 // 根据用户 id 取得用户相关信息
                 $user_info2=$cus->id_is($this->state["id"]);
             }
         }else            // 如果用户使用微信登录 或忘记密码 或者忘记原密码
         {
             $user_info2=$cus->openid_is($openid);
             if(empty($user_info2))
             {
                 Log::write($openid,"SELF_ERROR");
                 echo "<p style='text-align: center;margin-top:20px;font-size: 30px;'>
                 用户不存在或未绑定微信，请联系咨询师
                 <a href='/Home/Login/sign'>点击账号密码登录</a>
                 </p>";
                 exit;
             }
         }

         if(empty($user_info2["n"]))
         {
             $new_user_info["n"]=$user_info["nickname"];
         }

         if(empty($user_info2["headimg"]) && !empty($user_info["headimgurl"]))
         {
             // 下载远程图片
              // $headimg=$openid."jpg";
               $is_down=$curl->get_img($user_info["headimgurl"],$openid);
               if($is_down["code"]===0)
               {
                   $new_user_info["headimg"]=$openid;
               }else
               {
                   Log::write($openid."头像写入失败","SELF_ERROR");
               }
         }

        // 如果获取到用户信息，写入数据库
         if(!empty($new_user_info))
         {
             if($this->state["state"]==WX_VALIWX) // 验证微信
             {
                 $w["id"]=array("eq",$this->state["id"]); // 取得用户id
                 $is_save=$cus->where($w)->save($new_user_info);
                 if($is_save!=1)
                 {
                     $this->error("验证微信失败，请刷新页面或稍后再试","index");
                     exit;
                 }
             }
             else       // 登录 忘记原密码
             {
                 $w["id"]=array("eq",$user_info2["id"]);
                 $is_save=$cus->where($w)->save($new_user_info);
                 if($is_save!=1)
                 {
                     Log::write($user_info2["id"]."用户使用微信登录更新信息失败","SELF_ERROR");
                 }
             }
         }

         if(!isset($_SESSION))
         {session_start();}

         // 如果用户是从扫码登录进来后，后期验证微信或找回原密码--- 不需要从新获取session
         // 如果用户浏览器登录 图片识别二维码跳转过来的, 忘记原密码  与验证微信 都重新设置了session
         if(!isset($_SESSION[$this->s_pix]['id']) || empty($_SESSION[$this->s_pix]['id']))
         {
             session('login_status'.$user_info2["id"],1); // 登录状态
             session('id',$user_info2["id"],USER_LOGIN_T,'/');
         }

         $token=pass_md5(sha1($user_info2["id"]).$user_info2["phone"]);
         cookie('token',$token,USER_LOGIN_T,'/');
         cookie('phone',$user_info2["phone"],USER_LOGIN_T,'/');  // 记住手机号仅对下次有效

         if($this->state["state"]==WX_PASS)  // 忘记原密码 --- 跳转到 重置密码页面
         {
             //$state[1]   是用户id
             header("Location:http://".$_SERVER['SERVER_NAME'].__MODULE__."/Wx/pass");
             exit;
         }else if($this->state["state"]==WX_VALIWX)  // 验证微信-- 绑定微信
         {
            // 取得历史记录页面
             if(isset($this->state["history"]) && !empty($this->state["history"]))
             {
                 $history=$this->state["history"];
             }else
             {
                 $history="/Index/index";
             }
             $this->success("微信验证成功",'../'.$history);

         }else
         {
             $history="/Index/index";
             //---------------------- 执行登录
             header("Location:http://".$_SERVER['SERVER_NAME'].__MODULE__.$history);
             exit;
         }
     }

    /*微信找回密码*/
    public function pass()
    {
        $this->display();
    }

    //  执行重置密码
    public function execPass()
    {
        $post=add_slashes($_POST);
        $cus=D("Customer");
        if (!$cus->create()){  // 不是本表字段只能验证，不能收集数据
           // 如果创建失败 表示验证没有通过 输出错误提示信息
           // $this->error($cus->getError());
            $error=$cus->getError();
        }
        if(isset($error) && !empty($error))
        {
             $this->error($error);
        }

        $pass["pass"]=pass_md5($post["pass"]);

        $cus_user=D("Cus_base");
        // 先查询是否与数据一致 如果一致提示修改成功，如果不一致写入数据
        $user_id=cookie("id");
        // 判断历史记录页面是否存在
        $history=$this->history($user_id);

        $old_pass=$cus_user->getFieldById($user_id,"pass");
        if($old_pass==$pass["pass"])
        {
           $this->success("重置密码成功",'../'.$history);
           exit;
        }
        $w["id"]=array("eq",$_COOKIE[$this->s_pix.'id']);
        $is_save=$cus_user->where($w)->save($pass);

        if($is_save==1)
        {
            $this->success("重置密码成功",'../'.$history);
        }else
        {
            $this->error("重置密码失败");
        }
    }

    // 历史记录页面
    private function history($id)
    {
        if(isset($_COOKIE[$this->s_pix.$id."history"]) && !empty($_COOKIE[$this->s_pix.$id."history"]))
        {
            $history=$_COOKIE[$this->s_pix.$id."history"];
        }else
        {
            $history="Login/sign";
        }
        return $history;
    }

 }