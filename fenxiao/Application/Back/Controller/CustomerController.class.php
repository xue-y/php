<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-9
 * Time: 上午9:39
 * 咨询进入客户管理模块
 */

namespace Back\Controller;
use Back\Model\CustomerModel;
use My\MyController;

class CustomerController extends MyController {  // 客户管理

    // 客户列表
    public function index()
    {
        $this->pos_tag();  //当前位置标签
        $cus_base=D("Cus_base");
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
        //    $w['n'] = array("like","%{$get["key"]}%");
        }// 用户名搜索

        if(isset($get["zx"]) && !empty($get["zx"]))
        {
            $w['cid']=array("eq",$get["zx"]);
        }// 咨询搜索

        $user=D("User"); // 取得所有的咨询
        $all_zx=$user->all_zx();

        $w["b.is_del"]=array("eq",0);

        $count = $cus_base->alias("b")->join(' __CUS_DETAILED__ as a ON a.id =b.id')->where($w)->count();//  查询满足要求的总记录数

        $Page = new \Think\Page($count,P_O_C);//  实例化分页类 传入总记录数和每页显示的

        $list = $cus_base->alias("b")->order('t desc')->limit($Page->firstRow.','.$Page->listRows)
            ->field("a.id as id,a.cid,a.n,a.wx,a.is_wx,a.t,a.money,b.phone")
            ->join(' __CUS_DETAILED__ as a ON a.id =b.id')->where($w)->select();

        $show = $Page->show();//  分页显示输出
        $this->assign(array('page'=>$show,'list'=>$list,'count'=>$count,"uid"=>$this->u_id,'all_zx'=>$all_zx));//  赋值分页输出

