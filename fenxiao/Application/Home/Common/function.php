<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-7-23
 * Time: 下午2:38
 */

    /** 删除session 销毁登录的session
     * @parem $s_pix session 前缀
     */
    function de_session($s_pix)
    {
        unset($_SESSION[$s_pix.'login_status']);
        unset($_SESSION[$s_pix.'id']);
        unset($_SESSION[$s_pix.'phone']);
        unset($_SESSION[$s_pix.'token']);
        /*session($s_pix.'login_status',null);
        session($s_pix.'id',null);
        session($s_pix.'phone',null);
        session($s_pix.'token',null); */// 清空session
    }

    /**限制用户频繁访问刷新页面
     * */
    function visit_num()
    {
        $client_ip=get_client_ip(0,true);
        $s_pix=md5(__SELF__.$client_ip); // 每个页面名称+ip 限制刷新次数
        $temp_t=cookie($s_pix."t");  //根据ip 设置

        if(isset($temp_t) )
        {
            exit("您刷新的太频繁,请稍后刷新");
        }else
        {
            cookie("t",'t',array('expire'=>REFRESH_T,'prefix'=>$s_pix));
        }
        $temp_c=cookie($s_pix."c");

        if(isset($temp_c))
        {
            $temp_c+=1;
            $first_time=cookie($s_pix."fisrt_time"); // 第一次频繁刷新时间
            $update_time=REFRESH_CT-(time()-$first_time); // 第二次第三次 刷新时间 --- 减去原设定时间
            cookie("c",$temp_c,array('expire'=>$update_time,'prefix'=>$s_pix));
        }else
        {
            // 累计频繁刷新次数限制 在 REFRESH_CT时间内
            $temp_c=1;
            // 设置初次频繁刷新时间
            cookie("fisrt_time",time(),array('expire'=>REFRESH_CT,'prefix'=>$s_pix));
            cookie("c",$temp_c,array('expire'=>REFRESH_CT,'prefix'=>$s_pix));
        }

        if($temp_c>=REFRESH_C)
        {
            if($update_time>=60 && REFRESH_CT>=60)  // 如果超过一分钟
            {
                $temp=floor(REFRESH_CT/60);
            }else                                // 如果时间不足一分钟
            {
                $temp=ceil($update_time/60);
            }
            exit("您刷新的太频繁请 $temp 分钟后再刷新");
        }
    }
