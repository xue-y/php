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
    <li><a href="/Back/Role/index" id="a_leader_txt"><?php echo ($pos["c"]); ?></a></li>
    <li><a><?php echo ($pos["a"]); ?></a></li>
</ul>

<!--角色列表页面-->
        <style>
            .submit input{background: none; border:none;color:#f00;}
            .submit:hover input{color:#fff;}
            .table{font-size:14px;}
        </style>
<div class="panel">
  <form method="post" action="/Back/Role/del">
      <div class="padding">
          <?php if(isset($is_admin)): ?><a class="button border-blue" href="/Back/Role/add"><span class="icon-plus-square-o"></span><?php echo ($action_name["add"]); ?>角色</a>
          <a class="button border-red submit" href="javascript:;" ><span class="icon-trash-o"></span><input type="submit"  value="批量<?php echo ($action_name["del"]); ?>" ></a><?php endif; ?>
      </div>
      <table class="table table-hover text-center">
        <tr><?php if(isset($is_admin)): ?><th  width="15%" style="text-align:left; padding-left:20px;"><input type="checkbox" id="checkall"/>全选</th><?php endif; ?>
          <th width="15%">角色ID</th>
          <th>角色名称</th>
          <th>角色描述</th>
          <th>权限</th>
          <th>操作</th>
        </tr>
       <tbody>
         <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
           <?php if(isset($is_admin)): ?><td>
               <?php if(($r_id) != $v["id"]): ?><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" /><?php endif; ?>   <!--不显示当前超级管理员用户的角色 删除选框-->
           </td><?php endif; ?>
           <td><?php echo ($v["id"]); ?></td>
           <td><?php echo ($v["n"]); ?></td>
          <td><?php echo (mb_substr($v["descr"],0,20,'utf-8')); ?></td>
          <td><a href="/Back/Limit/index?id=<?php echo ($v["id"]); ?>">查看权限</a> </td>
          <td>
          <div class="button-group">
              <?php if($is_admin == 1): ?><!--如果登录用户是超级管理员-->
                  <a class="icon-edit" href="/Back/Role/update?id=<?php echo ($v["id"]); ?>"> <?php echo ($action_name["update"]); ?></a><!--自己所属的角色-->
                  <?php if(($r_id) != $v["id"]): ?><a class="icon-trash-o" href="/Back/Role/del?id=<?php echo ($v["id"]); ?>" onclick="return confirm('您确定要删除吗?')"> <?php echo ($action_name["del"]); ?></a><?php endif; ?><!--其他用户-->
                  <?php else: ?>
                  <?php if(($r_id) == $v["id"]): ?><a class="icon-edit" href="/Back/Role/update?id=<?php echo ($v["id"]); ?>"> <?php echo ($action_name["update"]); ?></a><!--自己所属的角色--><?php endif; endif; ?>
          </div>
          </td>
        </tr><?php endforeach; endif; ?>
      </tbody>
      </table>
 </form>
</div>

<script type="text/javascript" src="/Public/back/js/arc_list.js"></script>
</body></html>