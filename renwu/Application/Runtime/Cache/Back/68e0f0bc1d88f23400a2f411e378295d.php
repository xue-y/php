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
    <li><a href="/Back/Personal/megList"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--用户任务反馈列表页面-->
<link rel="stylesheet" type="text/css" href="/Public/back/css/time.css">
<script src="/Public/back/js/time.js"></script>
<style>
    .submit input{background: none; border:none;color:#f00;}
    .submit:hover input{color:#fff;}
    .font-size16{font-size: 16px; margin-top: 20px;margin-left:5px;}
    .font-size16 a{margin: 0px 10px;}
    td{overflow: hidden;}
</style>
<div class="panel">
  <div class="padding">

  </div> 
  <table class="table table-hover text-center">
    <tr>
      <th>问题标题</th>
      <th  width="450">解决方案</th>
      <th>任务执行时间</th>
      <th>详情</th>
    </tr>
   <tbody>

     <?php if($count > 0 ): if(is_array($list)): foreach($list as $key=>$v): ?><tr>
       <td><?php echo (mb_substr($v["tit"],0,10,'utf-8')); ?></td>
       <td><?php echo ($v["plan"]); ?></td>
       <td><?php echo (date("Y-m-d H:i:s",$v["times"])); ?></td>
       <td>
       <a href="/Back/Personal/megShow?id=<?php echo ($v["id"]); ?>">查看</a><!--传的是任务ID-->
      </td>
     </tr><?php endforeach; endif; ?>

    <?php else: ?>
       <tr class="font-size16"><td colspan="8">暂无反馈信息</td></tr><?php endif; ?>

   </tbody>
  </table>

    <ul class="pagelist">  <span>共 <?php echo ($count); ?> 个任务</span></ul>
</div>



<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>