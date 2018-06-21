<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>我的消息</title>
</head>
<body class="info-index">
<h3>
    <a onclick="javascript :history.back(-1)" >←</a> | 我的消息
    <a href="/wx/Home/Index/index" class="float-right">主页</a>
</h3>
<ul class="list clear">
    <?php if(isset($status)): ?><li><a href="/wx/Home/Info/index">全部消息</a></li>
        <?php if($status == 2 ): ?><li>已读消息</li>
            <li><a href="/wx/Home/Info/index?status=1">未读消息</a></li>
            <?php else: ?>
            <li><a href="/wx/Home/Info/index?status=2">已读消息</a></li>
            <li>未读消息</li><?php endif; ?>
        <?php else: ?>
        <li>全部消息</li>
        <li><a href="/wx/Home/Info/index?status=2">已读消息</a></li>
        <li><a href="/wx/Home/Info/index?status=1">未读消息</a></li><?php endif; ?>
</ul>

    <?php if(empty($list)): ?><table class="table table-hover text-center">
           <tr><td> 暂无消息</td></tr>
        </table>
        <?php else: ?>
        <table class="table table-hover text-center">
        <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
            <?php if(($v["is_read"]) == "2"): ?><td><a style="color:#666" href="/wx/Home/Info/infoRead?id=<?php echo ($v["id"]); ?>"><?php echo ($v["easy"]); ?></a></td>
            <?php else: ?>
                <td><a style="color:#0099FF" href="/wx/Home/Info/infoRead?id=<?php echo ($v["id"]); ?>"><?php echo ($v["easy"]); ?></a></td><?php endif; ?>
            </tr><?php endforeach; endif; ?>
        </table>
        <ul class="pagelist">    <?php echo ($page); ?> <span>共 <?php echo ($count); ?> 条消息</span></ul><?php endif; ?>

<link type="text/css" rel="stylesheet" href="/wx/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/wx/Public/home/home.css" >
<script  type="text/javascript" src="/wx/Public/back/js/jquery.js"></script>
<script  type="text/javascript" src="/wx/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/wx/Public/back/js/pintuer.js"></script>
</body>
</html>