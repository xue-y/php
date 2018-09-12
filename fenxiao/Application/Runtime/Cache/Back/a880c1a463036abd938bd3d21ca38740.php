<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>首页-<?php echo ($pos["c"]); ?></title>
    <link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
    <link type="text/css" rel="stylesheet" href="/Public/back/css/admin.css">
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>
    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/Back/Personal/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--管理员个人信息页面-->
<div class="panel admin-panel">
  <div class="body-content">
    <div class="form-x">
      <div class="form-group">
          <label  class="label">用户编号：</label>
          <p class="input w50"><?php echo ($info["id"]); ?></p>
      </div>
        <div class="form-group">
                <label  class="label">用户名：</label>
                <p class="input w50"><?php echo ($info["u_name"]); ?></p>
        </div>

        <div class="form-group">
            <label  class="label">用户邮箱：</label>
            <p>
                <?php if(($info["mail"]) == ""): ?><a href="javascript:;" onclick="email('<?php echo ($info["id"]); ?>')"  class="input w50">请绑定邮箱，有于忘记密码时找回密码</a>
                    <?php else: ?>
                    <span  class="input w50"><?php echo ($info["mail"]); ?>

                        <?php if(($info["is_jihuo"]) == "1"): ?><a href="javascript:;" onclick="javascript:$('.no-skin').fadeIn();" style="margin-left: 20px">解除绑定</a>
                            <?php else: ?>
                            <a href="javascript:;" onclick="javascript:$('.no-skin').fadeIn();"  style="margin-left: 20px">验证邮箱</a><?php endif; ?>

                    </span><?php endif; ?> &nbsp;
            </p>&nbsp;
        </div>

        <div class="form-group">
                <label class="label">所在部门：</label>
                <p class="input w50"><?php echo ($info["bumen"]); ?></p>
        </div>
        <div class="form-group">
             <label class="label">用户角色：</label>
             <p><a class="input w50" href="/Back/Limit/index?id=<?php echo ($info["role_id"]); ?>" title="查看权限"><?php echo ($info["role_n"]); ?></a></p>
        </div>
    <!--  附加表信息  -->
    <?php if(!empty($u_fj)): ?>&nbsp;
        <div class="form-group">
            <div class="label">
                <label>手机号：</label>
            </div>
            <div class="field">
                <p  class="input w50"  style="height: 38px;"><?php echo ($u_fj["phone"]); ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label>微信号：</label>
            </div>
            <div class="field">
                <p class="input w50" style="height: 38px;"><?php echo ($u_fj["wx"]); ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label>消息个数：</label>
            </div>
            <div class="field">
                <p class="input w50"><a href="/Back/Line/index"> &nbsp; <?php echo ($u_fj["meg"]); ?> &nbsp; </a></p>
                <div class="tips"></div>
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label>个人说明：</label>
            </div>
            <div class="field">
                <textarea><?php echo (str_slashes($u_fj["info"])); ?></textarea>
                <span>字符不得超过80个</span>
            </div>
        </div><?php endif; ?>

    </div>
  </div>
