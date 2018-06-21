<?php
namespace Back\Controller;
use My\MyController;
class IndexController extends MyController { // 后台首页
    public function index(){

		$pos["c"]="后台首页";
		$pos["a"]='';
	
        $n=$_SESSION[$this->s_pix.'n'];
        $this->assign(array(
            "n"=>$n,
            "u_id"=>$this->u_id
        ));
        $limit=D("Limit");
        $quick=$limit->task_all($this->u_id);
		
		$explain_text="消息列表";
		
		// 取得当前管理员的消息个数---如果有附加表并且有数据
		$meg=$limit->table("__USER_FJ__")->getFieldById($this->u_id,'meg');
		$new=array();
        foreach($quick as $k=>$v)
        {
            if(strstr($v["execs"],'-index')!='')
            {
                $v['execs']=str_replace('-','/',$v['execs']);
				if(($v["n"] == $explain_text) &&  intval($meg)>=1)
				{
				
					// 消息通知个数
					$v['execs'].="?state=1";
					$cid_cus=F($this->u_id."_cus");
					if(isset($cid_cus) && (intval($cid_cus)!==0))
					{
						$meg=$meg+($cid_cus);
					}
					$v['n'].='<sup class="red" style="top: -0.5em;left: -2px;"> '.$meg.'</sup>';
				}
                array_push($new,$v);
            }			
        }
		
        $this->assign("pos",$pos);
		 $this->assign("quick",$new);
        $this->display();
    }
}