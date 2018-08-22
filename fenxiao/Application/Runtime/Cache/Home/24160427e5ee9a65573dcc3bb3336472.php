<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>


<title>测试付款</title>
</head>
<body class="pay">
<h3>
    <a  onclick="javascript :history.back(-1)" >←</a> | 测试付款   <a href="/Home/Index/index" class="float-right">主页</a>
</h3>

    <form action="/Home/Pay/Pay"   method="post">
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="product_id" placeholder="商品id"    value=""/>
                <div class="tips"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="body" placeholder="商品描述"    value=""/>
                <div class="tips"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="total_fee" placeholder="商品金额"    value="0.01"/>
                <div class="tips"></div>
            </div>
        </div>
        <input  type="hidden" vlaue="" name="agent" id="agent">

        <div class="form-group">
            <div class="field" style="text-align: center">
                <button class="button bg-main" type="submit">确认下单</button>
                &nbsp;  &nbsp;  &nbsp;
                <input type="reset" value="清空重置" class="button bg-main" >
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