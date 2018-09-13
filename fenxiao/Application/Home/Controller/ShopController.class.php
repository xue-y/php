<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-27
 * Time: 上午10:52
 */

namespace Home\Controller;
use Think\Controller;


class ShopController extends Controller{

    public function index()
    {
        header("Content-Type:text/html;charset=utf-8");
        $history=CONTROLLER_NAME.FEN_FU.ACTION_NAME;
        echo "<a href='/Home/Login/sign?history=".$history."'>登录后跳转原页面</a>";
    }

} 