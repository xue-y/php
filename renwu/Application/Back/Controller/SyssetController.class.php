<?php
namespace Back\Controller;
use My\MyController;
class SyssetController extends MyController { // 系统设置
    public function index(){
        $config=file_get_contents(APP_PATH.'Back/Conf/define.php');
        if(isset($_POST) && !empty($_POST))
        {
            $post=$this->add_slashes($_POST);

            if(isset($post['quick']) && !empty($post['quick']))
            {
                $q="/'QUICK',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'QUICK',{$post["quick"]})",$config);
                }
            }
            if(isset($post['p_r_id']) && !empty($post['p_r_id']))
            {
                $q="/'P_R_ID',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'P_R_ID',{$post["p_r_id"]})",$config);
                }
            }
            if(isset($post['p_r_id_c']) && !empty($post['p_r_id_c']))
            {
                $q="/'P_R_ID_C',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'P_R_ID_C','{$post["p_r_id_c"]}')",$config);
                }
            }
            if(isset($post['a_s_a']) && !empty($post['a_s_a']))
            {
                $q="/'A_S_A',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'A_S_A',{$post["a_s_a"]})",$config);
                }
            }
            if(isset($post['a_s_c']) && !empty($post['a_s_c']))
            {
                $q="/'A_S_C',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'A_S_C',{$post["a_s_c"]})",$config);
                }
            }
            if(isset($post['t_i']) && !empty($post['t_i']))
            {
                $q="/'T_INT',.*?\)/";
                if(preg_match($q,$config)){
                    $t_int=intval($post["t_i"])*60;
                    $config=preg_replace($q,"'T_INT',$t_int)",$config);
                }
            }
            if(isset($post['p_o_c']) && !empty($post['p_o_c']))
            {
                $q="/'P_O_C',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'P_O_C',{$post["p_o_c"]})",$config);
                }
            }
            if(isset($post['deny_l_id']) && !empty($post['deny_l_id']))
            {
                $q="/'DENY_L_ID',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'DENY_L_ID','{$post["deny_l_id"]}')",$config);
                }
            }
            if(isset($post['web_name']) && !empty($post['web_name']))
            {
                $q="/'WBE_NAME',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'WBE_NAME','{$post["web_name"]}')",$config);
                }
            }
     //--------------------------------------------------------------------------

            if(isset($post['s_port']) && !empty($post['s_port']))
            {
                $q="/'S_PORT',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'S_PORT',{$post["s_port"]})",$config);
                }
            }
            if(isset($post['s_server']) && !empty($post['s_server']))
            {
                $q="/'S_SERVER',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'S_SERVER','{$post["s_server"]}')",$config);
                }
            }
            if(isset($post['s_mail']) && !empty($post['s_mail']))
            {
                $q="/'S_MAIL',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'S_MAIL','{$post["s_mail"]}')",$config);
                }
            }
            if(isset($post['s_pass']) && !empty($post['s_pass']))
            {
                $q="/'S_PASS',.*?\)/";
                if(preg_match($q,$config)){
                    $config=preg_replace($q,"'S_PASS','{$post["s_pass"]}')",$config);
                }
            }

            $len=strlen($config);
            $len2=file_put_contents(APP_PATH.'Back/Conf/define.php',$config);
            if($len!=$len2)
            {
                $this->error('修改网站配置失败');
            }else
            {
                $this->success('网站配置信息修改成功');
            }
        }
        else
        {
          //--------------------------------显示网站信息
            $pattern="/'QUICK',(.*?)\)/";
            preg_match($pattern,$config,$quick);
            $con_arr['quick']=$quick[1];

            $pattern="/'P_R_ID',(.*?)\)/";
            preg_match($pattern,$config,$p_r_id);
            $con_arr['prid']=$p_r_id[1];

            $pattern="/'P_R_ID_C','(.*?)'\)/";
            preg_match($pattern,$config,$p_r_id_c);
            $con_arr['pridc']=$p_r_id_c[1];

           $pattern="/'A_S_A',(.*?)\)/";
            preg_match($pattern,$config,$a_s_a);
            $con_arr['asa']=$a_s_a[1];

            $pattern="/'A_S_C',(.*?)\)/";
            preg_match($pattern,$config,$a_s_c);
            $con_arr['asc']=$a_s_c[1];

            $pattern="/'P_O_C',(.*?)\)/";
            preg_match($pattern,$config,$poc);
            $con_arr['poc']=$poc[1];

            $pattern="/'T_INT',(.*?)\)/";
            preg_match($pattern,$config,$t_i);
            $con_arr['tint']=intval($t_i[1])/60;

            $pattern="/'DENY_L_ID','(.*?)'\)/";
            preg_match($pattern,$config,$deny_l_id);
            $con_arr['denylid']=$deny_l_id[1];

            $pattern="/'WBE_NAME','(.*?)'\)/";
            preg_match($pattern,$config,$web_name);
            $con_arr['webname']=$web_name[1];
//--------------------------------------------------
            $pattern="/'S_PORT',(.*?)\)/";
            preg_match($pattern,$config,$s_port);
            $con_arr['sport']=$s_port[1];

            $pattern="/'S_SERVER','(.*?)'\)/";
            preg_match($pattern,$config,$s_server);
            $con_arr['sserver']=$s_server[1];

            $pattern="/'S_MAIL','(.*?)'\)/";
            preg_match($pattern,$config,$s_mail);
            $con_arr['smail']=$s_mail[1];

            $pattern="/'S_PASS','(.*?)'\)/";
            preg_match($pattern,$config,$s_pass);
            $con_arr['spass']=$s_pass[1];

            $limit=D("Limit");
            $class=lcfirst(CONTROLLER_NAME);
            $operate=$limit->sysset_operate($class);
            if(!empty($operate))
            {
                foreach($operate as $k=>$v)
                {
                    $execs=explode("-",$v["execs"]);
                    $operate[$k]["execs"]=$execs[1];
                }
            }
            $this->assign(array('con'=>$con_arr,"operate"=>$operate));
        }
        $this->pos_tag();  //当前位置标签
        $this->display();
    }

    //清理过期的图片
    public function oldf()
    {
        $pro=D("Problem");
        $p_mint=$pro->min_t(); // 最小的提交任务时间
        $task=D("Task");
        $t_mint=$task->min_t(); //最小的执行任务时间
        $min=min($p_mint,$t_mint);
        $dir=date("Ym",$min);
        $old_dir_arr=array();
        if(is_dir(OLD_PIC))
        {
            $old_f=opendir(OLD_PIC);
            while(($old_f2=readdir($old_f))!==FALSE)
            {
                if($old_f2!='.' && $old_f2!=".." && is_dir(OLD_PIC.$old_f2) && intval($old_f2)<intval($dir))
                {
                    array_push($old_dir_arr,$old_f2);
                }
            }
            closedir($old_f);
        }
        // 不存在任务的 任务图片文件夹
        if(empty($old_dir_arr))
        {
            echo "empty";
            exit;
        }
        // 存在文件夹
        foreach($old_dir_arr as $v)
        {
            $f_v=opendir(OLD_PIC.$v);
            while(($f=readdir($f_v))!==FALSE)
            {
                if($f!="." && $f!=".." && is_file(OLD_PIC.$v.'/'.$f))
                {
                   $b_f=unlink(OLD_PIC.$v.'/'.$f);
                    if(!isset($b_f))
                        $this->write_log("清理图片- OLD_PIC.$v.'/'.$f 失败");
                }
            }
            closedir($f_v);
            $b_d=rmdir(OLD_PIC.$v);
            if(!isset($b_d))
                $this->write_log("清理图片文件夹- OLD_PIC.$v 失败");
        }
        echo "ok";

    }
}