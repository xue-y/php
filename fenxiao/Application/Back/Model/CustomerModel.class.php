<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-6-9
 * Time: 上午10:53
 * 咨询-----客户数据
 */

namespace Back\Model;
use Think\Model;

class CustomerModel extends Model {
    protected $autoCkeckFields = false; //关闭检测字段
    protected $readonlyField = array('id');
    protected  $tableName = 'cus_detailed';
    protected $updateFields = array('wx','n','money','age','sex','descr'); // 允许修改的字段

    public  $_validate=array(
        array('phone','/^1(\d){10}$/','请输入正确的手机号',2,'regex'), // 修改时---验证手机号失败
       /* array('phone','','手机号已注册',2,'unique',1),*/
        array('age','/^\d{1,3}$/','用户年龄必须是数字',2,'regex'),
        array('wx','/^[\s\S]{3,60}$/','微信号字符长度3--20个字符',2,'regex'),
        array('money','/^[\+|\-]\d+$/','请输入+\-数字',2,'regex'),
        array('n',' /^[\s\S]{2,15}$/i','用户名2--5位',2,'regex'),
        array('pass','/^([\w\.\@\!\-\_]){6,12}$/i','6--12位数字、英文 . ! @ - _',2,'regex'),
        array('pass2','u_pass','确认密码不正确',2,'confirm')
        //客户  添加 修改
    );
    public $_auto=array(
        array('pass','',2,'ignore'),
        array('age','',2,'ignore'),
        array('n','',2,'ignore'),
        array('wx','',2,'ignore'),
        array('phone','',2,'ignore'),
    );

    /**查询指定用户所有信息--根据咨询师id
     * @parem $id 客户id
     * @return type array 返回客户的所有信息
     * */
    public function cus_id($id)
    {
        $cus=$this->alias('a')->field("a.*,b.phone,c.u_name")
            ->join('__CUS_BASE__ as b ON b.id = a.id')
           ->join('__USER__ as c ON a.cid=c.id')
            ->where("b.is_del=0 and b.id=$id")->select();
        return $cus[0];
    }

    /** 根据客户id 判断客户是否存在
     * @parem $id 客户id
     * @return type int 存在返回1
     * */
    public function is_cus($id)
    {
        $w["id"]=array('eq',$id);
        $w["is_del"]=array('eq',0);
        return $this->table("__CUS_BASE__")->where($w)->count();
    }

    /** 修改用户信息是查询手机号是否唯一
     * @parem $id 当前客户的id
     * @parem $phone  当前客户的手机号
     * @return type int 存在返回1
     * */
    public function unique_phone($id,$phone)
    {
        $w["id"]=array('neq',$id);
        $w["phone"]=array('eq',$phone);
        return $this->table("__CUS_BASE__")->where($w)->count();
    }

    /** 查询用户佣金
     * @parem $id 客户id
     * @return type int 客户佣金金额
     * */
    public function cus_money($id)
    {
        $w["id"]=array('eq',$id);
        $money=$this->where($w)->getField('money');
        return intval($money);
    }

    /** 根据客户id获得客户密码
     * @parem $id 当前客户的id
     * */
    public function cus_pass($id)
    {
       return $this->table("__CUS_BASE__")->where("is_del!=1")->getFieldById($id,"pass");
    }

    /** 判断客户是否存在---并且判断当前管理是否有权删除--单个用户
     * @parem $id 当前客户的id
     * @parem $uid 当前操作管理员id
     * @parem $is_del 是否删除的值 0 或者 1
     * @return type int 失败0成功1
     * */
    public function del_cus_one($id,$uid,$is_del)
    {
        if(intval($id)<1)
        {
            return 0;
        }
        $w["a.cid"]=array("eq",$uid);
        $w["a.id"]=array("eq",$id);
        $w["b.is_del"]=array('eq',$is_del);
        return  $this->alias("a")->where($w)->join("__CUS_BASE__ as b ON b.id=a.id")->count();
    }

    /** 判断客户是否存在---并且判断当前管理是否有权删除--多个用户
     * @parem $ids 当前客户的id 数组
     * @parem $uid 当前操作管理员id
     * @parem $is_del 是否删除 0
     * @return type array 可以删除的客户id数组
     * */
    public function del_cus_multi($ids,$uid,$is_del)
    {
        $ids=implode(",",$ids);
        $w["a.id"]=array("in",$ids);
        $w["a.cid"]=array("eq",$uid);
        $w["b.is_del"]=array('eq',$is_del);
        return $this->alias("a")->where($w)->join("__CUS_BASE__ as b ON b.id=a.id")->field("a.id")->select();
    }

