<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-10
 * Time: 下午7:32
 * 客户个人消息---客户自己登陆后调用
 */

namespace Home\Controller;
use My\WxController;
use Think\Log;

class InfoController extends WxController{

    // 消息列表页
    public function index()
    {
        // 未读消息1 已读为2  没有参数为全部
        $cus=D("Cus_info");
        if(isset($_GET["status"]) && !empty($_GET["status"]))
        {
            $status=add_slashes($_GET["status"]);
            $w["is_read"]=array("eq",$status);
        }
        $w["cid"]=array("eq",$this->uid);
        $count = $cus->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $list = $cus->where($w)->field("easy,is_read,id")->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign(array('list'=>$list,'page'=>$show,'status'=>$status,"count"=>$count));// 赋值数据集
        $this->display(); // 输出模板
    }

    // 读取消息  未读消息1 已读为2
    public function infoRead()
    {
        if(!isset($_GET["id"]) && intval($_GET["id"])<1)
        {
            $this->error("您阅读的信息不存在");
        }
        $id=intval($_GET["id"]);
        $cus_info=D("Cus_info");
        $w["cid"]=$this->uid;
        $w["id"]=intval($_GET["id"]);
        $info=$cus_info->where($w)->field("con,is_read")->find();

        if(empty($info))
        {
            $this->error("您阅读的信息不存在");
        }
        if($info["is_read"]==2)
        {
            $this->assign("info",$info);
            $this->display();
            exit;
        }
        // 更新用户通知信息表
        $is_read=$cus_info->where($w)->setField("is_read",2);
        if(!$is_read)
        {
            $con= __FILE__.PHP_EOL.$this->uid."用户更新通知信息数据出错 | 数据表：self_cus_info |".date('Y-m-d H:i:s',time()).PHP_EOL;
            Log::write($con,"zidingying",'',LOG_F);
        }else
        {
            $this->assign("info",$info);
            $this->display();
        }
    }
} 