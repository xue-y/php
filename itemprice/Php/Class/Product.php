<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-15
 * Time: 下午3:03
 */

class Product extends Pro {

    private $c_name="项目"; // 当前操作对象
    private $c_name_g="项目类型"; // 当前操作组元素
    public $g_id_field="t_id"; // 当前操作组字段名称
    public  $id_field="p_id"; // 当前操作元素字段名称--值为 组id_ 自身id
    public  $n_field="p_n"; // 当前操作元素名称字段
    private $table=DATA_PRO; // 当前操作的表
    private $table_parent=DATA_TYPE;//当前操作的父表
    private $cur_data_n="cur_data";// 当前数组名称标识
    private $table_d=DATA_DPRO;// 当前表的 回收站表
    private $k_id="k_id"; // 真正的单独id
    public $price_data_field;//----价格字段
    private $g_url=null;

    public function __construct()
    {
        parent::__construct();
        $this->g_url=PHP_CON.ucwords($this->table_parent).self::$conf_data["LIB_EXT"];  // 组url ;
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
            $all_data["g"]=$this->read_data(DATA_TYPE); // 判断数数据是否为空
            if(!empty($all_data["g"]))
            {
                $product_arr=array();
                foreach($all_data["g"] as $k=>$v)
                {
                    $product_arr_temp=$this->read_data(DATA_PRO.DE_LIMITER.$k);
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
            $this->price_data_field=$this->price_data_field();
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

    public function add($g_id=null)  //------进入添加用户页面
    {
        $this->price_data_field=$this->price_data_field();
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        $all_data["group"]=$this->read_data($this->table_parent);
        $all_data['is_g']=$g_id;
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    public function add_exct() // 执行添加页面
    {
        $vail_data=$this->vail_data();
        $post=$vail_data[0];
        $user_data=$vail_data[1]; // 验证数据 组装数据

        $u_data_file=$this->table.DE_LIMITER.$post["group"];
        $gid=$post["group"].DE_LIMITER; // 此id 与数据文件对应
        $f_len=$this->write_date_one($user_data,$u_data_file,false);
        if($f_len>1)
        {
            $this->skip("success",PHP_CON.__CLASS__.".php","添加".$this->c_name);
        }else
        {
            $this->skip("error",null,"添加".$this->c_name);
        }
    }

    public function edit($u_id) //------进入修改页面
    {
        $this->price_data_field=$this->price_data_field();
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        // 分割 u_id
        $id_arr=explode(DE_LIMITER,$u_id);
        // 判断组文件中是否存在此组
        $all_data["group"]=$g_data=$this->read_data($this->table_parent);
        if(!isset($g_data[$id_arr[0]]) || empty($g_data[$id_arr[0]]))
        {
            $this->skip("error",null,"参数错误 操作");
        }
        $u_data=$this->read_data($this->table.DE_LIMITER.$id_arr[0]);
        $all_data[$this->g_id_field]=$id_arr[0];
        $all_data[$this->id_field]=$id_arr[1];
        $id=explode(DE_LIMITER,$u_id);//---第一个数组是组id 第二个是自身id
       foreach($u_data as $k=>$v)
        {
            if($id[1]==$k)
            {
                $all_data[$this->cur_data_n]=$v;
                break;
            }
        }
        if(!isset($all_data[$this->cur_data_n]))
        {
            $this->skip("error",null,"没有您要更改的数据 操作");
        }
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    public function edit_exct()
    {
        $vail_data=$this->vail_data();// 验证数据 组装数据
        $post=$vail_data[0];
        $user_data=$vail_data[1];

        $g_data=$this->read_data($this->table_parent);
        if(!isset($post["group"]) || !isset($g_data[$post["group"]]) || !isset($g_data[$post["old_group"]]))
        {
            $this->skip("error",null,"所选类型/组不存在 操作");
        }
        //-----------------取得原数据
        $data_f=$this->table.DE_LIMITER.$post["old_group"];
        $data=$this->read_data($data_f);

        if($post["group"]===$post["old_group"]) // 没有移动分组
        {
            $data[$post["id"]]=$user_data;
            $f_len=$this->write_date_one($data,$data_f,true);
            if($f_len>1)
            {
                $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","修改".$this->c_name);
            }else
            {
                $this->skip("error",null,"修改".$this->c_name);
            }
        }else                       //----------------移动分组
        {
           if(!isset($data[$post["id"]]))
           {
               $this->skip("error",null,$this->c_name."原数据错误修改");
           }
            unset($data[$post["id"]]);
            $f_len=$this->write_date_one($data,$data_f,true);

            $data_f_new=$this->table.DE_LIMITER.$post["group"];
            $f_len2=$this->write_date_one($user_data,$data_f_new);
            if($f_len>1 && $f_len2>1)
            {
                $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","修改".$this->c_name);
            }else
            {
                $this->skip("error",null,"修改".$this->c_name);
            }
        }
    }

    public function del($id)
    {
        $g_data=$this->read_data($this->table_parent);//------组数据

        if(is_array($id)) //-----------删除多个
        {
          foreach($id as $k=>$v)  // $k---组id
          {
              if(!isset($g_data[$k])) //---判断组是否存在
              {
                  $this->skip("error",null,$this->c_name_g.$k."数据错误删除");
                  break;
              }
              $v_data=$this->read_data($this->table.DE_LIMITER.$k); //product_组id
              if(!empty($v_data))
              {
                  $remove_arr=array();
                  $remove_key=array();
                  foreach($v as $kk=>$vv)
                  {
                      $remove_arr[]=$v_data[$vv];
                      $remove_key[]=$vv;
                  }
              }

              $f_len=$this->write_date($remove_arr,$this->table_d.DE_LIMITER.$k);
              foreach($remove_key as $v)
              {
                  unset($v_data[$v]);
              }
              $f_len2=$this->write_date_one($v_data,$this->table.DE_LIMITER.$k,true);
              if($f_len<1 || $f_len2<1)
              {
                  $info="删除".$this->c_name_g.'['.$k .']下的['.$vv.']'.$this->c_name;
                  $this->skip("error",null,$info);
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
            $dpro_data=$data[$ids[1]];
            $dpro_f=$this->table_d.DE_LIMITER.$ids[0];
            $f_len=$this->write_date_one($dpro_data,$dpro_f);

            unset($data[$ids[1]]);
            $f_len2=$this->write_date_one($data,$data_f,true);
            if($f_len>1 && $f_len2>1)
            {
                $this->skip(SUCCESS,null,"删除".$this->c_name);
            }else
            {
                $this->skip("error",null,"删除".$this->c_name);
            }
        }
    }

    /** 验证添加修改数据
     * */
    private function  vail_data()
    {
        //  $all_data["tit"]=array("项目名称","项目价格","项目类型","上架时间","备注信息"); 字段顺序
        $post=$this->add_slashes($_POST);
        $this->user_gid($post["group"],$this->table_parent); // 此数据与数据文件里的数据一致

        $Vail=Com::getInstance("Vaildata");
        $Vail->name($post["name"]);
        $user_data["n"]=$post["name"];

        $this->price_data_field=$this->price_data_field();
        foreach( $this->price_data_field as $k=>$v)
        {
            $user_data[$k]=$post[$k];
        }
        if(empty($post["t"]))  // 如果添加用户时没有填写密码有一个默认密码
        {
            $user_data["t"]=date("Y-m-d",time());
        }else
        {
            $bool=$Vail->check_time($post["t"]);
            if(!isset($bool))
            {
                $user_data["t"]=date("Y-m-d",time());
            }else
            {
                $user_data["t"]=$post["t"];
            }
        }
        $user_data["bz"]=$Vail->veri_str_len($post["bz"],"0,50");
        $vail_data[0]=$post;
        $vail_data[1]=$user_data;
         return $vail_data;
    }

} 