/**
 * Created by Administrator on 17-9-17.
 */
$(document).ready(function(e) {
    var sta=1;
    $('#db').focus(function(){
        $('#db').siblings('.tips').html("");
    });

    // 判断是否验证完成    判断是否执行过失去光标后的验证
    var status=true;is_blur=true;
    // 初步验证数据库信息-----只验证按顺序填写的 数据库信息
    $('#db').blur(function(){
        blur_vail($(this));
    });

    $('#tijiao').click(function(){
        if(!status) // 等待 数据库信息验证完成 在 提交 防止 tabel用户是否选择清空
        {
            $.ajax({
                type:"POST",
                url:"/install/Index/install",
                data:$('form').serialize(),
                beforeSend: function(){
                    var h=$(document).height()+$(document).scrollTop();
                    $('.load').height(h);
                    $('.load').fadeIn();
                },
                success: function(data,status,xhr){
                    if(data=='ok')
                    {
                        $('.load').css("padding-top",'10%');
                        $('.load').html(":) 安装成功进入系统");
                        $('.load').delay("1500").fadeOut();
                        var t=setTimeout(function(){window.location.href="/back/Lock/index";clearTimeout(t);},30)
                    }else
                    {
                        console.log(data);
                        $('.load').fadeOut();
                    }
                },
                error: function(xhr,status,errorinfo){
                    $('.load').html("(:安装失败请检查参数").delay("1500").fadeOut();
                },
                timeout:5000
            });
        }else
        {
            if(is_blur==true)
            {
                blur_vail($('#db'));//  判断是否执行过失去光标后的验证
            }
        }

    });
    // 验证数据表
    function vail_table(data)
    {
        if(data=='table_data')
        {
            if (confirm("数据库存在数据，请问确定删除吗？\r\n 缓存文件也将一起删除")) {
                $('.table_data').val(2);
                sta="db_exis";
            }
            else {
                alert("请先清空数据库再安装，如有数据请先备份");
                $('.table_data').val(2);
            }
        }

    }
    // 验证数据库信息
    function vail(data)
    {
        sta=data; // 记录状态

        if(data=='server_error')
        {
            $('#db').siblings('.tips').html("连接数据库失败或没有此数据库的权限");
        }

        if(data=="db_noexis")
            $('#db').siblings('.tips').html("数据库不存在请手动创建并授予权限");
        if(data=="db_exis")
        {
            status=false;
            $('#db').siblings('.tips').html("信息正确，已存在此数据库，直接使用---连接成功");
        }
    }

    function blur_vail(ele){
        for(var i=0;i<5;i++)
        {
            if($('.input').eq(i).val()=='')
                return false;
        }
        var num=ele.siblings('div').length;//如果字符验证正确  验证数据库信息是否正确
        var data=$('form').serialize();
        if(num==1)
        {
            is_blur=false; //  判断是否执行过失去光标后的验证
            status=false;
            //发送ajax请求
            $.ajax({
                type:"POST",
                url:"/install/Index/validb",
                data:data,
                success: function(data,status,xhr){
                    vail_table(data);//验证数据表
                    vail(data); // 验证数据库信息
                },
                error: function(xhr,status,errorinfo){
                    console.log(status)
                    // console.log(xhr)
                    console.log(errorinfo)
                },
                timeout:5000
            });
        }
        else
        {
            $('#db').siblings('.tips').html('');
        }
    }

});