</div>
<div class="hidden mail_send_c"><?php echo ($mail_send_c); ?></div>
<div class="hidden mail_deny_t"><?php echo ($mail_deny_t); ?></div>
<div class="no-skin">
        <div class="error-container">
            <h1>验证邮箱</h1><span class="close" onclick="javascript:$('.no-skin').fadeOut();"></span>
            <div class="errorcon">
                <p>
                    <input type="text" value="<?php echo ($info["mail"]); ?>" class="input" readonly="" >
                    <button class="button bg-main" type="button"   onclick="email_send($(this),'<?php echo ($info["id"]); ?>','<?php echo ($info["mail"]); ?>','<?php echo ($info["mail_state"]); ?>')">发送验证</button>
                </p>
                    <i class="clear">验证码发送到邮箱后台，请一个小时内完成验证</i>
            </div>

            <h4  class="email_ver_info">窗口 <span></span> 秒后自动关闭</h4>
        </div>
    </div>

    <style>
        .no-skin,.no-skin2{display: none;}
        .close{ position: absolute;color:#fff;width:28px; height:28px;top:8px;right:10px;}
        .error-container{ position:fixed;bottom: 40%;left:50%;margin-left:-220px; background:#fff; border:1px solid #0ae;  text-align:center; width:450px;  font-family:Microsoft Yahei; padding-bottom:30px; border-top-left-radius:5px; border-top-right-radius:5px;  }
        .error-container h1{ font-size:16px; padding:12px 0; background:#0ae; color:#fff;}
        .errorcon{ padding:35px 0; text-align:center; font-size:16px;width: 90%; margin: 0 auto;}
        .errorcon input{display: inline-block;}
        .errorcon i{ margin:12px auto; font-size:14px;}
        h4 span{color:red;}
        .email_ver_info{visibility: hidden }
        h4{ font-size:14px; color:#666;}
       .input a,a.input{color:#0F85D4;}
    </style>
    <script>
                function email(id)  //写入邮箱字段
                {
                    var mail = prompt("请输入您的邮箱", ""); //将输入的内容赋给变量 name ，
                    //这里需要注意的是，prompt有两个参数，前面是提示的话，后面是当对话框出来后，在对话框里的默认值
                    if (mail)//如果返回的有内容
                    {
                      //  alert("邮件稍后发送您的邮箱，请验证")
                        $.post("/Back/Personal/email",{"id":id,"mail":mail},function(data){

                            if(data=="ok")
                            {
                                alert("您已绑定邮箱，请激活验证邮箱");
                                location.reload();
                            }
                            ver_eid(data); //验证 用户ID 邮箱返回信息
                        })
                    }
                }

                function email_send(t,id,mail,state)//执行邮箱验证--发送验证
                {
                    if(send_t)
                    {
                        clearInterval(send_t);
                        t.html("发送验证");
                        $('.email_ver_info').find("span").text('');
                    }
                    // 发送邮件 id 用户ID email 用户邮箱 state 绑定1、解除0
                    $.post("/Back/Personal/emailSend",{'id':id,'mail':mail,'state':state},function(data){
                      //  console.log(data)  // 取得发送邮件后是否成功
                        ver_eid(data); //验证 用户ID 邮箱返回信息
                        console.log(data);
                        if($(".no-skin").is(":hidden")) // 邮箱绑定时
                        {
                            $('.no-skin').fadeIn();
                        }
                        if(data=="delay_time")
                        {
                            var s=$(".mail_deny_t").text();
                            $('.no-skin').find("h1").html("请稍后发送");
                            $('.no-skin').find("p").html("发送邮件间隔时间 "+s+"秒");
                        }
                        if(data=="send_mail_c")
                        {
                            var mail_send_c=$(".mail_send_c").text();
                            $('.no-skin').find("h1").html("今天邮件发送超过 "+mail_send_c+" 次");
                            $('.no-skin').find("p").html("请明天在验证");
                        }
                        if(data=="mail_send_error")
                        {
                            $('.no-skin').find("h1").html("邮件发送失败");
                            var cf='<a onclick="email_send(t,id,mail,state")>重新发送</a>';
                            $('.no-skin').find("p").html(cf);

                        }
                        if(data=="mail_send_ok")
                        {
                            var mail_url = gotoEmail(mail);
                            mail_url='<a target="_blank" href="http://'+mail_url+'">去我的邮箱</a>';
                            $('.no-skin').find("h1").html("邮件发送成功");
                            $('.no-skin').find("p").html(mail_url);
                        }

                    });//发送邮件
                    $('.email_ver_info').css("visibility","visible");
                    $('.email_ver_info').find("span");

                    var s=$(".mail_deny_t").text();
                    var send_t=setInterval(function(){
                        t.html(s+"秒后重新发送");
                        $('.email_ver_info').find("span").text(s)
                        s--;
                        if(s<0)
                        {clearInterval(send_t);
                            $(".no-skin").fadeOut();
                            t.html("发送验证");
                        }
                    },1000);

                }

                function ver_eid(data)//验证 用户ID 邮箱返回信息
                {
                    if(data=="mail_error")
                    {
                        alert("邮箱错误，请检查邮箱格式或更换邮箱");
                    }
                    if(data=="id_error")
                    {
                        alert("用户信息错误");
                    }
                }

                //功能：根据用户输入的Email跳转到相应的电子邮箱首页---这个js 函数只在当前页面起作用
                function gotoEmail(mail) {
                    $t = mail.split('@')[1];
                    $t = $t.toLowerCase();
                    if ($t == '163.com') {
                        return 'mail.163.com';
                    } else if ($t == 'vip.163.com') {
                        return 'vip.163.com';
                    } else if ($t == '126.com') {
                        return 'mail.126.com';
                    } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
                        return 'mail.qq.com';
                    } else if ($t == 'gmail.com') {
                        return 'mail.google.com';
                    } else if ($t == 'sohu.com') {
                        return 'mail.sohu.com';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'vip.sina.com') {
                        return 'vip.sina.com';
                    } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
                        return 'mail.sina.com.cn';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
                        return 'mail.cn.yahoo.com';
                    } else if ($t == 'tom.com') {
                        return 'mail.tom.com';
                    } else if ($t == 'yeah.net') {
                        return 'www.yeah.net';
                    } else if ($t == '21cn.com') {
                        return 'mail.21cn.com';
                    } else if ($t == 'hotmail.com') {
                        return 'www.hotmail.com';
                    } else if ($t == 'sogou.com') {
                        return 'mail.sogou.com';
                    } else if ($t == '188.com') {
                        return 'www.188.com';
                    } else if ($t == '139.com') {
                        return 'mail.10086.cn';
                    } else if ($t == '189.cn') {
                        return 'webmail15.189.cn/webmail';
                    } else if ($t == 'wo.com.cn') {
                        return 'mail.wo.com.cn/smsmail';
                    } else if ($t == '139.com') {
                        return 'mail.10086.cn';
                    } else {
                        return '';
                    }
                };
    </script>


<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>