    /** 取得消息个数----咨询管理员
     * @parem $id 咨询 的id
     * @parem $role 如果不为nuLl 是咨询，如果为null 身份是 客户
     * @return type int  消息个数
     * */
    public function info_c($id)
    {
       $info_c=$this->table("__USER_FJ__")->getFieldById($id,"meg");
	   return $info_c;
    }

    /* 客户推荐的下线个数
     * @parem $id 客户id
     * @return 客户推荐的下线个数
     * */
    public function ke_line_c($id)
    {
        $w["tid"]=array("eq",$id);
        return $this->table("__CUS_DOWNLINE__")->where($w)->count();
    }

    /** 新增用户时判断手机号是否已经注册
     * */
    public function is_phone($phone)
    {
        return $this->table("__CUS_BASE__")->where("phone=$phone")->count();
    }

    /** 判断是不是当前管理员（咨询）的客户
     * @parem $id 客户id
	 * @parem $cid 咨询 的id
	 * @parem $field 需要查询返回的字段
     * @return type int  返回个数/ type string 字段值
     * */
    public function cus_is_zx($id,$cid,$field=NULL)
    {
        $w["cid"]=array("eq",$cid);
        $w["id"]=array("eq",$id);
		if(!isset($field))
		{
			return $this->where($w)->count();
		}else
		{
			$zd=$this->where($w)->field($field)->find();
			return $zd[$field];
		}
       	
    }

    /** 回收站判断 客户是不是当前管理员的
     * @parem $id 客户id  多个id是数组形式
     * @parem $uid 管理员id
     * @parem $is_multi 默认null  存在是判断多个id
     * @return 判断单个id 返回查询个数,判断多个id 返回id数组
     * */
    public function cusid_uid($id,$uid,$is_multi=null)
    {
        $w["a.cid"]=array("eq",$uid);
        $w["b.is_del"]=1;
        if(isset($is_multi))
        {
            $w["a.id"]=array("in",implode(",",$id));
            $is_cus=$this->alias("a")->where($w)->field("a.id")
                ->join("__CUS_BASE__ as b ON b.id = a.id")->select();
            return array_column($is_cus,"id");
        }else
        {
            $w["a.id"]=array("eq",$id);
            return  $this->alias("a")->where($w)
                ->join("__CUS_BASE__ as b ON b.id = a.id")->count();
        }


    }

    /** 删除客户 判断要删除的用户是不是有提交(推荐)的下线未审核  未审核状态为 1
     * @parem $id 客户id
     * @parem $uid 管理员id
     * @return 返回下线个数
     * */
    public function rec_cus_line($id,$uid,$is_multi=null)
    {
        $w["cid"]=array("eq",$uid);
        $w["state"]=1;
        if(isset($is_multi))
        {
            $w["tid"]=array("in",implode(",",$id));
            $tid=$this->table("__CUS_DOWNLINE__")->where($w)->field("tid")->select();
            return array_column($tid,"tid");
        }else
        {
            $w["tid"]=array("eq",$id);
            return $this->table("__CUS_DOWNLINE__")->where($w)->count();
        }
    }

    /**回收站  推荐人是当前要删除客户
     * @parem $id 客户id   多个id 是字符串形式
     * @parem $uid 管理员id
     * @parem $type 默认eq  默认查询一条数据
     * @return 返回下线个数
     * */
    public function rec_cus_tid($id,$uid,$type='eq')
    {
        $w["cid"]=array("eq",$uid);
        $w["tid"]=array($type,$id);
        return $this->table("__CUS_DOWNLINE__")->where($w)->count(); // 要删除的客户推荐过的用户
    }

    /**回收站  通过审核的客户--推荐人是当前要删除客户
     * @parem $id 客户id   多个id 是字符串形式
     * @parem $uid 管理员id
     * @parem $type 默认eq  默认查询一条数据
     * @return 返回已通过审核下线个数
     * */
    public function rec_cus_state($id,$uid,$type='eq')
    {
        $w["cid"]=array("eq",$uid);
        $w["tid"]=array($type,$id);
        return $this->where($w)->count();
    }

    /** 回收站 查询要删除客户的佣金表
     * @parem $id 客户id
     * @parem $uid 管理员id
     * @parem $type 默认eq  默认查询一条数据
     * @return 返回查询个数
     * */
    public function rec_cus_money($id,$uid,$type="eq")
    {
        $w["id"]=array($type,$id);
        $w["cid"]=array("eq",$uid);
        return $this->table("__CUS_MONEY__")->where($w)->count();
    }

    /**  回收站 查询要删除客户的信息通知表
     * @parem $id 客户id
     * @parem $type 默认eq  默认查询一条数据
     * @return 返回查询个数
     * */
    public function rec_cus_info($id,$type="eq")
    {
        $w["cid"]=array($type,$id);
       return  $this->table("__CUS_INFO__")->where($w)->count();
    }

} 