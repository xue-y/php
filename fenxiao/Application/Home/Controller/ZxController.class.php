<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-15
 * Time: 下午2:31
 * 客户的咨询-信息
 */

namespace Home\Controller;
use My\WxController;

class ZxController extends WxController {

    public function index()
    {
        // 取得自己咨询师的信息
        $cus=D("Customer");
        $zx=$cus->zx($this->uid);

        if(empty($zx))
        {
            $url="http://plt.zoosnet.net/LR/Chatpre.aspx?id=PLT22186113";
            $this->error("暂无客户消息<br/>请点击 <a href='{$url}'>在线咨询</a> <br/> 或 <a href='tel:0531-55571118'>拨打电话</a>",U("Index/index"),30);
         //   $this->error("您的个人信息错误,请刷新页面或重新登录");
        }
        $this->assign("info",$zx);
        $this->display();
    }

} 