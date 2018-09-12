<?php
/**
 * https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=19410520602&lang=zh_CN
 * wechat php test
 *  验证回调地址   token
 *  服务器设置---->服务验证token
 */

//服务器地址(URL)
//http://域名/Public/wx_token.php

//定义TOKEN密钥
define("TOKEN","loginzf");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
            exit;
        }
    }

	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}