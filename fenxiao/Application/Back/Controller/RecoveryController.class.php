<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-10
 * Time: 下午7:18
 * 客户回收站---咨询删除后的客户信息
 */

namespace Back\Controller;
use My\MyController;

class RecoveryController extends MyController{

    // 客户回收站列表
    public function index()
    {
        $this->pos_tag();  //当前位置标签
        $cus_base=D("cus_base");
        $get=$this->str_slashes($_GET);

        if(isset($get["t_start"]) && !empty($get["t_start"]) && !isset($get["t_end"]))
        {
            $t_start=$get["t_start"];
            $w["t"]=array("egt",$t_start);
        }// 开始时间搜索

        if(isset($get["t_end"]) && !empty($get["t_end"]) && !isset($get["t_start"]))
        {
            $t_end=$get["t_end"];
            $w["t"]=array("elt",$t_start);
        }// 结束时间

        if(isset($get["t_end"]) && !empty($get["t_end"]) && isset($get["t_start"]) && !empty($get["t_start"]))
        {
            $t_start=$get["t_start"];
            $t_end=$get["t_end"];
            $w["t"]=array('between',"$t_start,$t_end");
        }// 时间区间


        if(isset($get["key"]) && !empty($get["key"]))
        {
           $w['_string'] = "b.phone LIKE '%{$get["key"]}%'  OR  a.n LIKE '%{$get["key"]}%'";
        }// 用户名 手机号搜索

        if(isset($get["zx"]) && !empty($get["zx"]))
        {
            $w['cid']=array("eq",$get["zx"]);
        }// 咨询搜索

        $user=D("User");
        $all_zx=$user->all_zx();

        $w["b.is_del"]=array("eq",1);

        $count = $cus_base->alias("b")->join(' __CUS_DETAILED__ as a ON a.id =b.id')->where($w)->count();//  查询满足要求的总记录数

        $Page = new \Think\Page($count,P_O_C);//  实例化分页类 传入总记录数和每页显示的

        $list = $cus_base->alias("b")->order('t desc')->limit($Page->firstRow.','.$Page->listRows)
            ->field("a.id as id,a.cid,a.n,a.wx,a.is_wx,a.t,a.money,b.phone")
            ->join(' __CUS_DETAILED__ as a ON a.id =b.id')->where($w)->select();

        $show = $Page->show();//  分页显示输出
        $this->assign(array('page'=>$show,'list'=>$list,'count'=>$count,"uid"=>$this->u_id,'all_zx'=>$all_zx));//  赋值分页输出

        $this->display();
    }

    // 还原数据
    public function restore()
    {
        $cus=D("Customer");

        if(isset($_GET["id"])) // 一个客户
        {
            //判断客户是否存在---并且判断当前管理是否有权删除
            $is_del=$cus->del_cus_one($_GET["id"],$this->u_id,1);
            if($is_del!=1)
            {
                $this->error("您操作的客户不存在或没有权限");
            }

            //还原除单个客户--- 进入客户列表
            $w["id"]=array('eq',intval($_GET["id"]));
            $cus_base=D("Cus_base");
            $is_del=$cus_base->where($w)->setField("is_del",0);
            if($is_del==1)
            {
                $this->success("还原客户成功");
            }else
            {
                $this->error("还原客户失败");
            }
        }else if($_POST["id"])  // ------------------------------------还原多个客户
        {
            $post=array_unique(array_filter($_POST["id"]));

            $del_id_arr=$cus->del_cus_multi($post,$this->u_id,1);

            $del_id_arr = array_column($del_id_arr, 'id'); // 二维数组转为一维数组
            $del_count=count($del_id_arr);

            $no_del_id=array_diff($post,$del_id_arr);

            $w["id"]=array("in",implode(",",$del_id_arr));
            $cus_base=D("Cus_base");
            $del_c=$cus_base->where($w)->setField("is_del",0);

            if($del_c==$del_count)
            {
                if(!empty($no_del_id))
                {
                    $this->success("还原成功,编号为 ".implode(" , ",$no_del_id)." 的客户您没有权限还原","index",10);
                }else
                {
                    $this->success("还原成功");
                }
            }else
            {
                $this->error("还原失败");
            }
        }
    }

