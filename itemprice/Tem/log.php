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
</head>
<body><!--日志管理----列表-->
<ul class="bread  clearfix">
    <?php echo $this->index_url;?>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>"><?php echo $all_data["nav"].$all_data["action"];?></a></li>
</ul>
<div class="panel">
        <form   method="post">
                <p class="export">
                    <a href="javascript:;" class="button border-red" ><span class="icon-trash-o"></span>
                        <input type="button" onclick="DelSelect($(this),'删除','id')" class="a_del" value="批量删除" name="dels">
                    </a>
                </p>
            <table class="table table-hover text-center">
                <tr>
                    <th  width="15%" style="text-align:left; padding-left:20px;"><input type="checkbox" /><span>全选</span></th>
                    <th >文件名称</th>
                    <th >文件时间</th>
                    <th>操作</th>
                </tr>
                <?php
                if(!isset($list) || empty($list))
                {
                    echo "<tr><td  colspan='4'>暂无数据</td></tr>";
                }else
                {
                 foreach($list as $k=>$v)
                 {  echo ' <tr>
                <td><input type="checkbox" name="id[]" value="'.$v['n'].'" /></td>
                <td>'.$v['n'].'</td>
                <td>'.$v['t'].'</td>
                <td><div class="button-group">
                <a class="button border-green" href="?h=sel&n='.$v['n'].'"><span class="icon-refresh"></span>查看</a>';
                      if($v['n']=='error'.self::$conf_data["DATA_EXT"])
                       {
                           echo '<a class="button border-red" data-href="?h=del&n='.$v['n'].'"  href="javascript:;"  onclick="del($(this),\'清空\')" title="系统自动生成日志"><span class="icon-trash-o">清空</span>';
                       }else
                      {
                          echo '<a class="button border-red" data-href="?h=del&n='.$v['n'].'"  href="javascript:;"  onclick="del($(this),\'删除\')"><span class="icon-trash-o">删除</span>';
                      }
                    echo '</a></div></td></tr>';
                    } //foreach 结束
                }
                ?>

            </table>
        </form>
    <div class="pagelist">
        <?php
        if(isset($list) && !empty($list))
        {
            $page = Com::getInstance("Spage") ;
            $page->show($total,$showrow,$show_page); // Spage.php

            /*$page=Com::getInstance("Tpage");
             $page->init($total,$showrow,$show_page);
             $page->show();*/ //1 2 3 >> Tpage.php
        }
        ?>
    </div>
</div>
<script src="/Static/js/arc_list.js" charset="UTF-8"></script>
</body></html>