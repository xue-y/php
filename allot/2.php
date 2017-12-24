<?php
if(isset($_POST))
{
	if(!isset($_POST["user"]) || empty($_POST["user"]))
	{
		echo "请输入用户名";die;
	}
	if(!isset($_POST["renwu"]) || empty($_POST["renwu"]))
	{
		echo "请输入任务名";die;
	}	
	session_start();
	/*---------------------------- 删除原来数据 start*/
	if(isset($_SESSION["ip"]))
	   unset($_SESSION["ip"]);
	if(isset($_SESSION["user"]))
		unset($_SESSION["user"]);
	if(isset($_SERVER["renwu"]))	
		unset($_SERVER["renwu"]);
	if(isset($_SERVER["t"]))
		unset($_SERVER["t"]);
	if(isset($_SESSION['start_t']))
		unset($_SESSION['start_t']);
	if(file_exists("data.php"))
	    @unlink("data.php");
	if(file_exists("shuju.txt"))
	    @file_put_contents("shuju.txt","##log\r\n");	 
	/*---------------------------- 删除原来数据 end*/
		
	if(isset($_POST["t"]) && !empty($_POST["t"]) && intval($_POST["t"])>1)
	{
		$_SESSION["t"]=intval($_POST["t"]);
	}
	
	$user=explode("|",$_POST["user"]);
	$user=new_addslashes($user);
	$user=array_filter($user);	
	$user=array_unique($user);
	$u=array();
	foreach($user as $v) //----------重置键名
	{
		$u[]=$v;
	}
	$user=$u;
	
	$renwu=explode("|",$_POST["renwu"]);
	$renwu=new_addslashes($renwu);
	$renwu=array_filter($renwu);
	$renwu=array_unique($renwu);
	$da=array();
	foreach($renwu as $v) //----------重置键名
	{
		$da[]=$v;
	}
	$renwu=$da;
	if(count($renwu)<count($user))
	{
		array_splice($user,count($renwu));
	}
	else
	{
		array_splice($renwu,count($user));
	}
	//-------------------------------任务个数与人名个数不一致时
	
	if(is_writable("./"))  //---------------如果当前目录不可创建文件使用session存储
	{
		$con="<?php \r\n\t \$user=".var_export($user,true).";\r\n\t \$renwu=".var_export($renwu,true).';';
	    $c=file_put_contents("data.php",$con);
		if($c<1)
		{
			$_SESSION["user"]=$user;
			$_SERVER["renwu"]=$renwu;
			if(file_exists("data.php"))
			   @unlink("data.php");
		}
    }else
	{
		$_SESSION["user"]=$user;
		$_SESSION["renwu"]=$renwu;
	}
	echo "ok";
}
else
{echo "没有输入数据";}

function new_addslashes($string){
	if(!is_array($string)) 
		return addslashes(trim($string));
	foreach($string as $key => $val) 
	   {$string[$key] = new_addslashes($val);}
	return $string;
}

function new_stripslashes($string) {
	if(!is_array($string)) 
		return stripslashes($string);
	foreach($string as $key => $val) 
		{$string[$key] = new_stripslashes($val);}
	return $string;
}


