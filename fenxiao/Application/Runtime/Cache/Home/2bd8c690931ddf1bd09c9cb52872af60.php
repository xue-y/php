<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>


    <title>我的推荐列表</title>
</head>
<body class="line-index">
<h3>
    <a onclick="javascript :history.back(-1)" >←</a> | 我的推荐 <a class="float-right" href="/Home/Index/index">主页</a>
</h3>
<?php if(empty($list)): ?><p class="text-center padding"  style="border-bottom: 1px solid #cbcbcb;">暂无推荐<a href="/Home/Line/add"> 现在去推荐 </a></p>
    <?php else: ?>
<form action="/Home/Line/del" method="post">
    <table class="table table-hover text-center">
       <tr>
           <th><input type="checkbox" id="checkall"/> 全选</th>
           <th><a href="/Home/Line/add">添加推荐</a></th>
           <th><input type="button"  value="删除推荐" class="submit" onclick="DelSelect($(this))"></th>
       </tr>
        <tbody>
        <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
                <td><input type="checkbox" name="id[]" value="<?php echo ($v["id"]); ?>" /></td>
                <td><?php echo ($v["n"]); ?> &nbsp;  &nbsp; &nbsp;
                    <?php if(($v["state"]) == "1"): ?>未审核<?php endif; ?>
                    <?php if(($v["state"]) == "2"): ?><a style="color:#0099FF">审核通过</a><?php endif; ?>
                    <?php if(($v["state"]) == "3"): ?><a style="color:#ee3333">未通过审核</a><?php endif; ?>
                </td>
                <td>
                    <div class="button-group">
                        <a class="icon-edit" href="/Home/Line/update?id=<?php echo ($v["id"]); ?>" ></a>

                        <?php if(($v["state"]) != "2"): ?><a class="icon-trash-o" href="/Home/Line/del?id=<?php echo ($v["id"]); ?>" onclick="return confirm('您确定要删除吗?')"> </a><?php endif; ?>
                    </div>
                </td>
            </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
    <ul class="pagelist"><?php echo ($page); ?> <span>共 <?php echo ($count); ?> 条消息</span></ul>
 </form><?php endif; ?>

<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>