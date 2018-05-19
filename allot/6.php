<?php 
/* 后台数据*/
session_start();
if(!empty($_POST)) //当用户选择了之后
{
	$u_index=$_POST["n"];
	$rw_index=$_POST["rw"];
	
	if(!file_exists("data.php"))
	{
		$user=$_SESSION["user"];
	    $renwu=$_SESSION["renwu"];
	}
	else
	{
		require "data.php";
	}
	
	//----------------------------------------写入log 日志开始
	$old_data=implode("|",$renwu);	
	date_default_timezone_set('PRC');
	$w_log="\r\n原数据:".$old_data."\r\n";
	$w_log.="用户数据：".$user[$u_index]."------编号".$rw_index."：".$renwu[$rw_index]."\r\n";
	$w_log.="时间：".date("Y-m-d H:i:s",time())."\r\n\r\n";
	file_put_contents("shuju.txt",$w_log,FILE_APPEND);
	//----------------------------------------写入log 日志结束
	

	unset($user[$u_index]);
	unset($renwu[$rw_index]); //---删除后变成关联数组
	
	$user=array_values($user);
    $renwu=array_values($renwu);// 关联数组转索引数组
	
	if(is_writable("./"))
	{
		$con="<?php \r\n\t \$user=".var_export($user,true).";\r\n\t \$renwu=".var_export($renwu,true).';';
		$c=file_put_contents("data.php",$con);
		if(is_file("data.php") && $c<1)
		{
			$_SESSION["user"]=$user;
			$_SESSION["renwu"]=$renwu;
			if(file_exists("data.php"))
			   @unlink("data.php");
		}
	}else
	{
		$_SESSION["user"]=$user;
		$_SESSION["renwu"]=$renwu;
	}
		
}else //-----------------用户第一次进入
{
	if(!file_exists("data.php"))
	{
	   $user=$_SESSION["user"];
	   $renwu=$_SESSION["renwu"];
	}
	else
	{
		require "data.php";
	}
}

$shu=array("state"=>"ok",'n'=>$user,'shu'=>$renwu);
echo json_encode($shu); 
