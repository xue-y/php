<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-9
 * Time: 上午9:39
 * 咨询进入客户佣金管理
 */

namespace Back\Controller;
use Back\Model\CustomerModel;
use My\MyController;

class MoneyController extends MyController { 
	
	 // 客户佣金列表
	public function index()
	{
		
        $this->pos_tag();  //当前位置标签
        
		if(isset($_GET["id"]) && intval($_GET["id"])>=1)
		{
			$id=intval($_GET["id"]);
			$w["id"]=array("eq",$id); //客户id
		}
		if(isset($_GET["zx"]))
		{
			$w["cid"]=add_slashes($_GET["zx"]);
		}
       
        $money=D("Cus_money");
		if(!isset($w) || empty($w))
		{
			 $count=$money->count();// 查询满足要求的总记录数
		}else
		{
			 $count=$money->where($w)->count();// 查询满足要求的总记录数
		}

        if($count<1 && isset($id))
        {
            $this->error("此客户暂无交易记录");
        }

        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性

		if(!isset($w) || empty($w))
		{
			  $list = $money->order('t desc')
            ->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		else
		{
			  $list = $money->where($w)->order('t desc')
            ->limit($Page->firstRow.','.$Page->listRows)->select();
		}
      
		
		$user=D("User");
	    $all_zx=$user->all_zx();

        $user=D("User");
        $this->assign(array('page'=>$show,'list'=>$list,'count'=>$count,"all_zx"=>$all_zx));//  赋值分页输出
        $this->display(); // 输出模板
	}
	
	
	public function update()
	{
		 $this->pos_tag();  //当前位置标签
         $id=$this->get_id("id");
		 // 判断用户是否存在 并且是自己的客户
		 $cus=D("Customer");
		 $money=$cus->cus_is_zx($id,$this->u_id,"money");
		 if(!isset($money) || $money==NULL)
		 {
			$this->error("客户不存在或您没有操作权限");
		 }
		 		
        $this->temp_session('money');
        $_COOKIE[$this->s_pix.'money']=$money; // 客户原有的总金额
		
		 $info["money"]=$money;
		 $info["cid"]=$this->u_id;
		 $info["id"]=$id;
		 $this->assign("info",$info);
		 $this->display(); // 输出模板
	}
	
	// 执行修改
	 public function execUate()
	 {
		$post=add_slashes($_POST);
		 
        if($post["cid"]!=$this->u_id)
        {
            $this->error("此客户不是您的下线您没有修改权限");
        }
		
		 // 判断用户是否存
		$cus_base=D("Customer");
        $this->cus_is($post["id"],$cus_base); // 判断客户是否存在
		
		if(empty($post["money"]) || (intval($post["money"])===0))
		{
		  redirect('index');
		}
		
		if(!preg_match("/^[\+|\-]\d+$/",$post["money"]))
		{
			$this->error("佣金请输入+\-数字");
		}
		
		$shiwu=M();// 开启事务
        $shiwu->startTrans();
		
		
		// 如果修改佣金，必须添加操作佣金金额说明
		if(empty($post["money_info"]) || strlen($post["money_info"])<2)
		{
			$this->error("修改佣金 必须填写 佣金操作说明");
		}
		if(isset($_COOKIE[$this->s_pix.'money']))
		{
			$data_detailed["money"]=$data_money["jine"]=intval($post["money"])+intval($_COOKIE[$this->s_pix.'money']);
		    $this->temp_session('money');
		}else
		{
			$data_detailed["money"]=$data_money["jine"]=intval($post["money"])+$cus_base->cus_money($id);
		}
		//$this->temp_session('money');
		
		// 佣金表增加一条数据
		$data_money["id"]=$post["id"];
		$data_money["cid"]=$this->u_id;
		$data_money["num"]=intval($post["money"]);
		$data_money["info"]=$post["money_info"];
		$data_money["t"]=date("Y-m-d H:i:s",time());
		$cus_money=D("cus_money");
		$is_money=$cus_money->add($data_money);
		if(!isset($is_money))
		{
			$shiwu->rollback();
			$this->error("修改客户佣金失败");
		}
      
	    // 客户资料表---- 更新佣金字段
		$base_w["id"]=array("eq",$post["id"]);
		$is_base=$cus_base->where($base_w)->save($data_detailed);
		if(!isset($is_base))
		{
			$shiwu->rollback();
			$this->error("修改客户佣金失败");
		}
		
	  	// 客户消息通知表 cus_info---- 通知客户
		$n=$cus_base->cus_is_zx($post["id"],$post["cid"],"n");
		
        $info="<b>[ ".$n." ]</b> 用户，由管理员 <b>".$_COOKIE[$this->s_pix."n"]."</b> 在 <b>".$data_money["t"]."</b> 时间更新佣金 <b>".$data_money["num"]."</b>
		<br/>佣金操作说明: ".$post["money_info"];
		 

        $cusinfo["cid"]=$post["id"];
        $cusinfo["easy"]="<b>[ ".$n." ]</b> 用户佣金 <b>".$data_money["num"]."</b> ";
        $cusinfo["con"]=$info;
        $cus_info=D("Cus_info");
        $cus_info_af=$cus_info->add($cusinfo);
        if(!$cus_info_af)
        {
            $shiwu->rollback();
            $this->error("通知客户信息失败");
        }
		
        $shiwu->commit();
        $shiwu=null;
        unset($shiwu);
        $this->success("更新客户佣金成功","index");
	 }
	
	// 判断get id 是否合法
    private function get_id($id)
    {
        if(!isset($_GET[$id]))
            $this->error("客户不存在或客户信息错误1");
        $id=intval($_GET[$id]);
        if($id<1)
            $this->error("客户不存在或客户信息错误2");
            //$this->redirect('Back/Customer/index','',0); /*nginx 不支持 默认路径会加上 html 后缀*/
        return $id;
    }

}
