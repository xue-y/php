<?php
namespace Back\Controller;
use My\MyController;
class TaskController extends MyController { // 任务控制器

    public function index(){

        $this->task_nimble(); //任务的快捷操作
        $bu_men=$this->arr_data("bu_men");
        $rw_state=$this->arr_data("rw_state");
        $user=D("user");
        if(isset($_GET) && !empty($_GET))
        {
            $get=$this->add_slashes($_GET);
            $w=$this->time_search($get,$bu_men,$rw_state); // 时间 部门 用户id 状态 搜索

            if(isset($get["p_id"]) && !empty($get["p_id"]))
            {
                $w["id"]=array("in",$get["p_id"]); // 当前用户从个人信息处查看 待执行的任务
            }

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

        $problem=D("Problem");
        $w["isdel"]=array("eq",0);

        $count = $problem->where($w)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(10)
        $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $problem->where($w)->order('times desc')->limit($Page->firstRow.','.$Page->listRows)->select();

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
            array("bu_men"=>$bu_men,"rw_state"=>$rw_state,"show"=>$show,"count"=>$count)
        );
        $this->display();
    }

    //添加任务
    public function add()
    {
        $this->pos_tag();  //当前位置标签
        $this->display();
    }

    // 执行添加任务
    public function  execAdd()
    {
        $problem=D("Problem");

        if($problem->create())
        {
            $post=$this->add_slashes($_POST);

            if(preg_match('/^[\s\S]{5,1000}$/i',$post["descr"],$con) && !empty($con))
            {
                $data["descr"]=$post["descr"];
            }

            $data['u_id']=$this->u_id;
            $data["tit"]=$post["tit"];

            $data['times']=time();
            $fp = fopen(LOCK_F, "r"); //锁文件
            if(flock($fp,LOCK_EX | LOCK_NB))
            {
                $last=$problem->add($data);
                flock($fp,LOCK_UN);
            }else{
                $this->error("系统正忙，请稍后再试");
            }
            fclose($fp);

            isset($last)?$this->success("添加任务成功","index"):$this->error("添加任务失败");
        }else
        {
            $this->error($problem->getError());
        }
    }

    //修改任务
    public function update()
    {
        $problem=D("Problem");
        $rw_state=$this->arr_data("rw_state");
        $pro=$this->update_vei($_GET["id"],$problem);
        $pro=$this->str_slashes($pro);
        $this->pos_tag();  //当前位置标签
        $this->assign(array("pro"=>$pro,"state"=>$rw_state));
        $this->display();
    }

    //执行修改任务
    public function execUate()
    {
        if(!isset($_POST) || empty($_POST))
        {
            $this->error("修改任务信息错误");
        }
        $post=$this->add_slashes($_POST);

        $problem=D("Problem");

        if(isset($post["t_id"]) && isset($post["t_state"]) && isset($post["p_id"]))// ----------------------验证反馈
        {
           $task=D("Task");
           $t_state=intval($post["t_state"]);//-----------------修改任务表
           if($t_state==0) // 未通过验证
           {
               $af=$task->save_state($post["t_id"],2,$this->u_id);
               if(!isset($af))$this->error("反馈验证失败");
           }
           if($t_state==1) //验证通过
           {
                $p_af=$problem->save_state($post["p_id"],$this->u_id);//-----------------修改问题表 --问题已解决
              // if($p_af!=1)$this->error("问题反馈信息验证失败");
               //如果提交任务人员已经验证过其他人员的解决方案，并且通过；在验证另外人员的解决方案是 $p_af ==0
                $af=$task->save_state($post["t_id"],1,$this->u_id);     //-----------------修改任务表--任务呢
                if($af!=1)$this->error("反馈信息验证失败");
           }
            $user=D("User");        //-----------------------更新用户表meg 字段
            $u_af=$user->meg_ver($this->u_id);
            if($u_af==1)
            {
                $this->success("验证完成",U("Personal/megList","id=$this->u_id"));
            }else
            {
                $this->error("验证失败");
            }
        }
        else
        {
            $pro=$this->update_vei($post["id"],$problem);

           if(!$problem->create())
           {
               $this->error($problem->getError());
           }
           $post=$this->add_slashes($_POST);
            if(!empty($post["tit"]) && $post["tit"]!=$pro["tit"])
            {
                $data["tit"]=$post["tit"];
            }
            if(!empty($post["descr"]) && $post["descr"]!=$pro["descr"])
            {
                $data["descr"]=$post["descr"];
            }
            if($post["state"]!=$pro["state"])
            {
                $data["state"]=$post["state"];
            }
            if(count($data)<1)
            {
                $this->success("您没有修改","index");exit;
            }

            $user=D("User");
            $is_admin=$this->is_admin($user);
            $w["id"]=array("eq",$pro["id"]);
            if(!isset($is_admin))
            {
                $w["u_id"]=array("eq",$this->u_id);
            }
            $affected=$problem->where($w)->save($data);
            $affected==1?$this->success("修改成功","index"):$this->error("修改失败");
        }
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
        $pid=$this->update_vei($pid,$problem,1);
        $w["id"]=array("in",$pid);
        $del_c=$problem->where($w)->setField('isdel',1);
        $del_c==$c?$this->success("删除任务成功","index"):$this->error("删除任务失败");
    }

