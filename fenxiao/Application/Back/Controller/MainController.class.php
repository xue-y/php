<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17-9-17
 * Time: 下午1:18
 * 后台主页
 */

namespace Back\Controller;
use My\MyController;

class MainController extends MyController{  //后台主页面
    public function index()
    {
       $n=$_SESSION[$this->s_pix.'n'];
        $this->user_limit(L_MENU); //判断用户身份读取相应权限左边菜单
        $ico=$this->arr_data("ico");
        $this->assign(
            array('ico'=>$ico,"n"=>$n)
        ); //权限图标
        $role=D("Role");
        $user=D("User");
        $r_id=$user->role_id($this->u_id);

        // 添加一个客服消息个数查询---咨询管理
        //Home/Line/261 _cus
	
        $cid_cus=F($this->u_id."_cus");
		
        if(isset($cid_cus) && intval($cid_cus)!==0)  // 如果客户增删下线时的更新咨询的消息个数失败时存储变量
        {
            $user_fj=D("User_fj");
			$zx=$user_fj->where("id=$this->u_id")->setInc('meg',$cid_cus);
            if(!isset($zx) || $zx!=1)
            {
				F($this->u_id."_cus",$cid_cus);
                $this->write_log("咨询师".$this->u_id."|".$_SESSION[$this->s_pix."n"]."消息更新失败|与数据库差距个数 ".$cid_cus);
            }else
			{
				F($this->u_id."_cus",NULL); // 如果更新完缓存的数据清空缓存
			}
        }
        $cus=D("Customer");
        $info_c=$cus->info_c($this->u_id); // 最后查询是否上次更新成功
		$cid_cus=F($this->u_id."_cus");
		if(isset($cid_cus) && intval($cid_cus)!==0)
		{
			$info_c+=intval($cid_cus);
	    }
		

        // 特别设置一个客服消息
        // 当前用户的uid--根据uid 列出自己下面的客户
        $explain_text="客服消息";
        $this->assign(array("explain_text"=>$explain_text,"uid"=>$_SESSION[$this->s_pix.'id'],"info_c"=>$info_c));

        $this->display('Public/main');
    }
} 