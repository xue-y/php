// JavaScript Document
function in_order()
{
	$('.in_order').each(function(i){
		
		$('.in_order').eq(i).focus(function()
		{
			$(this).css('border','1px solid #ddd');
		})
		$('.in_order').eq(i).blur(function()
		{
			$(this).css('border','none');
		})
	})
}
in_order()//-------------------排序框 文本框
//搜索
function changesearch(){
	var v=$('.add_arctice').val();
	console.log(v);
	if(v!=='0')
	{
		if($('.add_arctice').hasClass('requer'))
		 $('.add_arctice').removeClass('requer');
		$('#add_arc').attr('href',v+'.html');
	}else
	{
		$('#add_arc').attr('href','#');
	}
}

$('#add_arc').click(function(){
	if($('#add_arc').attr('href')=='#')
	{
		if(!$('.add_arctice').hasClass('requer'))
	    $('.add_arctice').addClass('requer');
	}
});//-----------------------------------------添加文章

//----------------------------------------------------------------------全选删除
check();
function check()
{
    var len=$('input[type=checkbox]').length;
    var T=$('input[type=checkbox]').eq(0);
    var change_info=T.siblings('span');
    var start='共 '+(len-1)+' 项'
    $('input[type=checkbox]').eq(0).click(function()
    {
        var status= $('input[type=checkbox]').eq(0).prop('checked');
        quxuan(change_info,len,status);
    });//全选、取消
    child(change_info,len,start);
}
function quxuan(change_info,len,status)
{
    if(status=="checked" || status==true)
    {
        var t_num='选中 '+(len-1)+' 项'
        $(change_info).text(t_num);
        for(i=0;i<=len;i++)
        {
            $('input[type=checkbox]').eq(i).prop('checked',true);
       //    $('input[type=checkbox]').eq(i).attr("checked","checked");
        }
    }
    else
    {
        $(change_info).text('未选中');
        for(i=0;i<len;i++)
        {
            $('input[type=checkbox]').eq(i).removeAttr('checked');
            $('input[type=checkbox]').eq(i).prop('checked',false);
        }
    }
}//全选
function child(change_info,len,start)
{
    var j=null;
    len=len-1;
    for(var i=1;i<=len;i++)
    {
        $('input[type=checkbox]').eq(i).click(function()
        {
            option=$(change_info).text();
            if(option=="")
            {
                j=0;
            }
            else if(option=='未选中选项' || option==start || option=="全选")
            {	j=0; }// 取消全选 | 第一次选中
            else
            {
                option=option.replace(/[^0-9]/ig,"");
                option=parseInt(option);
                j=option;
            }

            if($(this).prop('checked')==true || $(this).attr('checked')=="checked")
            {
               ++j;
            }
            else
            {
               --j;
            }
            if(j>=1)
                $(change_info).text('选中 '+(j)+' 项');
            else
                $(change_info).text('未选中选项');
        });
    }//子的控制总的全选
} // 子选项点击
//----------------------------------------------------------------------全选删除

//批量删除
/** tis  当前操作对象
 * info  提示信息
 * id 表单提取字段
* */
function DelSelect(tis,info,id){
	var Checkbox=false;
    $("input[name^='"+id+"']").each(function(){
        if (this.checked==true) {
            Checkbox=true;
        }
    });
    if (Checkbox){
		var t=confirm("您确认要"+info+"选中的内容吗？");
        if(t)
        {
            tis.attr("type","submit");
            return true;
        }else
        {
            return false;
        }

	}
	else{
		alert("请选择您要"+info+"的内容!");
		return false;
	}
}

function del(t,info)
{
    var tt=confirm("您确认要"+info+"选中的内容吗？");
    if (tt)
    {
       var href=t.attr("data-href");
        t.attr("href",href);
        return;
     //   return true;
    }
    else
    {
        return false;
    }
}