    //删除
    public function del()
    {
        // 判断id 是否合法
        $id=I('get.id',0,'intval');
        if(isset($id) &&  $id>=1)
        {
            $id=intval($_GET["id"]);
            $this->del_one($id);   // 删除单个用户
        }else if(isset($_POST["id"]))
        {
            // 删除多个用户
            $ids=array_unique(array_filter($_POST["id"]));
            if(count($ids)==1)
            {
                $this->del_one($ids[0]);   // 删除单个用户
            }
            $cus=D("Customer");

            $ids=$cus->cusid_uid($ids,$this->u_id,1); //客户是不是当前管理员的 并且客户存在

            if(count($ids)<1)
            {
                $this->error("您删除的客户不存在或不是您的客户");
            }
            if(count($ids)==1)
            {
                $this->del_one($ids[0]);   // 删除单个用户
            }else
            {
                $shiwu=M();// 开启事务
                $shiwu->startTrans();

                // cus_downline"推荐下线表" 推荐人是当前用户的 推荐人字段置为null
                $ids=implode(",",$ids);
                $cus_tid=$cus->rec_cus_tid($ids,$this->u_id,"in");
                if($cus_tid>=1)  // 如果当前要删除的客户曾经推荐过人
                {
                    $w["tid"]=array("in",$ids);
                    $w["cid"]=array("eq",$this->u_id);
                    $data["tid"]=0;
                    $af=$cus->table("__CUS_DOWNLINE__")->where($w)->save($data);
                    if(!isset($af))
                    {
                        //   $this->error("删除失败");
                        $info=$_COOKIE[$this->s_pix."n"]."管理员 删除客户 ".$ids." 时| cus_downline tid推荐人字段置为0 失败";
                        $this->write_log($info);
                        //   $shiwu->rollback();
                    }

                    //cus_detailed  客户资料表 推荐人是当前用户的 推荐人字段置为null
                    $cus_state=$cus->rec_cus_state($ids,$this->u_id,"in");
                    if($cus_state>=1)    // 如果当前要删除的客户过去推荐的人已经通过审核
                    {
                        $af=$cus->where($w)->sava($data);
                        if(!isset($af))
                        {
                            //  $this->error("删除失败");
                            $info=$_COOKIE[$this->s_pix."n"]."管理员 删除客户".$ids." 时| cus_detailed tid推荐人字段置为0 失败";
                            $this->write_log($info);
                            //   $shiwu->rollback();
                        }
                    }
                }
                //---------------------------------如果当前要删除的客户曾经推荐过人结束

                // 删除 cus_base 表中数据 cus_detailed 表中数据
                $cus_base=D("Cus_base");
                $cus_base_w["is_del"]=array("eq",1);
                $is_del=$cus_base->where($cus_base_w)->lock(true)->delete($ids);
                if(!isset($is_del))
                {
                    $shiwu->rollback();
                    $this->error("删除失败");
                }

                // 佣金表数据 cus_money
                $money_c=$cus->rec_cus_money($ids,$this->u_id,"in");
                if($money_c>=1)
                {
                    $w["id"]=array("in",$ids);
                    $w["cid"]=array("eq",$this->u_id);
                    $is_money_del=D("Cus_money")->where($w)->delete();
                    if($is_money_del!=$money_c)
                    {
                        $shiwu->rollback();
                        $this->error("删除客户佣金数据失败");
                    }
                }

                //cus_info"客户消息通知表"
                $info_c=$cus->rec_cus_info($ids);
                if($info_c>=1)
                {
                    $info_d=D("Cus_info")->where("in $ids")->delete();
                    if($info_d!=$info_c)
                    {
                        $shiwu->rollback();
                        $this->error("删除客户信息数据失败");
                    }
                }
                $shiwu->commit(); // 提交事务
                $shiwu=null;
                unset($shiwu);
                $this->success("删除成功","index");
            } //----------------------------------------------------删除多条
        }else
        {
            redirect("index"); // 直接跳转回去
        }
    }

    // 删除单个用户
    private function del_one($id)
    {
        // 判断用户是否存在 cus_base 表 并且是自己的客户
    //    $id=intval($_GET["id"]);
        $cus=D("Customer");
        $is_cus=$cus->cusid_uid($id,$this->u_id);

        if($is_cus!=1)
        {
            $this->error("客户不存在或不是您的客户");
        }

        $shiwu=M();// 开启事务
        $shiwu->startTrans();

        // cus_downline"推荐下线表" 推荐人是当前用户的 推荐人字段置为null
        $cus_tid=$cus->rec_cus_tid($id,$this->u_id);
        if($cus_tid>=1)  // 如果当前要删除的客户曾经推荐过人
        {
            $w["tid"]=array("eq",$id);
            $w["cid"]=array("eq",$this->u_id);
            $data["tid"]=0;
            $af=$cus->table("__CUS_DOWNLINE__")->where($w)->save($data);
            if(!isset($af))
            {
                //   $this->error("删除失败");
                $info=$_COOKIE[$this->s_pix."n"]."管理员 删除客户".$id." 时| cus_downline tid推荐人字段置为0 失败";
                $this->write_log($info);
                //  $shiwu->rollback();
            }

            //cus_detailed  客户资料表 推荐人是当前用户的 推荐人字段置为null
            $cus_state=$cus->rec_cus_state($id,$this->u_id);
            if($cus_state>=1)    // 如果当前要删除的客户过去推荐的人已经通过审核
            {
                $af=$cus->where($w)->sava($data);
                if(!isset($af))
                {
                    //  $this->error("删除失败");
                    $info=$_COOKIE[$this->s_pix."n"]."管理员 删除客户".$id." 时| cus_detailed tid推荐人字段置为0 失败";
                    $this->write_log($info);
                    //$shiwu->rollback();
                }
            }
        }

        // 删除 cus_base 表中数据 cus_detailed 表中数据
        $cus_base=D("Cus_base");
        $cus_base_w["is_del"]=array("eq",1);
        $is_del=$cus_base->where($cus_base_w)->lock(true)->delete($id);
        if(!isset($is_del))
        {
            $shiwu->rollback();
            $this->error("删除失败");
        }

        // 佣金表数据 cus_money
        $money_c=$cus->rec_cus_money($id,$this->u_id);
        if($money_c>=1)
        {
            $w["id"]=array("eq",$id);
            $w["cid"]=array("eq",$this->u_id);
            $is_money_del=D("Cus_money")->where($w)->delete();
            if($is_money_del!=$money_c)
            {
                $shiwu->rollback();
                $this->error("删除客户佣金数据失败");
            }
        }

        //cus_info"客户消息通知表"
        $info_c=$cus->rec_cus_info($id);
        if($info_c>=1)
        {
            $info_d=D("Cus_info")->where("cid=$id")->delete();
            if($info_d!=$info_c)
            {
                $shiwu->rollback();
                $this->error("删除客户信息数据失败");
            }
        }
        $shiwu->commit(); // 提交事务
        $shiwu=null;
        unset($shiwu);
        $this->success("删除成功","index");
    }


}

