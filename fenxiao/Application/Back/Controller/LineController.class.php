<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-10
 * Time: 下午7:35
 * 客户推荐好友---咨询可以查看自己客户下推荐的所有好友，客户只可查看自己推荐的
 */

namespace Back\Controller;


use My\MyController;

class LineController  extends MyController{

    public function index()
    {
        $this->pos_tag();  //当前位置标签
        $cus=D("Line");

        $get=$this->str_slashes($_GET);
        if(isset($get["key"]) && !empty($get["key"]))
        {
         $w['n'] = array("like","%{$get["key"]}%");
        }
        if(isset($get["state"]) && !empty($get["state"]))
        {
            $w['state']=array("eq",$get["state"]);
        }// 状态搜索

        if(isset($get["cid"]) && !empty($get["cid"]) && $get["cid"]!='all')
        {
            $w['cid']=array("eq",$get["cid"]);
        }else if($get["cid"]=="all" && empty($w))
        {
            $w=1;
        }else
        {
            $w["cid"]=array("eq",$this->u_id);
        }// 咨询搜索

        $user=D("User"); // 取得所有的咨询
        $all_zx=$user->all_zx();

        $count = $cus->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $list = $cus->where($w)->field("n,state,id,t,cid")->order("t desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign(array('list'=>$list,'page'=>$show,'count'=>$count,"all_zx"=>$all_zx,"u_id"=>$this->u_id));// 赋值数据集
        $this->display(); // 输出模板

    }

    //审核
    public function censor()
    {
        if(!isset($_GET) || empty($_GET))
        {
            header("Location:index");
            exit;
        }
        $get=add_slashes($_GET);
        //edit--审核  eye 已经审核过的查看
        if(isset($get["type"]) && !empty($get["type"]) && isset($get["id"]) && intval($get["id"])>=1)
        {
            // 验证当前咨询是否有操作客户的权限
            $cus=D("Line");
            $cus_info=$cus->zx_line($this->u_id,$get["id"]);
            if(empty($cus_info))
            {
               $this->error("客户信息错误或不是您的客户");
            }
            $this->pos_tag();
            $this->assign(array("id"=>$get["id"],"type"=>$get["type"],"info"=>$cus_info));
            $this->display(); // 输出模板
        }else
        {
            header("Location:index");
            exit;
        }
    }

    //执行审核
    public function execEnsor()
    {
       // 判断是否有值
        if(!isset($_POST) || count($_POST)<4)
        {
            $this->error("客户信息错误");
        }

        // 是不是自己的客户推荐的
        $cus=D("Customer");
        $post=add_slashes($_POST);
        if($cus->cus_is_zx($post['tid'],$this->u_id)!=1)
        {
            $this->error("此客户(推荐人)不是您的客户，您不可审核");
        }

        // 判断是否审核过
        $line=D("Line");
        $line_state=$line->line_state($post['id'],$this->u_id);
        if($line_state!=1)
        {
            $this->error("此下线客户已经审核过");
        }

        //收集客户信息---- tp 验证手机不起作用可能是 ：控制器名称不是与模型名称对应的原因
        if(($post["state"]==2) && (empty($post["phone"]) || !(preg_match("/1(\d){10}/",$post["phone"]))))
        {
            $this->error("请填写正确的手机号用于用户登录");
        }

        if(($post["state"]==2) && ($cus->is_phone($post["phone"])>=1))
        {
            $this->error("手机号已被注册,请更换手机号或回收站还原");
        }
        if(!$cus->create())
        {
            $this->error($cus->getError());
        }
        $shiwu=M();
        $shiwu->startTrans();  // 开启事务
		
		
		$detailed["descr"]=$downline["descr"]=$post["descr"];
		// 更新下线表
        $downline["s_t"]=date("Y-m-d H:i:s",time());
		$downline["state"]=$post["state"];
		$w["id"]=array("eq",$post['id']);
		$line_af=$line->lock(true)->where($w)->save($downline); // 锁表操作，没有测试
		if(!$line_af)
		{
			$shiwu->rollback();
			$this->error("更新下线客户状态失败");
		}
		
		if($post["state"]==2) // 如果通过审核 将客户推荐下线人的数据写入数据库
        {

			// 客户登录表
			$base["pass"]=pass_md5(PASS_DE);
			$base["phone"]=$post["phone"];
			$cus_base=D("Cus_base");
			$cus_base_af=$cus_base->add($base);
			if(!$cus_base_af)
			{
				$shiwu->rollback();
				$this->error("新增用户失败");
			}
	
			//客户资料表
			$detailed=$cus->create();
			$detailed["id"]=$cus_base_af;
			$detailed["tid"]=$post["tid"];
			$detailed["cid"]=$this->u_id;
			$detailed["t"]=$downline["s_t"];
			$cus_base_af2=$cus->add($detailed);
			if(!$cus_base_af2)
			{
				$shiwu->rollback();
				$this->error("新增用户资料失败");
			}

            $state="通过";
        }
        if($post["state"]==3)
        {
            $state="未通过";
        }
		// 客户消息通知表 self_cus_info
        $info="<b>您推荐的[ ".$post["n"]." ]</b> 用户，由管理员 <b>".$_COOKIE[$this->s_pix."n"]."</b> 在 <b>".$downline["s_t"]."</b> 时间 <b>".$state."</b> 审核
		<br/> 审核说明: ".$downline["descr"];

        $cusinfo["cid"]=$post["tid"];
        $cusinfo["easy"]="<b>您推荐的[ ".$post["n"]." ]</b> 用户 <b>".$state."</b> 审核";
        $cusinfo["con"]=$info;
        $cus_info=D("Cus_info");
        $cus_info_af=$cus_info->add($cusinfo);
        if(!$cus_info_af)
        {
            $shiwu->rollback();
            $this->error("通知客户信息失败");
        }

        // 咨询消息统计表
        $user_fj=D("User_fj");
        $user_meg=$user_fj->where("id=$this->u_id")->setDec('meg');
        if(!$user_meg)
        {
            $cid_cus=F($this->uid."_cus");
            if(isset($cid_cus))
            {
                $cid_cus-=1;
                F($this->uid."_cus",$cid_cus);
            }else
            {
                F($this->uid."_cus",-1);
            }
        }
        $shiwu->commit();
        $this->success("审核完成","index");
    }


} 