<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理中心</title>  
    <link rel="stylesheet" href="/Public/back/css/pintuer.css">
    <link rel="stylesheet" href="/Public/back/css/admin.css">
    <script src="/Public/back/js/jquery.js"></script>
</head>
<body>
<div class="header bg-main">
  <div class="logo margin-big-left fadein-top">
    <h1><img src="/Public/back/images/y.jpg" class="radius-circle" height="50" /><?php echo (WBE_NAME); ?></h1>
  </div>
  <div class="head-l">
  <a class="button button-little bg-green" href="/Back/Index/index" target="right" ><span class="icon-home"></span>&nbsp;管理首页</a>
  <a href="/Back/Personal/index" class="button button-little bg-blue" target="right"><span class="icon-user"></span> &nbsp;<?php echo ($n); ?> </a>
  <a class="button button-little bg-red" href="/Back/Login/login_out"><span class="icon-power-off"></span> 退出登录</a> </div>
</div>
<div class="leftnav">
  <div class="leftnav-title"><strong><span class="icon-list"></span>菜单列表</strong></div>
  <p class="on"><a class="icon-home" href="/Back/Index/index" target="right">  &nbsp;后台首页</a></p>
    <span style="display: none"><?php echo ($i=0); ?></span>
    <?php if(is_array($limit_menu)): foreach($limit_menu as $k=>$v): if(empty($v["execs"])): ?></ul><h2><span class="icon-<?php echo ($ico[$i++]); ?>"></span><?php echo ($v["n"]); ?></h2><ul>
           <?php else: ?>
           <li><a href="/Back/<?php echo ($v["execs"]); ?>/index" target="right"><?php echo ($v["n"]); ?></a></li><?php endif; endforeach; endif; ?>
</div>
<script type="text/javascript">
$(function(){
  $(".leftnav h2").click(function(){
	  $(this).next().slideToggle(200);	
	  $(this).toggleClass("on"); 
  })
  $(".leftnav ul li a").click(function(){
	    $("#a_leader_txt").text($(this).text());
  		$(".leftnav ul li a").removeClass("on");
		$(this).addClass("on");
  })
});
</script>

<div class="admin">
    <iframe rameborder="0" src="/Back/Index/index" name="right" width="100%" height="100%" scrolling="auto"></iframe>
</div>
<script>
    function url_pos() //验证邮箱后 跳转到 个人信息页面
    {
        var url=window.location.hash;
        url=url.substr(1);
        //  console.log(url);
        if(url!='')
            $("iframe").attr("src",'/Back/'+url+'/index');
    }
    url_pos();//验证邮箱后 跳转到 个人信息页面

    function showNotice(title,msg){
        var Notification = window.Notification || window.mozNotification || window.webkitNotification;
        if(Notification){
            Notification.requestPermission(function(status){
                //status默认值'default'等同于拒绝 'denied' 意味着用户不想要通知 'granted' 意味着用户同意启用通知
                if("granted" != status){
                    return;
                }else{
                    var tag = "sds"+Math.random();
                    var notify = new Notification(
                            title,
                            {
                                dir:'auto',
                                lang:'zh-CN',
                                tag:tag,//实例化的notification的id
                                //  icon:'http://www.yinshuajun.com/static/img/favicon.ico',//通知的缩略图,//icon 支持ico、png、jpg、jpeg格式
                                body:msg //通知的具体内容
                            }
                    );
                    notify.onclick=function(){
                        //如果通知消息被点击,通知窗口将被激活
                        window.focus();
                    },
                    notify.onerror = function () {
                        console.log("HTML5桌面消息出错！！！");
                    };
                    notify.onshow = function () {
                            setTimeout(function(){
                                notify.close();
                            },50000)
                    };
                    notify.onclose = function () {
                        console.log("HTML5桌面消息关闭！！！");
                    };
                }
            });
        }else{
            console.log("您的浏览器不支持桌面消息");
        }
    }; // 桌面提醒

</script>
<?php if(isset($p_c) && $p_c>0 && isset($meg) && $meg>0) { echo '<script>showNotice("请去您的[个人信息]页面查看","现在有 '.$meg.' 条反馈信息\r\n现在有 '.$p_c.' 条新任务有待执行")</script>'; }else if(isset($meg) && $meg>0) { echo '<script>showNotice("请去您的[个人信息]页面查看","现在有 '.$meg.' 条反馈信息")</script>'; }else if(isset($p_c) && $p_c>0) { echo '<script>showNotice("请去您的[个人信息]页面查看","现在有 '.$p_c.' 条新任务有待执行")</script>'; } ?>
</body></html>