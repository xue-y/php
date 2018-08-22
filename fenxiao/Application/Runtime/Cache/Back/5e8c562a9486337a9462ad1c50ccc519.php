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
<!--    <script type="text/javascript" src="/Public/back/js/pintuer.js"></script>-->

</head>
<body>
<ul class="bread  clearfix">
    <li><a href="/Back/Index/index" target="right" class="icon-home"> 首页</a></li>
    <li><a href="/Back/File/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/File/index"><?php echo ($pos["a"]); ?></a></li>
</ul>

 <!--文件管理-->
<div class="panel admin-panel">
    <div class="body-content">
        <ul>
            <?php if(!empty($operate)): if(is_array($operate)): foreach($operate as $key=>$v): ?><li  class="button-big"><a href="javascript:;" onclick="clear_img('<?php echo ($v["execs"]); ?>','wximg')"><?php echo ($v["n"]); ?></a></li><?php endforeach; endif; endif; ?>
        </ul>
    </div>
</div>
<script>
    // 清理微信二维码图片
    //action  操作方法
    //dirimg 清理文件夹名称 ，相对于 public 文件夹
    function clear_img(action,dirimg)
    {
        $.post('/Back/File/'+action,{'file':dirimg},function(data){
            if(data!="ok")
            {
                alert(data);
            }else
            {
                alert("删除完成");
            }

        }).error(function(xhr,errorCode,errorInfo){
            console.log(errorCode);
            console.log(errorInfo);
        });
    }
</script>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>