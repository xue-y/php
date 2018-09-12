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
    <li><a href="/Back/Money/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--修改客户佣金页面-->
<div class="panel  admin-panel">
  <div class="body-content">
   
    <form method="post" class="form-x" action="/Back/Money/execUate">

        
        <div class="form-group"><!--佣金修改-->
            <div class="label">
                <label>修改佣金：</label>
            </div>
            <div class="field">
                <input type="text" class="input w50" name="money"  value=""  data-validate="currency:请输入+\-数字" />
                <?php if(($info["money"]) >= "1"): ?>&nbsp; [ <?php echo ($info["money"]); ?> ] <a href="moneyList?id=<?php echo ($info["id"]); ?>" style="margin-top: 10px; display: inline-block">查询佣金记录</a><?php endif; ?> 
                <div class="tips"></div>
                 <p style="color:#666666" class="clear">  &nbsp;  减加佣金直接写要加减的数字即可 减 -3,加 3</p>
            </div>
        </div>
        <div class="form-group">
            <div class="label">
                <label>佣金备注：</label>
            </div>
            <div class="field">
                <textarea name="money_info"></textarea>&nbsp;
                <span class="red" style="margin-top: 10px; display:block">如果修改佣金金额，请填写佣金操作说明，字符不得超过80个</span>
            </div>
        </div>

     <input type="hidden" value="<?php echo ($info["cid"]); ?>" name="cid">
     <input type="hidden" value="<?php echo ($info["id"]); ?>" name="id">
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


<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>