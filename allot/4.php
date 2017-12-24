<?php
/*
 倒计时---获取用户的输入的倒计时
*/	session_start();
  if(isset($_SESSION["t"]))
  {
	echo  $_SESSION["t"]; 
  }else
  {
	echo 0;
  }
