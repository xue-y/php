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
    <li><a href="/Back/Personal/megShow"><?php echo ($pos["a"]); ?></a></li>
</ul>
<!--任务反馈详情页面-->
        <style>
            h4{padding-left: 40px; padding-top: 20px;}
            h5{padding-left: 60px; margin-bottom: 5px}
            .none{display: none}
        </style>
<div class="panel  admin-panel">

  <div class="body-content">
    <div  class="form-x">
      <div class="form-group">
        <div class="label">
          <label >问题标题：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" readonly="" value="<?php echo ($show["tit"]); ?>"  />
        </div>
      </div>
      <div class="form-group">
        <div class="label">
          <label>问题描述：</label>
        </div>
        <div class="field">
        <div class="border"><?php echo ($show["descr"]); ?></div>
        </div>
     </div>
     <div class="form-group">
        <div class="label">
            <label >问题提交时间：</label>
        </div>
        <div class="field">
            <input type="text" class="input w50" readonly="" value='<?php echo (date("Y-m-d H:i:s",$show["p_t"])); ?>'  />
        </div>
     </div>
        <hr/>
     <div class="form-group">
            <div class="label">
                <label >执行人姓名：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value="<?php echo ($show["u_name"]); ?>"  />
            </div>
     </div>
        <div class="form-group">
            <div class="label">
                <label >执行人部门：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value="<?php echo ($show["bumen"]); ?>"  />
            </div>
        </div>

        <div class="form-group">
            <div class="label">
                <label >执行时间：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" readonly="" value='<?php echo (date("Y-m-d H:i:s",$show["t_t"])); ?>'  />
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>解决方案：</label>
            </div>
            <div class="field">
                <div class="border"><?php echo ($show["plan"]); ?></div>
            </div>
        </div>
        <form method="post" action="/Back/Task/execUate">
            <input type="hidden" name="t_id" value="<?php echo ($show["id"]); ?>"><!--任务呢ID-->
            <input type="hidden" name="p_id" value="<?php echo ($show["p_id"]); ?>"><!--问题ID-->
        <div class="form-group">
            <div class="label">
                <label>是否解决：</label>
            </div>
            <div class="field">
                <div class="button-group radio">
                   <label class="button"><input name="t_state" value="0" type="radio">未解决问题</label>
                   <label class="button"><input name="t_state" value="1" type="radio">成功解决</label>
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
</div>
<script>
 $("form").submit(function(){
  var state=$(".radio").find('input:radio[name="t_state"]:checked').val() ;
     if(state==undefined)
     {
         alert("请选择是否解决");return false;
     }
 });
</script>
</body></html>
<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>