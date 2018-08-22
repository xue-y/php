<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-14
 * Time: 下午3:11
 * 客户佣金列表
 */

namespace Home\Controller;
use My\HwxController;

class MoneyController extends HwxController {

    // 佣金列表
    public function index()
    {
        $money=D("Cus_money");
        $w["id"]=$this->uid;
        $count = $money->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        $list = $money->where($w)->order('t desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign(array('list'=>$list,'page'=>$show,'count'=>$count));// 赋值数据集
        $this->display(); // 输出模板
    }
} 