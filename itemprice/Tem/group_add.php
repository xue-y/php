<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["nav"].$all_data["action"];?></title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
    <script src="/Static/js/pintuer.js"></script>
</head>

<body>
<ul class="bread  clearfix">
    <?php echo $this->index_url;?>
    <li><a href="<?php echo $all_data["p_url"];?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><?php echo $all_data["nav"].$all_data["action"];?></li>
</ul>
<div class="panel admin-panel">
    <div class="body-content" style=" padding:50px">
        <form method="post" class="form-x" action="/Php/Controll/Group.php">
            <input type="hidden" name="action" value="add_exct">
            <div class="form-group">
                <div class="label">
                    <label><?php echo $all_data["nav"];?>名称：</label>
                </div>
                <div class="field">
                    <input type="text" placeholder="" class="input w50" name="name[]" data-validate="required:请输入<?php echo $all_data["nav"];?>名称,title:<?php echo $all_data["nav"];?>名称长度2--10个字符"/>
                    <div class="tips"></div>
                    <p class="left" style="margin: 5px; font-size: 28px;cursor: pointer" onClick="add_group($(this),'.form-group')">+</p>
                    <p class="left" style="margin: 5px; font-size: 28px;cursor: pointer" onClick="del_group($(this),'.form-group')">-</p>
                </div>
            </div>
            <div  style="margin-left: 83px;">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main" type="submit"> 提交保存</button>
                    &nbsp; &nbsp;   &nbsp;
                    <button class="button bg-main" type="button" onclick="window.history.back();"> 直接返回</button>
                </div>
            </div>
        </form>
    </div>
</div>    <script src="/Static/js/arc_list.js"></script>
</body>
</html>
