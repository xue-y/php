/**
 * Created by Administrator on 17-9-17.
 */
$(document).ready(function(e) {
    var sta=1;
    $('#db').focus();// 默认获取光标
    $('#db').blur(function(){
        for(var i=0;i<5;i++)
        {
            if($('.input').eq(i).val()=='')
                return;
        }
        var num=$(this).siblings('div').length;//如果字符验证正确  验证数据库信息是否正确
        if(num==1)
        {
            //发送ajax请求
            $.ajax({
                type:"POST",
                url:"verifyDb",
                data:$('form').serialize(),
                success: function(data,status,xhr){
                    console.log(data);
                    sta=data;
                    if(data=='table_data')
                    {
                        if (confirm("数据库存在数据，请问确定删除吗？\r\n 缓存文件也将一起删除")) {
                            $('.table_data').val(1);
                            sta="db_exis";
                         //   $('#db').focus();
                        }
                        else {
                            alert("请先清空数据库在安装，如有数据请先备份");
                            $('.table_data').val(0);
                        //    $('#db').focus();
                        }
                    }

                    /*if(data=='server_error') // mysql 连接数据库
                        $('#db').siblings('.tips').html("连接服务器失败"); 
                    if(data=="db_nois")
                        $('#db').siblings('.tips').html("数据库不存在请手动创建并授予权限");
					if(data=="db_exis")
						 $('#db').siblings('.tips').html("信息正确，已存在此数据库，直接使用---连接成功");*/
				
				   // pdo 连接数据库
                    else if(data=="db_exis")
					{
						 $('#db').siblings('.tips').html("信息正确，已存在此数据库，直接使用---连接成功");
					}else
					{
						 $('#db').siblings('.tips').html(data);
					}
                       
                },
                error: function(xhr,status,errorinfo){
                    console.log(status)
                    console.log(xhr)
                    console.log(errorinfo)
                },
                timeout:5000
            });
        }
        else
        {
            $('#db').siblings('.tips').html('');
        }
    });

    $('#tijiao').click(function(){
        if(sta==="db_exis")
        {
            $.ajax({
                type:"POST",
                url:"install",
                data:$('form').serialize(),
                beforeSend: function(){
                    $('.load').fadeIn();
                },
                success: function(data,status,xhr){
					var data=data.split(":");
					
                    if(data[0]!="ok")
                    {
                        alert("安装失败"+data);
						$('.load').delay().fadeOut();
                    }else
                    {
                        $('.load').html(":) 安装成功进入系统");
                        $('.load').delay("500").fadeOut();
                        t=setTimeout(function(){window.location.href=data[1]+"/Lock/index";clearTimeout(t);},10)
                    }
                },
                error: function(xhr,status,errorinfo){
                    console.dir(xhr);
                    console.log(status);
                    console.log(errorinfo);
                    $('.load').html("(:安装失败请检查参数").delay("1500").fadeOut();
                },
                timeout:5000
            });
        }
        else
        {
            alert('连接数据库信息错误');
            return;
        }
    });
});