//跳转到指定的邮箱登录页面
/*var mail_url = gotoEmail("1922527784@qq.com");
 mail_url='<a href="http://'+mail_url+'">去我的邮箱</a>';
 $("body").append(mail_url);*/

//功能：根据用户输入的Email跳转到相应的电子邮箱首页
function gotoEmail(mail) {
    $t = mail.split('@')[1];
    $t = $t.toLowerCase();
    if ($t == '163.com') {
        return 'mail.163.com';
    } else if ($t == 'vip.163.com') {
        return 'vip.163.com';
    } else if ($t == '126.com') {
        return 'mail.126.com';
    } else if ($t == 'qq.com' || $t == 'vip.qq.com' || $t == 'foxmail.com') {
        return 'mail.qq.com';
    } else if ($t == 'gmail.com') {
        return 'mail.google.com';
    } else if ($t == 'sohu.com') {
        return 'mail.sohu.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'vip.sina.com') {
        return 'vip.sina.com';
    } else if ($t == 'sina.com.cn' || $t == 'sina.com') {
        return 'mail.sina.com.cn';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yahoo.com.cn' || $t == 'yahoo.cn') {
        return 'mail.cn.yahoo.com';
    } else if ($t == 'tom.com') {
        return 'mail.tom.com';
    } else if ($t == 'yeah.net') {
        return 'www.yeah.net';
    } else if ($t == '21cn.com') {
        return 'mail.21cn.com';
    } else if ($t == 'hotmail.com') {
        return 'www.hotmail.com';
    } else if ($t == 'sogou.com') {
        return 'mail.sogou.com';
    } else if ($t == '188.com') {
        return 'www.188.com';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else if ($t == '189.cn') {
        return 'webmail15.189.cn/webmail';
    } else if ($t == 'wo.com.cn') {
        return 'mail.wo.com.cn/smsmail';
    } else if ($t == '139.com') {
        return 'mail.10086.cn';
    } else {
        return '';
    }
};
//--------------------------------------------------------------

// tab 菜单切换
// 获取hash 值
function hash_fn()
{
    var url=window.location.href;
    var url_arr=url.split("#");
    {
        if(url_arr.length==2)
            return url_arr[0];
        else
            return null;
    }
}
tab_toggle($("#data .tab-nav li"),$(".panel"));
function tab_toggle(nav,con)
{
    nav.each(function(i,ele){
        $(this).click(function(){

            con.eq(i).addClass("on").siblings().removeClass("on");
            $(this).addClass("on").siblings().removeClass("on");
        });
    })
}
//采用正则表达式获取地址栏参数
function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null) return r[2];
}

// 桌面提醒
// 调用 showNotice("请去您的[个人信息]页面查看","现在有 1 条反馈信息")；
function showNotice(title,msg){
    var bool=true;
    var Notification = window.Notification || window.mozNotification || window.webkitNotification;
    if(Notification){
        Notification.requestPermission(function(status){
            //status默认值'default'等同于拒绝 'denied' 意味着用户不想要通知 'granted' 意味着用户同意启用通知
            if("granted" != status){
                return;
            }else{
                var tag = "sds"+Math.random();
                var notify = new Notification(
                    title,
                    {
                        dir:'auto',
                        lang:'zh-CN',
                        tag:tag,//实例化的notification的id
                        //  icon:'http://www.yinshuajun.com/static/img/favicon.ico',//通知的缩略图,//icon 支持ico、png、jpg、jpeg格式
                        body:msg //通知的具体内容
                    }
                );
                notify.onclick=function(){
                    //如果通知消息被点击,通知窗口将被激活
                    window.focus();
                },
                    notify.onerror = function () {
                        console.log("HTML5桌面消息出错！！！");
                    };
                notify.onshow = function () {
                    setTimeout(function(){
                        notify.close();
                    },50000)
                };
                notify.onclose = function () {
                    console.log("HTML5桌面消息关闭！！！");
                };
            }
        });
    }else{
        console.log("您的浏览器不支持桌面消息");
    }
}; // 桌面提醒

