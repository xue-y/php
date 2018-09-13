<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-12
 * Time: 下午7:13
 */

namespace Back\Model;


use Think\Model;

class LineModel extends Model{
    protected $autoCkeckFields = false; //关闭检测字段
    protected  $tableName = 'cus_downline';
    protected $updateFields = array('wx','phone','state','n','remarks',"sex","age",'descr'); // 允许修改的字段

   /**验证当前咨询是否有操作客户的权限
    * @parem $cid 当前咨询id
    * @parem $id 客户推荐下线id
    * @return type int
    * */
    public function zx_line($cid,$id)
    {
        /*$w["a.id"]=$id;
        $w["a.cid"]=$cid;
       echo $this->where($w)->alias("a")->field("b.n as t_n,a.*")
           ->join("__CUS_DETAILED__ as b ON b.id=a.tid")->fetchSql(true)->find();*/
        $w["id"]=$id;
        $w["cid"]=$cid;
        return $this->where($w)->find();

    }

    /** 判断当前的下线是否已经审核过了
     * @parem $id 当前下线id
     * @parem $cid 当前咨询id
     * @return  type int  审核状态
     * */
    public function line_state($id,$cid)
    {
       return  $this->where("cid=$cid")->getFieldById($id,"state");
    }

} 