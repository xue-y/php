<?php
namespace Back\Controller;
use My\MyController;
class IndexController extends MyController { // 后台首页
    public function index(){
        $this->task_nimble(); //任务的快捷操作
        $this->remind_meg();
        if(isset($_COOKIE[TAST_W]))
        {
            $p_id_arr=$this->u_task_c();
            $p_id=implode(",",$p_id_arr);
        }
        $n=cookie("n");
        $this->assign(array(
            "p_id"=>$p_id,
            "n"=>$n,
            "u_id"=>$this->u_id
        ));

        $this->display('index');
    }
}