//文件上传
/*  ele 上传域
    n上传文件个数
    max 单个文件上传大小
    files_max 总文件上传大小
    s_f 服务上传文件 服务器上传一次只能选择一个
    t上传文件按钮*/
/*php.ini
        file_uploads = On ;打开文件上传选项<br/>
        max_file_uploads=你需要的文件个数<br/>
        upload_max_filesize = 500M ;其表示为上传文件的最大值[单个文件]<br/>
        post_max_size = 500M ;设定POST数据所允许的最大限制值[总的post上传大小]<br/>
        max_execution_time=30;其意思就是该页面最长执行的时间为30s<br/>
        max_input_time = 600;每个PHP页面接收数据所需的最大时间，默认60秒<br/>
        memory_limit = 128m;每个PHP页面所吃掉的最大内存，默认128M<br/>
 */
function check_file(ele,n,max,files_max,s_f,t)
{
    var file =$(ele).prop('files');
    var file_len=file.length;
    if(file_len>n)
    {
        alert("上传文件超过"+n+"个");return false;
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
        if(fileSize>(2*1024*1024))
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
        if(file.length<1 )return false;// 没有上传文件
        $(".load").css("display","block");
        $(t).attr("type","submit");
    }
}

/*添加复制文本框*/
function add_group(t,t_parent)
{
    var len=$("body").find(t_parent).length+1;
    if(len>10)
    {
        alert("一次最多添加10个");
        return false;
    }
    var inp=$(t).parents(t_parent).eq(0).clone(true);
    $(t).siblings("input").attr("placeholder","第"+len+"个");
    $(t).parents(t_parent).eq(0).after(inp);
}

/*删除文本框
*t 当前操作的元素
* t_parent 此元素只为页面操作元素的父级
* */
function del_group(t,t_parent)
{
  // 删除点击的下一个  t_parent 元素
  $(t).siblings("input").attr("placeholder","");
  var del_inp=$(t).parents(t_parent).next(t_parent);
    del_inp.remove();

  /*  var del_inp=$(t).parents(t_parent);
    if($(t_parent).length>1)
    { del_inp.remove();}*/
}

//
function recursion_check_all()
{
    $("h4").each(function(){
        $(this).find("input").click(function(){
            var val=$(this).val();
            if($(this).is(':checked')==true)
            {
                $("."+val).prop("checked",true);
            }else
            {
                $("."+val).prop("checked",false);
            }
        });
    });
    $("h3").each(function(){
        $(this).find("input").click(function(){
            var val=$(this).val();
            if($(this).is(':checked')==true)
            {

                $("."+val).prop("checked",true);
                $("."+val).each(function(){
                    var val2=$(this).val();
                    $("."+val2).prop("checked",true);
                })
            }else
            {
                $("."+val).prop("checked",false);
                $("."+val).each(function(){
                    var val2=$(this).val();
                    $("."+val2).prop("checked",false);
                })
            }
        });
    });
    $("h5").each(function(){
        var h5_t=$(this);
        $(this).find("input").click(function(){
            if($(this).is(':checked')==true && h5_t.next().hasClass("none"))
            {
                h5_t.next().find('input').prop("checked",true);
            }
            else if($(this).is(':checked')!=true && h5_t.next().hasClass("none"))
            {
                h5_t.next().find('input').prop("checked",false);
            } //-------------------------------选择执行的添加。修改 id
            var T=$(this).attr('class');
            $("h4").each(function(){
                var val3=$(this).find("input").val();
                if(val3==T)
                {
                    var f_c=$(this).find("input").attr('class');
                    $("h3 input").each(function(){
                        if($(this).val()==f_c)
                            $(this).prop("checked",true);
                    });
                    $(this).find("input").prop("checked",true);
                }
            })
        });
    });
}

