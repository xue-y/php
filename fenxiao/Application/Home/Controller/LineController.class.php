<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-12
 * Time: 上午10:09
 */

namespace Home\Controller;
use My\WxController;
use Think\Controller;

class LineController extends  WxController{

    private $uid_id_phone;//客户修改自己提交下线 修改手机号--sessiong 名称标识

    //推荐列表
    public function index()
    {
        $cus=D("Line");
        $w["tid"]=array("eq",$this->uid);
        $count = $cus->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $list = $cus->where($w)->field("n,state,id")->order("t desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign(array('list'=>$list,'page'=>$show,'count'=>$count));// 赋值数据集
        $this->display(); // 输出模板
    }

    //添加
    public function add()
    {
        $this->display();
    }

    //执行添加
    public function execAdd()
    {

        if(!isset($_POST) || empty($_POST))
        {
            $this->error("表单内容不得为空");
        }
        S($this->uid."_line_num",null);
        // 限制用户一天最多推荐不得超过20 个
        $line_num=S($this->uid."_line_num");
        if(isset($line_num) && $line_num>LINE_NUM)
        {
            $this->error("一天内添加的用户不得超过".LINE_NUM."个");
        }

        $post=add_slashes($_POST);
        if(empty($post["phone"]) && empty($post["wx"]))
        {
            $this->error("微信号手机号至少填写一个");
        }

        $cus=D("Line");
        if(!$cus->create())
        {
            $this->error($cus->getError());
        }
        $form=$cus->create();
        $form["t"]=date("Y-m-d H:i:s");
        $form["tid"]=$this->uid;
        $customer=D("Customer");
        $cid=$customer->zx_id($this->uid);
        $form["cid"]=$cid;
        // 写入推荐表
        $af=$cus->add($form);
        if(!$af)
        {
            $this->error("添加推荐失败");
        }

        $line_num=S($this->uid."_line_num"); // 推荐人数时间验证 24 推荐不得超过20个
        if(!isset($line_num))
        {
            S($this->uid."_line_num",1,3600*24);
         }
        else
         {
            $line_num+=1;
            S($this->uid."_line_num",$line_num,3600*24);
         }

        // 通知咨询表		
        $user_fj=D("User_fj");
		$cid_cus=F($cid."_cus");
		
		if(isset($cid_cus) && (intval($cid_cus)!==0))
		{
			$meg=1+intval($cid_cus);	
		}else
		{
			$meg=1;	
		}
		
		$zx=$user_fj->where("id=$cid")->setInc('meg',$meg); // 如果更新失败写入文件
	
        if(!isset($zx) || $zx!=1)
        {
		    F($cid."_cus",$meg);
        }else		
		{
			F($this->uid."_cus",NULL);	
		}
        $this->success("添加推荐成功","index");
    }

    //修改
    public function update()
    {
        $id=$this->get_id("id");
        if(!isset($id))
        {
            $this->error("不存在此用户");
        }

        $cus=D("Line");

        $line_info=$cus->line_info($this->uid,$id);

        if(empty($line_info))
        {
            $this->error("不存在此用户");
        }

        $line_info["id"]=$id;
        $this->assign("line_info",$line_info);
        $this->display();
    }

    //执行修改
    public function execUate()
    {
        $id=$this->post_id("id");
        if(!$id)
        {
            $this->error("不存在此用户");
        }
        $post=add_slashes($_POST);

        // 判断是否存在是用户
        $cus=D("Line");
        if($cus->line_is($this->uid,$id)!=1)
        {
            $this->error("用户信息错误,或用户已经经过咨询审核过不可修改");
        }
        if(empty($post["phone"]) && empty($post["wx"]))
        {
            $this->error("微信号手机号至少填写一个");
        }
        //收集数据
        if(!$cus->create())
        {
            $this->error($cus->getError());
        }
        $form=$cus->create();

        // 判断手机号是否重复
        if(!empty($post["phone"]))
        {
            if($cus->line_phone($id,$post["phone"])>=1)
                {
                    $this->error("此手机号已经推荐过了");
                }
            $form["phone"]=$post["phone"];
        }
        // 执行修改---锁表
        if($cus->where("id=$id")->lock(true)->save($form))
        {
            $this->success("修改成功","index");
        }else
        {
            $this->error("修改成功","index");
        }
    }

    //删除
    public function del() //只可删除未审核或没有通过审核的
    {
        //------------------------------------------删除单条数据
        if(isset($_GET["id"]))
        {
            $id=$this->get_id("id");
            if(!$id)
            {
                $this->error("不存在此用户");
            }
            // 判断是否存在是用户
            $cus=D("Line");

            if($cus->line_iss($this->uid,$id)!=1)
            {
                $this->error("用户信息错误,或用户已经通过咨询审核过不可删除");
            }

            if($cus->delete($id))
            {
              $this->up_user_meg(1);
              $this->success("删除成功");
            }else
            {
                $this->error("删除失败");
            }
        } ///------------------------------------------删除单条数据
        // -----------------------------------------------删除多条数据
        if(isset($_POST["id"]))
        {
            $ids=array_unique(array_filter($_POST["id"]));
            $old_ids_c=count($ids);
            if(empty($ids))
            {
                $this->redirect("index",'',0);
                exit;
            }

            $w["id"]=$w2["id"]=array("in",implode(",",$ids));
            $w["tid"]=$w2["tid"]=$this->uid;
            $w["state"]=array("neq",2);
            $line=D("Line");

            $id_c=$line->where($w)->delete();  // 删除 客户的

            // 更新咨询通知消息个数
            $w2["state"]=array("eq",1);
            $user_meg=$line->where($w2)->count();

            if($user_meg>=1)
            {
                $this->up_user_meg($user_meg);  // 更新咨询通知消息
            }

            if($old_ids_c!=$id_c)
            {
                $this->success("已删除未通过审核与未审核<br/> 审核过的不可删除","index");
            }else
            {
                $this->success("删除成功");
            }
        }
    }

     private function up_user_meg($meg_num)
     {
         $customer=D("Customer");
         $cid=$customer->zx_id($this->uid); // 咨询id

         $user_fj=D("User_fj");
		 $cid_cus=F($this->uid."_cus");
		 if(isset($cid_cus) && (intval($cid_cus)!==0))
		 {
			 $cid_cus-=$meg_num;
			 
		 }else
		 {
			$cid_cus=$meg_num;
		 }
         $is_up=$user_fj->where("id=$cid")->setDec('meg',$cid_cus);
         if(!$is_up)
         {
			 F($this->uid."_cus",$cid_cus);
         }else
		 {
			 F($this->uid."_cus",NULL);
		 }
     }
} 