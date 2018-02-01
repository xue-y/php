<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
    <div class="body-content">
        <form method="post" class="form-x" action="/Php/Controll/<?php echo $all_data["p_url"];?>">
            <input type="hidden" name="action" value="add_exct">
            <div class="form-group">
                <div class="label">
                    <label><?php echo $all_data["nav"];?>名称：</label>
                </div>
                <div class="field">
                    <label style="line-height:33px;">
                        <input type="text" class="input w50" name="name" value="" data-validate="required:请输入<?php echo $all_data["nav"];?>名,title:<?php echo $all_data["nav"];?>名称长度2--10个字符" />
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label><?php echo $all_data["nav"];?>密码：</label>
                </div>
                <div class="field">
                    <input type="password" class="input w50" name="newpass" size="50" placeholder="请输入新密码" data-validate="wordpass:6到10位之间字符数组 英文 .\@\!\-\_" />
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>确认新密码：</label>
                </div>
                <div class="field">
                    <input type="password" class="input w50" name="renewpass" size="50" placeholder="请再次输入新密码" data-validate="repeat#newpass:两次输入的密码不一致" />
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label><?php echo $this->c_name_g;?>：</label>
                </div>
                <select class="input select_w"  name="group">
                    <?php
                    foreach($all_data["group"] as $k=>$v)
                    {
                        if($k==$all_data["is_g"])
                        {
                            echo " <option value='$k' selected>".$v."</option>";
                        }else
                        {
                            echo " <option value='$k'>".$v."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main" type="submit">保存提交</button>
                    &nbsp; &nbsp;
                    <button class="button bg-main" type="button" onclick="window.history.back();">直接返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body></html>