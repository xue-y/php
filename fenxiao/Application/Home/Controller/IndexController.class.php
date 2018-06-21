<?php

 /*个人主页
  * */

namespace Home\Controller;
use My\WxController;

class IndexController extends WxController {

    public function index()//个人主页
    {
        $cus=D("Customer");
        $info=$cus->money_n($this->uid);
        if(!empty($info))
        {
            $this->assign("info",$info);
        }else
        {
            $this->error("请重新登录,信息错误");
        }
        $this->display("/index");
    }
}