        $this->display();
    }

    // 添加
    public function add()
    {
        $this->pos_tag();  //当前位置标签
        $this->display();
    }

    //执行添加
    public function execAdd()
    {
        $post=$this->add_slashes($_POST);
        // pc 端操作
        if(empty($post["phone"]) && empty($post["wx"]))
        {
            $this->error("微信号手机号至少填写一个用于用户登录");
        }
        $cus_base=D("Customer");

        if(!$cus_base->create())
        {
            $this->error($cus_base->getError());
        }
        //基本资料表
        if(!empty($post["phone"]))
        {
            if($cus_base->is_phone($post["phone"])>=1)
            {
                $this->error("手机号已被注册,请更换手机号或回收站还原");
            }
            $base["phone"]=$post["phone"];
        }

        if(empty($post["pass"]))
        {
            $post["pass"]=PASS_DE;
        }
        $base["pass"]=pass_md5($post["pass"]);
        $cus_base=M("Cus_base");

        $shiwu=M();// 开启事务
        $shiwu->startTrans();

        $is_base=$cus_base->add($base);
        if(!isset($is_base)) // 返回插入的id 值
        {
            $shiwu->rollback();
            $this->error("添加数据失败");
        }

        // 详细资料表
        $cus_detailed=M("Cus_detailed"); // 表名
        $detailed["wx"]=$post["wx"];
        $detailed["cid"]=$this->u_id;
        $detailed["n"]=$post["n"];
        $detailed["t"]=date("Y-m-d H:i:s",time());
        $detailed["age"]=$post["age"];
        $detailed["sex"]=$post["sex"];
        $detailed["descr"]=$post["descr"];
        $detailed["id"]=$is_base;
        $is_detailed=$cus_detailed->add($detailed);
        // 返回值为1 ？
       /* 1.写入数据库非法返回false.
          2.写入数据库正常返回自增主键值,否则返回1.*/
        if(!isset($is_detailed))
        {
            $shiwu->rollback();
            $this->error("添加数据失败");
        }
        $shiwu->commit(); //提交事务
        $shiwu=null;
        unset($shiwu);
        $this->success("添加客户成功","index");
    }

    // 修改查看客户信息
    public function update()
    {

        $this->pos_tag();  //当前位置标签
        $id=$this->get_id("id");

        $cus=D("Customer");
        $cus_id_info=$cus->cus_id($id);// 判断客户是否存在
		
        if(empty($cus_id_info))
            $this->error("您查看的客户信息错误或不存在");

		// 取得客户的下线个数
		$cus_id_info["sub_num"]=$cus->ke_line_c($id);

        $cus_id_info["id"]=$id;
        // 删除临时 值
        $this->temp_session('phone');
        session('phone',$cus_id_info["phone"]);

        $this->assign("info",$cus_id_info);
        $this->display();
    }

    // 修改客户信息
    public function execUate()
    {
        // 判断用户是否存
        if($this->post_id("cid")!=$this->u_id)
        {
            $this->error("此客户不是您的下线您没有修改权限");
        }
        $id=$this->post_id("id");
        $cus_base=D("Customer");

        $this->cus_is($id,$cus_base); // 判断客户是否存在

        $post=$this->add_slashes($_POST);

        if(!$cus_base->create())
        {
            $this->error($cus_base->getError());
        }
        $data_detailed=$cus_base->create();

        $shiwu=M();// 开启事务
        $shiwu->startTrans();

        // 修改用户基本登录表
        if(!empty($post["phone"])) //如果手机号不为空
        {

            if(!isset($_SESSION[$this->s_pix]['phone']) || $post["phone"]!=$_SESSION[$this->s_pix]['phone'])
            {
                // 修改后的手机号是否与数据库中其他用户的手机号是否重复
                $is_unique=$cus_base->unique_phone($id,$post["phone"]);

                if($is_unique>=1)
                {
                    $this->error("手机已注册请更换,或用户进入回收站");
                }
                $data_base["phone"]=$post["phone"];
            }
            $this->temp_session('phone');
        }

        //密码操作
        if(!empty($post["pass"]) && !empty($post["pass2"]))
        {
            $pass=pass_md5($post["pass"]);
            if($pass!=$cus_base->cus_pass($id))
            {
                $data_base["pass"]=$pass;
            }
        }

        if(isset($data_base) && !empty($data_base))
        {
            $cus_login=D("cus_base");
            $is_base=$cus_login->where("id=$id")->lock(true)->save($data_base);
            if(!isset($is_base))
            {
                $shiwu->rollback();
                $this->error("修改客户手机号或密码失败");
            }
        }

        // 修改用户资料表
        $is_detailed=$cus_base->where("id=$id")->lock(true)->save($data_detailed);

        if(!isset($is_detailed))
        {
            $shiwu->rollback();
            $this->error("修改失败");
        }
        $shiwu->commit();
        $shiwu=null;
        unset($shiwu);
        $this->success("修改客户成功","index");
    }


    // 删除
    public function del()
    {
        $cus=D("Customer");

        if(isset($_GET["id"])) // 删除一个客户
        {
            //判断客户是否存在---并且判断当前管理是否有权删除  查询未删除的用户
            $is_del=$cus->del_cus_one($_GET["id"],$_COOKIE[$this->s_pix.'id'],0);
            if($is_del!=1)
            {
                $this->error("您删除的客户不存在或没有权限");
            }

            // 如果存在未审核消息不可删除 cus_downline"推荐下线表"
            $downline_c=$cus->rec_cus_line(intval($_GET["id"]),$this->u_id);
            if($downline_c>=1)
            {
                $this->error("此客户下有下线还未审核不可删除");
            }

            //删除除单个客户 ---进入回收站
            $w["id"]=array('eq',intval($_GET["id"]));
            $cus_base=D("Cus_base");
            $is_del=$cus_base->where($w)->lock(true)->setField("is_del",1);
            if($is_del==1)
            {
                $this->success("删除客户成功");
            }else
            {
                $this->error("删除客户失败");
            }
        }else if($_POST["id"])  // ------------------------------------删除多个客户
        {
            $post=array_unique(array_filter($_POST["id"]));

            // 如果存在未审核消息不可删除 cus_downline"推荐下线表"
            $ids=$cus->rec_cus_line($post,$this->u_id,1);
            if(!empty($ids))
            {
                $ids=array_unique($ids);
                $ids=implode(",",$ids);
                $this->error("您要删除的客户有推荐的下线您未审核<br/> 客户ID".$ids);
            }

            //并且判断当前管理是否有权删除--多个用户  cus_detailed
            $del_id_arr=$cus->del_cus_multi($post,$this->u_id,0);

            $del_id_arr = array_column($del_id_arr, 'id'); // 二维数组转为一维数组
            $del_count=count($del_id_arr);

            $no_del_id=array_diff($post,$del_id_arr);

            $w["id"]=array("in",implode(",",$del_id_arr));
            $cus_base=D("Cus_base");
            $del_c=$cus_base->where($w)->lock(true)->setField("is_del",1);

            if($del_c==$del_count)
            {
                if(!empty($no_del_id))
                {
                    $this->success("删除成功,编号为 ".implode(" , ",$no_del_id)." 的客户您没有权限删除","index",10);
                }else
                {
                    $this->success("删除成功");
                }
            }else
            {
                $this->error("删除失败");
            }
        }
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

    private function post_id($id)
    {
        if(!isset($_POST[$id]))
            $this->error("客户不存在或客户信息错误1");
       $id=intval($_POST[$id]);
        if($id<1)
            $this->error("客户不存在或客户信息错误2");
        return $id;
    }

} 