<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-5
 * Time: 下午1:10
 */

class Type extends Pro {
    private $c_name="项目类型"; // 当前操作对象
    private $c_name_c="项目"; // 当前操作组子元素
    private $g_id_field="t_id"; // 当前操作组字段名称
    private $g_n_field="t_n"; //当前操作元素名称字段
    private $c_file="Product";// 子元素控制器名称
    private $table_child=DATA_PRO;// 子元素的表
    private $table_child_r=DATA_DPRO;// 子元素的表的回收站表 用于还原

    public function  __construct()
    {
        parent::__construct();
    }
    /* 项目列表页面
     * */
    public function index()
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        $list=$this->read_data(strtolower(__CLASS__));
        if(!empty($list))
        {
            foreach($list as $k=>$v)
            {
                $list2[$k]["n"]=$v;
                $list2[$k]["id"]=$k+1;// 0 为空 取不到，所有加1 方便修改添加删除操作
            }
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
            $url = "?p={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']-- Page.php  这个类中使用

            $total=count($list2);                // 数据开始位置    数组截取的结束位置
            $list=array_slice($list2,($curpage-1)*$showrow,$showrow);
            rsort($list); // 文件排序
        }
        $requer_file=strtolower(__CLASS__);
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }
    /**进入添加页面
     * */
    public function add()
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }


    /** 执行添加页面
     * */
    public function add_exct()
    {
        $Vail=Com::getInstance("Vaildata");
        $new_data=$Vail->veri_arr($_POST["name"],"添加".$this->c_name);
        if(count($new_data)>10)
        {
            $new_data=array_slice($new_data,0,10);
        }
        foreach($new_data as $v)
        {
            $v=$Vail->veri_str_len($v,"2,10");
            $new_post[]=filter_var($v,FILTER_SANITIZE_STRING); // 过滤器去除或编码不需要的字符。
        }
        $f_len=$this->write_date($new_post,strtolower(__CLASS__));
        if($f_len>1)
        {
            //  $this->index(true); // 更新列表页缓存---如果列表页使用的是静态的--用户组
            $this->skip("success",PHP_CON.__CLASS__.".php","添加".$this->c_name);
        }else
        {
            $this->skip("error",null,"添加".$this->c_name);
        }
    }

    /*进入修改页面
   * @parem $id 用户组传过来的id
    * @parem $is_arr 是不是批量操作
    * */
    public function edit($id)
    {
        $all_data=$this->cur_pos(__CLASS__,__FUNCTION__);
        $data=$this->read_data(strtolower(__CLASS__));
        if(is_array($id))
        {
            foreach($id as $k=>$v)
            {
                $all_data["t"][$k][$this->g_n_field]=$data[$v-1];
                $all_data["t"][$k][$this->g_id_field]=$v;  // 传过去的g_id 是已经加了 【1】 的
            }
        }else
        {
            $all_data["t"][0][$this->g_n_field]=$data[$id-1];
            $all_data["t"][0][$this->g_id_field]=$id;
        }
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    /** 执行修改
     * */
    public function edit_exct()
    {
        $post=$_POST[$this->g_n_field];
        $data=$this->read_data(strtolower(__CLASS__));
        foreach($post as $k=>$v)
        {   //列表页面 传值加了 1
            if(isset($data[$k-1]) && !empty($data[$k-1]))  // ----------------------判断有修改的值是否存在
            {
                $data[$k-1]=$post[$k];
            }else
            {
                $this->skip("error",null,"修改 $this->c_name 数据为空或不存在 操作");
            }
        }
        $f_len=$this->write_date($data,strtolower(__CLASS__),true);
        if($f_len>1)
        {
            $this->skip("success",PHP_CON.__CLASS__.".php","修改".$this->c_name);
        }else
        {
            $this->skip("error",null,"修改".$this->c_name);
        }
    }


    /** 执行删除
     * */
    public function del($id)
    {
        $data=$this->read_data(strtolower(__CLASS__));
        $old_data_len=count($data);
        $del_id_c=count($id);
        // ----------------------------------传过来的id 是加了1 ，需减一
        if(is_array($id))
        {
            if($old_data_len==1)
            {
                $this->del_one($id[0],$data);
            }
            $u_d_arr2=$u_d_arr=array(); // 存放用户组 用户文件数据 文件名id 数组
            foreach($id as $vv)
            {
                //     $v=$v-1;
                $v=$vv-1; // 与数据表一致
                $user_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child.'_'.$v.self::$conf_data["DATA_EXT"];
                // 如果该项目组下有项目 不可删除
                if(is_file($user_file))
                {
                    $data2=$this->read_data($this->table_child.'_'.$v);
                    if(!empty($data2))
                    {
                        array_push($u_d_arr,$v+1);  // 加 1 与列表页面展现的 一致编号，
                    }else
                    {
                        @unlink($user_file);
                    }
                }
                // 判断项目回收站是否有 此项目类型的项目
                $user_data_r=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child_r.'_'.$v.self::$conf_data["DATA_EXT"];
                if(is_file($user_data_r))
                {
                    $data2=$this->read_data($this->table_child_r.'_'.$v);
                    if(!empty($data2))
                    {
                        array_push($u_d_arr2,$v+1);  // 加 1 与列表页面展现的 一致编号，
                    }else
                    {
                        @unlink($user_data_r);
                    }
                }
            } //-------------------------------------循环检查所有的要删除的id

            if(!empty($u_d_arr)) // 项目产品
            {
                $u_d_arr=implode(" | ",$u_d_arr);
                $this->skip("error",null,"<p>此".$this->c_name."下有".$this->c_name_c.$u_d_arr."组请先删除</p><p>此".$this->c_name ."下的".$this->c_name_c."再删除".$this->c_name ."</p>删除",10);
            }
            if(!empty($u_d_arr2)) // 项目产品回收站
            {
                $u_d_arr2=implode(" | ",$u_d_arr2);
                $this->skip("error",null,"<p>此".$this->c_name."下回收站有".$this->c_name_c.$u_d_arr2."组请先删除</p><p>此".$this->c_name ."下的".$this->c_name_c."再删除".$this->c_name ."</p>删除",10);
            }

            //如果要删除的用户组下没有用户---删除用户组
            foreach($id as $v)//-------------- 此id 需要减一
            {
                if(isset($data[$v-1]) && !empty($data[$v-1]))//----------------------删除前先判断是否存在此值
                {
                    unset($data[$v-1]);
                }else
                {
                    $this->skip("error",null,"删除".$this->c_name."数据出错 操作");
                }
            }
            $new_data_len=count($data);
            $f_len=$this->write_date($data,strtolower(__CLASS__),true);

            if($f_len>1 && ($old_data_len=($new_data_len+$del_id_c)))
            {
                $this->skip(SUCCESS,null,"删除".$this->c_name);
            }else
            {
                $this->skip("error",null,"删除".$this->c_name);
            }
        }
        else // ------------------------删除单个用户组
        {
            $this->del_one($id,$data);
        }
    }

    /** 删除单条数据
     * @parem $id 要删除的id
     * @parem $data 要删除的数据
     * */
    protected function del_one($id,$data)
    {
        // 是否有用户属于此组---用户表
        $id=$id-1;
        // 判断是否存在项目表
        $user_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child.'_'.$id.self::$conf_data["DATA_EXT"];
        if(is_file($user_file))
        {
            $data2=$this->read_data($this->table_child.'_'.$id);
            if(!empty($data2))
            {
                $this->skip("error",null,"<p>此".$this->c_name.'有'.$this->c_name_c."请先删除</p><p>此".$this->c_name ."下的".$this->c_name_c."再删除".$this->c_name ."</p>删除",10);
            }else
            {
               unlink($user_file); // 删除用户组下的用户表文件
            }
        }
        // 判断是否存在回收站表
        $user_data_r=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child_r.'_'.$id.self::$conf_data["DATA_EXT"];
        if(is_file($user_data_r))
        {
            $data2=$this->read_data($this->table_child_r.'_'.$id);
            if(!empty($data2))
            {
                $this->skip("error",null,"<p>此".$this->c_name.'回收站有'.$this->c_name_c."请先删除</p><p>此".$this->c_name ."下的".$this->c_name_c."再删除".$this->c_name ."</p>删除",10);
            }else
            {
                unlink($user_data_r); // 删除用户组下的用户表文件
            }
        }

        $old_data_len=count($data);
        if(isset($data[$id]) && !empty($data[$id])) //----------------------删除前先判断是否存在此值
        {
            unset($data[$id]);
        }else
        {
            $this->skip("error",null,"删除".$this->c_name."数据出错 操作");
        }

        $new_data_len=count($data);
        $f_len=$this->write_date($data,strtolower(__CLASS__),true);

        if($f_len>1 && ($old_data_len=($new_data_len+1)))
        {
            $this->skip("success",null,"删除".$this->c_name);
        }else
        {
            $this->skip("error",null,"删除".$this->c_name);
        }
     }

} 