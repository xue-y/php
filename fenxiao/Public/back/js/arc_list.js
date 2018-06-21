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
in_order()//-------------------排序框
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
    var len=$('table input[type=checkbox]').length;
    var T=$('table input[type=checkbox]').eq(0);
    T.after("<span></span>");
    var change_info=T.siblings('span');
    var start='共 '+(len-1)+' 项'
    $('input[type=checkbox]').eq(0).click(function()
    {
        var status= $('input[type=checkbox]').eq(0).prop('checked');
		if(len>1)
        	quxuan(change_info,len,status);
    });//全选、取消
    child(change_info,len,start);
}
function quxuan(change_info,len,status)
{
    if(status=="checked" || status==true)
    {
        var t_num=' '+(len-1)+' 项'
        $(change_info).text(t_num);
        for(i=0;i<=len;i++)
        {
            $('input[type=checkbox]').eq(i).prop('checked',true);
            //    $('input[type=checkbox]').eq(i).attr("checked","checked");
        }
    }
    else
    {
        $(change_info).text('');
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
            else if(option=='' || option==start || option=="全选")
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
                $(change_info).text(' '+(j)+' 项');
            else
                $(change_info).text('');
        });
    }//子的控制总的全选
} // 子选项点击
//----------------------------------------------------------------------全选删除
//全选
/*$("#checkall").click(function(){
  $("input[name='id[]']").each(function(){
	  if ($(this).attr("checked")=="checked") {
          $(this).removeAttr("checked");
          $(this).prop("checked",false);
	  }
	  else {
          $(this).prop("checked",true);
          $(this).attr("checked","checked");
	  }
  });
});*/
$(".radio label").each(function(i,ele){
    $(".radio label").eq(i).click(function(){
        $(".radio label").eq(i).addClass("active").siblings().removeClass("active");
        $(".radio label").eq(i).siblings().find("input").removeAttr("checked");
        $(".radio label").eq(i).find("input").attr("checked","checked");
    });
})
//批量删除
function DelSelect(th){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){
		var t=confirm("您确认要删除选中的内容吗？");
		if (t!=true) return false;
         th.attr("type","submit");
	}
	else{
		alert("请选择您要删除的内容!");
		return false;
	}
}

tab($(".nav-inline li"),$(".content"));
/*an 是点击的按钮
* con 是要切换的元素块
* */
function tab(an,con)
{
   var len=an.length ;
   var now=0;
    for(var i=0;i<len;i++)
    {
        an[i].index=i;
        an[i].onclick=function()
        {
            if(now!=this.index)
            {
                addClass(an[this.index],"active");
                removeClass(an[now],"active");
                addClass(con[now],"hidden");
                removeClass(con[this.index],"hidden");
               /* an[now].removeClass('active');
                an[this.index].addClass('active');
                con[now].removeClass("hidden");
                con[this.index].addClass("layout");*/
            }

            now=this.index;
        };
    }
}//------------------tab表单切换
function hasClass(obj, cls) {
    return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function addClass(obj, cls) {
    if (!this.hasClass(obj, cls)) {
        obj.className += " " + cls;
    }
}

function removeClass(obj, cls) {
    if (hasClass(obj, cls)) {
        var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
        obj.className = obj.className.replace(reg, ' ');
    }
}
//批量排序
function sorts(){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){	
		
		$("#listform").submit();		
	}
	else{
		alert("请选择要操作的内容!");
		return false;
	}
};

//跳转到指定的邮箱登录页面
/*var mail_url = gotoEmail("1922527784@qq.com");
 mail_url='<a href="http://'+mail_url+'">去我的邮箱</a>';
 $("body").append(mail_url);*/
