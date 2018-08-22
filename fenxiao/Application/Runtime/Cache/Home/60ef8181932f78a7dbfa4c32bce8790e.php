<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <script  type="text/javascript" src="/Public/back/js/jquery.js"></script>


<title>商品下单</title>
</head>
<body class="pay">
<h3>
    <a  onclick="javascript :history.back(-1)" >←</a> | 商品下单   <a href="/Home/Index/index" class="float-right">主页</a>
</h3>

    <form action="/Home/Pay/index"   method="post">
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="product_name" placeholder="商品名称"    value="测试商品"/>
                <div class="tips"></div>
            </div>
        </div>
        <input type="hidden" class="input w100" name="product_id"  value="S0001"/>
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="body" placeholder="商品描述"    value="商品描述"/>
                <div class="tips"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="field">
                <input type="text" class="input w100" name="total_fee" placeholder="商品金额"    value="0.01"/>
                <div class="tips"></div>
            </div>
        </div>

        <div class="form-group">
            <div class="field" style="text-align: center">
                <button class="button bg-main" type="submit">确认下单</button>
                &nbsp;  &nbsp;  &nbsp;
                <input type="reset" value="清空重置" class="button bg-main" >
            </div>
        </div>
    </form>

<link type="text/css" rel="stylesheet" href="/Public/back/css/pintuer.css" >
<link type="text/css" rel="stylesheet" href="/Public/home/home.css" >
<script  type="text/javascript" src="/Public/back/js/arc_list.js"></script>
<script type="text/javascript" src="/Public/back/js/pintuer.js"></script>
</body>
</html>