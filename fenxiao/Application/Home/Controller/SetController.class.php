<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-14
 * Time: 下午3:40
 *  客户设置
 */

namespace Home\Controller;
use My\HwxController;

class SetController extends HwxController{

    // 设置页面
    public function index()
    {
        $this->display();
    }

    // 基本信息、 佣金、 下线 、消息个数
    public function cusBase()
    {
        $cus=D("Customer");

        $info=$cus->base($this->uid);
        // 客户推荐下线个数单独统计---客户资料表在客户添加删除下线时没有更新
        $info["sub_num"]=D("Line")->cus_sub_num($this->uid);;
        $info["id"]=$this->uid;
        $info["history"]=CONTROLLER_NAME.FEN_FU.ACTION_NAME;

        if(!empty($info))
        {
            $this->assign("info",$info);
        }else
        {
            $this->error("请重新登录,信息错误");
        }
        $this->display();
    }

    // 执行修改基本信息
    public function execBase()
    {
        if(isset($_POST) && !empty($_POST))
        {
            if(add_slashes($_POST["id"])!=$this->uid)
            {
                $this->error("用户信息错误");
            }

            $cus=D("Customer");
            if(!$cus->create())
            {
               $this->error($cus->getError());
            }

            $w["id"]=array("eq",$this->uid);

            $post=add_slashes($_POST);

            if(!$cus->where($w)->save($post))
            {
                $this->error("您没有修改或修改失败");
            }else
            {
                $this->success("修改成功",'cusBase');
            }
        }else
        {
            header("Location:index");exit;
        }
    }

    //修改查看手机号
    public function phone()
    {
        $phone=$this->byIdField("phone");
        if(!isset($phone) || empty($phone))
        {
            $this->error("您的信息错误,请刷新页面或退出重新登录");
        }
        $this->assign(array("id"=>$this->uid,"phone"=>$phone));
        $this->display();
    }

    // 执行修改客户手机号
    public function execPhone()
    {
        if(!isset($_POST)|| empty($_POST["phone"]))
        {
            header("Location:index");exit;
        }

       /* if(add_slashes($_POST["id"])!=$this->uid)
        {
            $this->error("用户信息错误");
        }*/ // 可以省略验证

        // 验证手机格式
        $phone=add_slashes($_POST["phone"]);
        if(!preg_match("/^1(\d){10}$/",$phone))
        {
           $this->error("请输入正确的手机号");
        }

        //验证手机号是否注册
        $cus=D("Customer");

        if($cus->unique_phone($this->uid,$phone)>=1)
        {
            $this->error("此手机号已经注册过请更换手机号或联系客服");
        };

        $cus_base=D("Cus_base");
        $w["id"]=array("eq",$this->uid);
        $data["phone"]=$phone;
        if(!$cus_base->where($w)->save($data))
        {
            $this->error("您没有修改或修改失败");
        }else
        {
            cookie('phone',$post["phone"],USER_LOGIN_T,'/');
          //  $_SESSION[$this->s_pix.'phone']=$phone;
            cookie('token',pass_md5(sha1($this->uid).$phone),USER_LOGIN_T,'/');
          //  $_SESSION[$this->s_pix.'token']=pass_md5(sha1($this->uid).$phone);

            $this->success("修改成功","index");
        }

    }

    // 进入修改密码页面
    public function pass()
    {
       // $this->assign("id",$this->uid);
        $history=CONTROLLER_NAME.FEN_FU.'index';
        $this->assign("history",$history);
        $this->display();
    }

    // 执行修改密码
    public function execPass()
    {

        if(!isset($_POST) || empty($_POST))
        {
            header("Location:index");exit;
        }
        $post=add_slashes($_POST);
        if(empty($post["old_pass"]) || empty($post["pass"]) || empty($post["pass2"]))
        {
            header("Location:index");exit;
        }

        if($post["pass"] == $post["old_pass"])
        {
            $this->error("新密码与原密码一致");
        }
        if(($post["pass"]!=$post["pass2"]))
        {
            $this->error("新密码与确认密码不一致");
        }
        /*if(!$cus->create())
        {
            $this->error($cus->getError());   // 所有的正则验证通过后 返回 false 错误值为 null
        }*/
        // 验证原密码是否正确
        $cus_base=D("Cus_base");
        $old_pass=$cus_base->field("pass")->find($this->uid);
        if(!isset($old_pass) || empty($old_pass))
        {
            $this->error("原密码错误");
        }

        $pass["pass"]=pass_md5($post["pass"]);
        if($old_pass===$old_pass)
        {
            $this->success("修改成功","index");
        }
        $w["id"]=array("eq",$this->uid);
        $af=D("Cus_base")->where($w)->save($pass);
        if(!$af)
        {
            $this->error("修改失败");
        }else
        {
            $this->success("修改成功","index");
        }
    }

    /*取消微信绑定
     * */
    public function qxBingwx()
    {
        if($this->uid!=intval($_GET["id"]))
        {
            $this->error("不存在此用户");
        };

        $token=pass_md5(sha1($this->uid).cookie("phone"));
        if($token!==cookie(token))
        {
            $this->error("不存在此用户");
        }
        $cus=D("Customer");

        $affected=$cus->quBingwx($this->uid);
        if(!$affected)
        {
            $this->error("取消失败");
        }else
        {
            $this->success("取消成功","cusBase");
        };
    }

    /** 根据当前客户id 读取用户其他单个字段信息
     * @parem $field 需要获取的字段
     * */
    private function byIdField($field)
    {
        $cus=D("Cus_base");
        return $cus->getFieldById($this->uid,$field);
    }

} 