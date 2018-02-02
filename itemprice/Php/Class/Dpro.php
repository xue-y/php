<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-17
 * Time: 上午10:14
 */

if(!defined('IN_SYS')) {
    header("Location:/404.html");
    exit();  // 跳转404
}
class Dpro extends Pro{

    private $c_name="项目"; // 当前操作对象
    private $c_name_g="项目类型"; // 当前操作组元素
    public $g_id_field="t_id"; // 当前操作组字段名称
    public  $id_field="p_id"; // 当前操作元素字段名称--值为 组id_ 自身id
    public  $n_field="p_n"; // 当前操作元素名称字段
    private $table=DATA_DPRO; // 当前操作的表
    private $table_parent=DATA_TYPE;//当前操作的父表
    private $cur_data_n="cur_data";// 当前数组名称标识
    private $table_r=DATA_PRO;// 当前表的 还原表
    private $k_id="k_id"; // 真正的单独id

    public function  __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);

        if(filter_has_var(INPUT_GET, $this->g_id_field)) //-------------------------------------查看指定分组
        {
            $gid=intval($_GET[$this->g_id_field]); //---组id
            $gid_data=$this->read_data($this->table_parent); // 判断组是否存在
            if(!isset($gid_data[$gid]) || empty($gid_data[$gid]))
            {
                $this->skip("error",__CLASS__.self::$conf_data["TEM_EXT"],"所选类型/组不存在 操作");
            }

            $user_file=strtolower(__CLASS__).DE_LIMITER.$gid;
            $product_arr=$this->read_data($user_file);//---------如果没有组成员文件返回空数组
            if(!empty($product_arr))
            {
                foreach($product_arr as $k=>$v)
                {
                    $product_arr[$k]["id"]=$gid.DE_LIMITER.$k;
                    $product_arr[$k][$this->k_id]=$k;  //  当前id [单独的]
                    $product_arr[$k][$this->g_id_field]=$gid; // 当前组id[单独的]
                }
                $all_data["g"]=$gid_data[$gid];
            }
        }else                           //-------------------------------------------查看首页分组
        {
            $all_data["g"]=$this->read_data($this->table_parent);
            if(!empty($all_data["g"]))      // 判断数数据是否为空
            {
                $product_arr=array();
                foreach($all_data["g"] as $k=>$v)
                {
                    $product_arr_temp=$this->read_data($this->table.DE_LIMITER.$k);
                    if(!empty($product_arr_temp))
                    {
                        foreach($product_arr_temp as $kk=>$vv)
                        {
                            $vv["id"]=$k.DE_LIMITER.$kk; // 组合 组id_ 真实id
                            $vv[$this->k_id]=$kk;       // 单独 真实id
                            $vv[$this->g_id_field]=$k; // 单独组id
                            array_push($product_arr,$vv);
                        }
                    }
                }
            }

        }

        if(!empty($product_arr))
        {
            $showrow =PAGE_SIZE; //一页显示的行数
            $show_page=PAGE_SHOW; // 显示几个页码
            if(isset($_GET['p']))
            {
                $curpage=max(intval($_GET['p']),1);
            }else
            {
                $curpage=1;  // 当前页数
            }
            //当前的页,还应该处理非数字的情况
            /* if(isset($_GET['g_id'])) //Page.php  这个类中使用 需要手动添加参数
             {
                 $url = "?p={page}&g_id=".max(intval($_GET['g_id']),0);
             }else
             {
                 $url = "?p={page}";
             }*/

            $total=count($product_arr);                // 数据开始位置    数组截取的结束位置
            $list=array_slice($product_arr,($curpage-1)*$showrow,$showrow);
            rsort($list); // 文件排序
        }
        $requer_file=strtolower(__CLASS__);
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    public function edit($id)  //--------------------------还原
    {
        $g_data=$this->read_data($this->table_parent);//------ 组数据
        $data_txt=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
        $f_ext=self::$conf_data["DATA_EXT"];

        if(is_array($id)) //-----------还原多个
        {
            foreach($id as $k=>$v)//--------------------------------循环组数据
            {
                if(!isset($g_data[$k])) //---判断组是否存在
                {
                    $this->skip("error",null,$this->c_name_g.$k."数据错误还原");
                    break;
                }
                $v_data=$this->read_data($this->table.DE_LIMITER.$k);

                foreach($v as $kk=>$vv) //--------------------------------操作每个组的数据
                {
                    if(isset($v_data[$kk]))
                    {
                        $f_len=$this->write_date_one($v_data[$vv],$this->table_r.DE_LIMITER.$k);
                        unset($v_data[$kk]);
                        if(count($v_data)<1)   //如果数组为空删除文件 跳出循环
                        {
                            @unlink($data_txt.$this->table.DE_LIMITER.$k.$f_ext);
                            break;
                        }else
                        {
                            $f_len2=$this->write_date_one($v_data,$this->table.DE_LIMITER.$k,true);
                            if($f_len<1 || $f_len2<1)
                            {
                                $info="还原".$this->c_name_g.'[ '.$k .' ] 下 [ '.$vv.' ]'.$this->c_name;
                                $this->skip("error",null,$info,40);
                            }
                        }
                    }else
                    {
                        $info=$this->c_name_g.'[ '.$k.' ]下的不存在'.$this->c_name.'[ '.$vv ." ]还原";
                        $this->skip("error",null,$info,40);
                    }
                }
            }
            $this->skip(SUCCESS,null,"还原".$this->c_name);
        }else           // -----------还原单个
        {
            $ids=explode(DE_LIMITER,$id);
            if(!isset($g_data[$ids[0]])) //-----判断组是否存在
            {
                $this->skip("error",null,$this->c_name_g."数据错误还原");
            }
            $data_f=$this->table.DE_LIMITER.$ids[0];
            $data=$this->read_data($data_f);

            if(!isset($data[$ids[1]]))
            {
                $this->skip("error",null,$this->c_name."数据错误还原");
            }
            $dpro_data=$data[$ids[1]];
            $dpro_f=$this->table_r.DE_LIMITER.$ids[0];
            $f_len=$this->write_date_one($dpro_data,$dpro_f);

            unset($data[$ids[1]]);
            if(count($data)<1)  //-------------------如果是空数组删除文件
            {
                @unlink($data_txt.$data_f.$f_ext);
            }else
            {
                $f_len2=$this->write_date_one($data,$data_f,true);
                if($f_len>1 && $f_len2>1)
                {
                    $this->skip(SUCCESS,null,"还原".$this->c_name);
                }else
                {
                    $this->skip("error",null,"还原".$this->c_name);
                }
            }
            $this->skip(SUCCESS,null,"还原".$this->c_name);
        }
    } // 执行还原

    public function del($id) // 执行删除
    {
        $g_data=$this->read_data($this->table_parent);//------组数据
        $data_txt=$_SERVER['DOCUMENT_ROOT'].DATA_TXT;
        $f_ext=self::$conf_data["DATA_EXT"];

        if(is_array($id)) //-----------删除多个
        {
            foreach($id as $k=>$v)         //------------------------循环组数据
            {
                if(!isset($g_data[$k])) //---判断组是否存在
                {
                    $this->skip("error",null,$this->c_name_g.$k."数据错误删除");
                    break;
                }
                $v_data=$this->read_data($this->table.DE_LIMITER.$k);//-----打开每个组文件
                foreach($v as $vv)
                {
                    if(isset($v_data[$vv]))
                    {
                        unset($v_data[$vv]);
                        if(count($v_data)<1)  //如果数组为空删除文件 跳出循环
                        {
                            @unlink($data_txt.$this->table.DE_LIMITER.$k.$f_ext);
                            break;
                        }else
                        {
                            $f_len=$this->write_date_one($v_data,$this->table.DE_LIMITER.$k,true);
                            if($f_len<1)
                            {
                                $info="删除".$this->c_name_g.'['.$k .']下的['.$vv.']'.$this->c_name;
                                $this->skip("error",null,$info);
                            }
                        }

                    }else
                    {
                        $info=$this->c_name_g.'['. $k .']下不存在'.$this->c_name.'[ '.$vv ."]删除";
                        $this->skip("error",null,$info);
                    }
                }
            }
            $this->skip(SUCCESS,null,"删除".$this->c_name);
        }else           // -----------删除单个
        {
            $ids=explode(DE_LIMITER,$id);
            if(!isset($g_data[$ids[0]])) //-----判断组是否存在
            {
                $this->skip("error",null,$this->c_name_g."数据错误删除");
            }
            $data_f=$this->table.DE_LIMITER.$ids[0];
            $data=$this->read_data($data_f);

            if(!isset($data[$ids[1]]))
            {
                $this->skip("error",null,$this->c_name."数据错误删除");
            }

            unset($data[$ids[1]]);
            if(count($data)<1)   //如果数组为空删除文件
            {
                @unlink($data_txt.$data_f.$f_ext);
            }else
            {  $f_len=$this->write_date_one($data,$data_f,true);
                if($f_len>1)
                {
                    $this->skip(SUCCESS,null,"删除".$this->c_name);
                }else
                {
                    $this->skip("error",null,"删除".$this->c_name);
                }
            }
            $this->skip(SUCCESS,null,"删除".$this->c_name);
        }
    }

} 