    //进入执行任务
    public function ute()
    {
      if((!isset($_GET["id"]) || (intval($_GET["id"])<1)) && (!isset($_POST["see"])))
      {
              $this->error("请选择任务");
      }
      //判断任务是否存在
       if(isset($_GET["id"]))
           $pro_id=$this->add_slashes($_GET["id"]);
        else
            $pro_id=$this->add_slashes($_POST["id"]);

        $problem=D("Problem");
        $pro=$problem->is_task($pro_id);

        if(!isset($pro) || empty($pro))
        {
            $this->error("不存在此任务");
        }
        $pro=$this->str_slashes($pro[0]);//二维数组转一维

        $this_u=0;
        if($pro["u_id"]==$this->u_id)  // 如果是自己提交的任务只可查看，不可执行--可修改
        {
            $this_u=1;
        }

        $this->pos_tag();  //当前位置标签
        $rw_state=$this->arr_data("rw_state");
        $task_state=$this->arr_data("task_state");

        //查询执行过此任务的人员信息---ajax---任何人都可查看
        if(isset($_POST["see"]) && $_POST["see"]=="task")
        {
            $d=D();
            $p_id=$this->add_slashes($_POST["id"]);
            $sql="select u.u_name,u.bumen,t.times,t.state,t.plan from __USER__ as u,__TASK__ as t where t.p_id=$p_id and t.u_id=u.id";
            $task_info=$d->query($sql);
            $c=count($task_info);
            if($c>0)
            {
               $task_info=$this->str_slashes($task_info);
                $sum=array();
                foreach($task_info as $v)
                {
                    $sum[]=$v["u_name"];
                }
                $sum=array_unique($sum);
                $task_info[$c]["sum"]=count($sum);

                foreach($task_info as $k=>$v)
                {
                    $task_info[$k]["state"]=$task_state[$v["state"]];
                    $task_info[$k]["times"]=date("Y-m-d H:i:s",$v["times"]);
                }
                $this->ajaxReturn($task_info);
            }else
            {
                $this->ajaxReturn($c);
            }
        };

       //获取提交问题人的信息
        $user=D("User");
        $uid_info=$user->uid_info($pro["u_id"]);
        $pro["bumen"]=$uid_info[0]["bumen"];
        $pro["u_name"]=$uid_info[0]["u_name"];

        $this->assign(array("pro"=>$pro,"state"=>$rw_state,"this_u"=>$this_u));

       $this->display();
    }

    //执行任务
    public function execUte()
    {
        $post=$this->add_slashes($_POST);
        if(!isset($post["state"]))
        {
            $this->error("请勾选执行任务");exit;
        }
        if(!preg_match('/^[\s\S]{5,1000}$/i',$post["content"],$con) || empty($con))
        {
            $this->error("解决方案必填5---300个字之间");
        }

        $problem=D("Problem");
        $pro=$problem->is_task_state($post["id"]);
        if(!isset($pro) || empty($pro))
        {
            $this->error("不存在此任务");exit;
        }

        if($pro["u_id"]==$this->u_id)
        {
            $this->error("自己提交的任务自己不可执行");exit;
        }

        //任务表添加数据
        $task=D("Task");
        if(!$task->create())
        {
            $this->error($task->getError());exit;
        }

        $pro_plan=$post["content"];
        $last=$task->u_add_task($this->u_id,$post["id"],$pro_plan);
        if(!isset($last))
            $this->error("$this->u_id 执行任务失败");

        //用户表更新信息字段
        $user=D("User");
        $user_meg=$user->user_meg($pro["u_id"]);
        $user_meg==1?$this->success("执行任务成功,请等待提交任务人员回馈","index"):$this->error("执行任务失败");
    }

    //任务统计
    public function count()
    {
        $this->pos_tag();
        $bu_men=$this->arr_data("bu_men");
        $task_state=$this->arr_data("task_state");

        $task=D("Task");
        $task_index=array_keys($task_state);
        if(isset($_GET) && !empty($_GET))
        {
            $get=$this->add_slashes($_GET);
            $w=$this->time_search($get,$bu_men,$task_index);
        }

       if(!isset($w) || count($w)<1)
       {
           $count=$task->count();// 查询满足要求的总记录数
           $Page=new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
           $list = $task->order('times desc')->field("p_id,u_id,times,state")->limit($Page->firstRow.','.$Page->listRows)->select();
       }else
       {
           $count=$task->where($w)->count();// 查询满足要求的总记录数
           $Page=new \Think\Page($count,P_O_C);// 实例化分页类 传入总记录数和每页显示的记录数(25)
           $list = $task->where($w)->order('times desc')->field("p_id,u_id,times,state")->limit($Page->firstRow.','.$Page->listRows)->select();
       }
        $list=$this->str_slashes($list);
        if($count>=1)
        {
            foreach($list as $k=>$v)
            {
                $pro_arr[]=$v["p_id"];
                $u_arr[]=$v["u_id"];
                $list[$k]["state"]=$task_state[$v["state"]];
            }
            $user=D("User");
            $u=$user->uid_info($u_arr);

            $pro_arr=implode(",",$pro_arr);
            $problem=D("Problem");
            $pro=$problem->pro_info($pro_arr,"id,tit");
            $show = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $this->assign(array("list"=>$list,"pro"=>$pro,"u"=>$u));
        }else
        {$show=NULL;}

        $this->assign(array("bu_men"=>$bu_men,"state"=>$task_state,"show"=>$show,"count"=>$count));
        $this->display();
    }

}