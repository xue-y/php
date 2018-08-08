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
    <li><a href="/Back/Task/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a href="/Back/Task/update"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--任务添加页面-->
        <style>
            h4{padding-left: 40px; padding-top: 20px;}
            h5{padding-left: 60px; margin-bottom: 5px}
            .none{display: none}
        </style>
<link rel="stylesheet" href="/Public/kindeditor-4.1.10/themes/default/default.css" />
<script charset="utf-8" src="/Public/kindeditor-4.1.10/kindeditor-min.js"></script>
<script charset="utf-8" src="/Public/kindeditor-4.1.10/lang/zh_CN.js"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="descr"]', {
            allowFileManager : true
        });
    });
</script>
<div class="panel  admin-panel">
  <div class="body-content">
      <p class="red">标题、描述 为空为不修改</p>
    <form method="post" class="form-x" action="/Back/Task/execUate">
        <input type="hidden" value="<?php echo ($pro["id"]); ?>" name="id">
      <div class="form-group">
        <div class="label">
          <label >问题标题：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="tit" value="<?php echo ($pro["tit"]); ?>" data-validate="title:标题长度2--20个字符" />
          <div class="tips"></div>
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>问题描述：</label>
        </div>
        <div class="field">
        <textarea type="text" class="input" name="descr"  data-validate="descipt:角色描述长度5--100个字符"><?php echo ($pro["descr"]); ?></textarea>
        </div>
     </div>
      <div class="form-group">
            <div class="label">
                <label>任务状态：</label>
            </div>
            <div class="field">
                <div class="button-group radio">

                <?php foreach($state as $k=>$v) { if($pro["state"]==$v) { echo '<label class="button  active"><input name="state" value="'.$v.'" type="radio" checked="checked" >'.$k.'</label>'; } else { echo '<label class="button"><input name="state" value="'.$v.'" type="radio" >'.$k.'</label>'; } } ?>

                </div>
            </div>
        </div>
     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main" type="submit">保存提交</button>
             &nbsp;  &nbsp;  &nbsp;
         <button class="button bg-main" type="button" onclick="javascript :history.back(-1);">直接返回</button>
        </div>
      </div>
    </form>
  </div>
</div>

</body></html>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>