<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台首页</title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
</head>
<body>
<ul class="bread  clearfix">
    <li><a href="javascript:;" target="right" class="icon-home"> 首页</a></li>
    <li>后台首页</li>
    <a  href="##"  class="help icon-exclamation-sign"> 说明文件</a>
</ul>
<div class="padding">
    <h2 class="padding">
        <?php echo $all_data["user"].' 用户 '.$all_data["time"];?>，欢迎来到个人网站后台管理中心
    </h2>
    <div class="text-big" style="display: none;">
        [用户组]  列表页 编号、操作 id 是数据表id+1  默认从0 开始<br/>
        [用户]    列表页操作 id 是数据表组id_真实人员id  默认从 1 开始<br/>
        <span class="text-blue"> 点击组名跳转组员列表页   用户组id 是数据表id</span><br/>
        [项目类型]  列表页 编号、操作 id 是数据表id+1  默认从0 开始<br/>
        [项目产品]  列表页 编号是 项目类型组数据表id+1_项目产品数据表id+1 <--> 操作是项目类型数据表id_项目产品数据表id<br/>
        [项目回收站]列表页 编号是 项目类型组数据表id+1_项目产品数据表id+1 <--> 操作是项目类型数据表id_项目产品数据表id<br/>
       <span class="text-blue"> 点击组名跳转类型列表页  类型id 是项目类型数据表id</span><br/>
        根据不同的用户分组，不同的用户组员看到的项目价格不同如需 修改调整 请修改 Com.class 288 price_data_field()函数<br/>
        同时修改 321  price_she_veri($g_id) 函数
        <p><a href="/Php/Controll/Log.php"  class="text-dot" target="right">提示操作失败查看日志文件</a></p>
    </div>
</div>
<script>
    $(".help").click(function(){
        $(".text-big").slideToggle("1000");
    })
</script>
</body>
</html>