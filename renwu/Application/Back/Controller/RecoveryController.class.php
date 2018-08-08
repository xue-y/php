<?php
namespace Back\Controller;
use My\MyController;
class RecoveryController extends MyController { // 回收站

    //回收站首页
    public function index(){

        $this->pos_tag(); //当前位置
        $bu_men=$this->arr_data("bu_men");
        $rw_state=$this->arr_data("rw_state");
        $user=D("user");
        //--- 身份判断
        $is_admin=$this->is_admin($user);
        if(!isset($is_admin)) //------------------------普通用户只可查看自己删除的任务
        {
            $w["u_id"]=array("eq",$this->u_id);
        }
        else                 //------------------------超级管理员可以查看所以的并且可以搜索
        {
            if(isset($_GET) && !empty($_GET))
            {
                $get=add_slashes($_GET);
                $w=$this->time_search($get,$bu_men,$rw_state); // 时间 部门 用户id 状态 搜索

                if(isset($get["key"]) && !empty($get["key"]))
                {
                    $where['tit'] = array("like","%{$get["key"]}%");
                    $u_ids=$user->u_name($get["key"]);
                    $where["u_id"]=array("in",$u_ids);
                    $where['_logic'] = 'or';
                    $w['_complex'] = $where;
                }
                //用户名称或问题标题查询
            }//---------------------搜索
        }
        $problem=D("Problem");
        $w["isdel"]=array("eq",1);

        $count = $problem->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(10)
        $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $problem->where($w)->order('times desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $list=str_slashes($list);
        $this->assign('list',$list);// 赋值数据集$this->assign('page',$show);// 赋值分页输出

        if($count>=1)  // -----------------------如果有数据
        {
            foreach($list as $k=>$v)
            {
                $u_id_arr[]=$v["u_id"];
            }
            $u_id_arr=array_unique($u_id_arr);
            $u_id_arr=implode(",",$u_id_arr);

            $user=D("User");
            $u_info=$user->uid_info($u_id_arr);
            $this->assign(
                array("list"=>$list,"u_info"=>$u_info)
            );
        }
        else
        {
            $show=NULL;
        }
        $this->assign(
            array("bu_men"=>$bu_men,"rw_state"=>$rw_state,"show"=>$show,"count"=>$count,"is_admin"=>$is_admin)
        );
        $this->display();
    }

    //删除任务
    public function del()
    {
        $problem=D("Problem");
        if(isset($_POST["id"]))
        {
            $pid=$_POST["id"];
            $c=count($pid);
        }
        if(isset($_GET["id"]))
        {
            $pid=$_GET["id"];
            $c=1;
        }
        $pid=$this->recov_vei($pid,$problem);

        $del_c=$problem->delete($pid);
        $del_c==$c?$this->success("删除 $c 任务成功","index"):$this->error("删除任务失败");
    }

    //任务还原
    public function restore()
    {
      if((!isset($_GET["id"]) || empty($_GET["id"])) && (!isset($_POST["id"]) || empty($_POST["id"])))
      {
          $this->error("请选择要还原的任务");exit;
      }
      if(isset($_GET["id"]))
      {
          $pro_id=add_slashes($_GET["id"]);
          $pro_id_arr[]=$pro_id;
          $pro_old_c=1;
      }
      if(isset($_POST["id"]))
      {
          $pro_id=add_slashes($_POST["id"]);
          $pro_old_c=count($pro_id);
          if(is_array($pro_id))
          {
              $pro_id_arr=$pro_id;
              $pro_id=implode(",",$pro_id);
          }
      }

      $problem=D("Problem");
      $user=D("user");
      $is_admin=$this->is_admin($user);
      if(isset($is_admin))
      {
          $p_c=$problem->is_task_revo($pro_id);
      }else
      {
          $pro=$problem->is_task_revo($pro_id,$this->u_id);
          foreach($pro as $k=>$v)
          {
            $p_arr[]=$v["id"];
          }
          $p_c=count($p_arr);
      }

       if(isset($_GET) && $p_c<1)
       {
           $this->error("不存在此任务无法还原或<br/>您没有权限还原 [其他用户] 的任务");exit;
       }else  if($pro_old_c!=$p_c)
       {
           $p_arr2=array_diff($pro_id_arr,$p_arr);
           $p_arr2=implode(" , ",$p_arr2);
           $this->error("不存在编号为 $p_arr2  <br/>的任务或您没有权限还原 [其他用户] 的任务","index",10);
       }
        $pro_id=implode(",",$pro_id_arr);
        $w["id"]=array("in",$pro_id);
        $af=$problem->where($w)->setField('isdel','0');
        if(isset($af) && $af==$p_c)
        {
            $this->success("$af 任务还原成功","index");
        }else
        {
            $this->error("任务还原失败");
        }
    }

    /**回收站删除问题表中的任务---数据是否存在
     * @parem $pid 任务ID
     * @parem $problem 问题表
     * @parem $re_type 如果type有值反回ID
     * */
    protected function recov_vei($pid,$problem)
    {
        if(!isset($pid))
        {
            $this->error("您没有选择任务");
        }
        $pro_id=add_slashes($pid);
        $pro_id_c=count($pro_id);
        if(is_array($pro_id))  // git post 获取的所有问题ID
        {
            $pro_id_arr=$pro_id;
            $pro_id=implode(',',$pro_id);
        }

        $user=D("User");
        $is_admin=$this->is_admin($user);

        if(!isset($is_admin)) //普通用户
        {
            $pro=$problem->is_task_revo($pro_id,$this->u_id);
            if(!isset($pro) || empty($pro))
            {
                $this->error("您没有权限操作此任务");
            }

            foreach($pro as $k=>$v)
            {
                $pro_id_arr2[]=$v["id"];
            }
            $pro_id_arr3=array_diff($pro_id_arr,$pro_id_arr2);
            if(!empty($pro_id_arr3))// 如果差集不为空---不是用户自己添加的任务
            {
                $pro_id_arr3=implode(" , ",$pro_id_arr3);
                $this->error("您没有权限操作编号为 $pro_id_arr3 的任务");
            }

        }else  //超级管理员
        {
            $pro=$problem->is_task_revo($pro_id);

            if($pro<1 || $pro!=$pro_id_c)
            {
                $this->error("任务数据出错");
            }
        }
        $task=D("Task"); //任务是否有人执行过
        $task_already=$task->is_pro($pro_id);
        $task_already_id=implode(" , ",$task_already["pro_id"]);
        if($task_already["c"]>0)
        {
            $this->error("编号为 $task_already_id 的任务已有 {$task_already['c']} 人执行，不可操作");
        }
        return $pro_id;
    }

}