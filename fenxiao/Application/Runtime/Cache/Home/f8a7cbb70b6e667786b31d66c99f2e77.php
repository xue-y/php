<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>


<title>确认订单</title>
</head>
<body class="pay">
<h3>
    <a  onclick="javascript :history.back(-1)" >←</a> | 确认订单   <a href="/Home/Pay/index" class="float-right">主页</a>
</h3>

<ul>
    <li>商品编号：<?php echo ($shop["product_id"]); ?></li>
    <li>商品名称：<?php echo ($shop["product_name"]); ?></li>
    <li>商品描述：<?php echo ($shop["body"]); ?></li>
    <li>商品价格：<?php echo ($shop["total_fee"]); ?></li>
</ul>

    <form action="/Home/Pay/Pay"   method="post">
        <input  type="hidden" value="" name="agent" id="agent">
        <input  type="hidden"  name="shop_info" value="<?php echo ($shop_info); ?>" >
        <div class="form-group">
            <div class="field" style="text-align: center">
                <button class="button bg-main" type="submit">确认付款</button>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            var isB= isBrowser(); // 判断浏览器
            var agent="pc";
            if(isMobile()==true && isB=="wx") // 如果用户使用 微信客户端打开的
            {
                agent="wx";
            }else if(isMobile()==true)  // 如果是移动端打开的
            {
                agent="mob";
            }
            console.log(agent);
            $("#agent").val(agent);
        })

    </script>

<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>