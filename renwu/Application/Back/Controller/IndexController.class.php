<?php
namespace Back\Controller;
use My\MyController;
class IndexController extends MyController { // 后台首页
    public function index(){
        $this->task_nimble(); //任务的快捷操作
        $this->remind_meg();
        if(isset($_SESSION[TAST_W]))
        {
            $p_id_arr=$this->u_task_c();
            $p_id=implode(",",$p_id_arr);
        }
        $n=$_SESSION[$this->s_pix.'n'];
        $this->assign(array(
            "p_id"=>$p_id,
            "n"=>$n,
            "u_id"=>$this->u_id
        ));

        $this->display('index');
    }
}