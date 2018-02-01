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
<?php
// 判断身份
$g_u=explode(DE_LIMITER,$_SESSION["id"]); // 取得组id 用户数据文件编号   用户id
$gid_token=$this->price_she_biaoshi($g_u[0],$_SESSION["token"],PHP_CON."Login.php");
$price_data_key=array_keys($this->price_data_field());
if(TOKEN===$gid_token)
{
  echo '<ul class="bread  clearfix">
            '.$this->index_url.'
            <li><a href="'.$all_data["p_url"].'" id="a_leader_txt">'.$all_data["nav"].'管理</a></li>
            <li>'. $all_data["nav"].$all_data["action"].'</li>
        </ul>';
}else
{
    if(isset($_SERVER['HTTP_REFERER']))
    {
        $referef_url=$_SERVER['HTTP_REFERER'];
    }else
    {
        $referef_url="javascript:;";
    }
    echo '<ul class="bread  clearfix">
            <li><a href="'.$referef_url.'" onclick="window.history.back();" >返回首页</a></li>
          </ul>';
}
?>
<div class="panel admin-panel">
    <div class="body-content">
        <p class="red">如果值为空默认为不修改, 如果修改密码其中一个密码值为空默认不修改</p>
        <form method="post" class="form-x" action="/Php/Controll/<?php echo $all_data["p_url"];?>?h=edit_exct">
            <input type="hidden" name="action" value="edit_exct">
            <?php

            if(in_array($gid_token,$price_data_key))  // 普通用户
            {
                echo '<input type="hidden" name="u_id" value="'.$g_u[1].'">
                    <input type="hidden" name="g_id" value="'.$g_u[0].'">';
            }else                        //---管理员用户
            {
               echo '<input type="hidden" name="u_id" value="'.$all_data["u_id"].'">
                    <input type="hidden" name="g_id" value="'.$all_data["g_id"].'">'.PHP_EOL;
            }
            $cur_id=$all_data["g_id"].DE_LIMITER.$all_data["u_id"];//当前用户id
            ?>
            <div class="form-group">
                <div class="label">
                    <label><?php echo $all_data["nav"];?>名称：</label>
                </div>
                <div class="field">
                    <label style="line-height:33px;">
                        <input type="text" class="input w50" name="name" value="<?php echo $all_data[$this->n_field];?>" data-validate="required:请输入<?php echo $all_data["nav"];?>名,title:<?php echo $all_data["nav"];?>名称长度2--10个字符" />
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label>原密码：</label>
                </div>
                <div class="field">
                    <input type="password" class="input w50" name="oldpass" size="50" placeholder="请输入新密码" data-validate="wordpass:6到10位之间字符数组 英文 .\@\!\-\_" />
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
                <select class="input select_w"  name="<?php echo $this->table_parent ;?>">
                    <?php
                    if(in_array($gid_token,$price_data_key) || $cur_id===$_SESSION["id"])  // 普通用户修改自己的 管理员用户修改自己的
                    {
                        echo " <option value='$g_u[0]' selected>".$all_data[$this->table_parent][$g_u[0]]."</option>";
                    }else   if(TOKEN===$gid_token)  //---管理员用户修改其他用户
                    {
                        foreach($all_data["group"] as $k=>$v)
                        {
                            if($k==$all_data[$this->g_id_field])
                            {
                                echo " <option value='$k' selected>".$v."</option>";
                            }else
                            {
                                echo " <option value='$k'>".$v."</option>";
                            }
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