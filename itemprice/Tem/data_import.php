<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["nav"].$all_data["action"].'---'.$tit;?></title>
    <link rel="stylesheet" href="/Static/css/pintuer.css">
    <link rel="stylesheet" href="/Static/css/admin.css">
    <script src="/Static/js/jquery.js"></script>
</head>
<body><!--数据管理----导入-->
<ul class="bread  clearfix">
    <?php echo $Data->index_url;?>
    <li><a href="<?php echo  $_SERVER['PHP_SELF'] ;?>" id="a_leader_txt"><?php echo $all_data["nav"];?>管理</a></li>
    <li><?php echo $all_data["nav"].$all_data["action"].'---'.$tit;?></li>
</ul>
<div class="panel admin-panel">
    <p><span class="icon-sort-down" style="font-size:30px; padding: 0px 10px; color:#0099FF; cursor: pointer"
             onclick=' $("#updown").slideToggle(1000);'></span>展开信息</p>
    <p  style="display: none" id="updown">
        <span  class="red">导入多个、单个、压缩文件[解压多个文件] 允许的格式zip txt excel 文件<br/>
        文件里面的数据格式必须有导出数据格式一致否则失败文件<br/>
        一次最大可上传5个，单个文件大小最大 5MB，总文件大小最大20 MB<br/>
        同名文件自动覆盖</span><br/>
        <span class="green">
        /*php.ini<br/>
        file_uploads = On ;打开文件上传选项<br/>
        max_file_uploads=你需要的文件个数<br/>
        upload_max_filesize = 500M ;其表示为上传文件的最大值[单个文件]<br/>
        post_max_size = 500M ;设定POST数据所允许的最大限制值[总的post上传大小]<br/>
        max_execution_time=30;其意思就是该页面最长执行的时间为30s<br/>
        max_input_time = 600;每个PHP页面接收数据所需的最大时间，默认60秒<br/>
        memory_limit = 128m;每个PHP页面所吃掉的最大内存，默认128M<br/>
        */</span>
    </p>
    <div class="body-content">
        <form method="post" class="form-x import" enctype="multipart/form-data" action=""  >
            <div class="form-group">
                <div class="label">
                    <label>导入文件</label>
                </div>
                <div class="field">
                    <input type="file" name="file[]" multiple class="input" id="files">
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label></label>
                </div>
                <div class="field">
                    <button class="button bg-main" type="button"  onclick="check_file('#files',5,5,20,this)">导入数据</button>
                    &nbsp; &nbsp;
                    <button class="button bg-main" type="button" onclick="window.history.back();">直接返回</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="load">
    <img src="/Static/img/load.png">
</div>
<script src="/Static/js/arc_list.js" charset="UTF-8"></script>
<script>
    // 所选元素  上传个数 单个元素最大值  上传文件总个数大小  提交按钮元素
    function check_file(ele,n,max,files_max,t)
    {
        var file =$(ele).prop('files');
        var file_len=file.length;
        if(file_len>n)
        {
            alert("上传文件超过"+n+"个");
            return false;
        }
        // console.dir(file);
        var fileSize=0;
        var conut_size=0;
        for(var i=0;i<file_len;i++)
        {
            //console.log(file[i]);
            /*lastModified: 1505204874155
             lastModifiedDate: Tue Sep 12 2017 16:27:54 GMT+0800 (中国标准时间)
             name: "dede.txt"
             size: 22180
             type: "text/plain"
             * */
            fileSize=$(file)[i].size;
            if(fileSize>(max*1024*1024))
            {
                alert($(file)[i].name+'大于'+max+'MB');return false;
            }
            conut_size+=fileSize;
        }
        conut_size=conut_size/1024/1024;
        if(conut_size>8)
        {
            alert("总的上传文件大小"+conut_size.toFixed(2)+"大于"+files_max+"MB");
            return false;
        }else
        {
            if(file.length<1)return;
            $(".load").css("display","block")
            $(t).attr("type","submit");
        }
    }
</script>
</body></html>