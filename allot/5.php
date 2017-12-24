<?php
//执行倒计时
if(isset($_POST["t"]))
{
	session_start();
	date_default_timezone_set('PRC');
	if(!isset($_SESSION['start_t']))
	{
		$_SESSION['start_t']=time()+$_POST["t"];
	}
	$end_t=$_SESSION['start_t']-time();
	$m=floor($end_t/60)."[min] ";
		if($m<1)$m='';
		$s=$end_t%60 ."[s]";
	    if($s<1)$s="现在开始";
	    $t=$m.$s;
	$arr["t"]=$end_t;
	$arr["meg"]=$t;
	echo json_encode($arr);
}
