<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-3
 * Time: 下午3:59
 */
class Group extends Pro {

    private $c_name="用户组"; // 当前操作对象
    private $c_name_c="组员"; // 当前操作组子元素
    private $g_id_field="g_id"; // 当前操作组字段名称
    private $g_n_field="g_n"; //当前操作元素名称字段
    private $c_file="User";// 子元素控制器名称
    private $table_child=DATA_USER;// 子元素的表

    public function  __construct()
    {
        parent::__construct();
    }

    /** 用户组列表页面
     * @parem $is_update 是否更新缓存
     * */
    public function index($is_update=false)
    {
        if(!isset($is_update) || $is_update!=true)
        {
            // 访问动态页面
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
                $url = "?p={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']-- Page.php  这个类中使用此参数
                $total=count($list2);                // 数据开始位置    数组截取的结束位置
                $list=array_slice($list2,($curpage-1)*$showrow,$showrow);
                rsort($list); // 文件排序
            }
            $requer_file=strtolower(__CLASS__);
            require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
        }else
        {
            //生成静态页面
             $Tem=Com::getInstance("Tem");
             $data=$this->cur_pos(__CLASS__,__FUNCTION__);
             $data["list"]=$this->read_data(strtolower(__CLASS__));
             $Tem->write_html("group",$data,true);
        }
    }

    /** 用户组添加页面
     * @parem $is_update 是否更新缓存
     * */
    public function add($is_update=false)
    {
        // 访问静态页面
      /*  $v_n=$this->view_name(__CLASS__,__FUNCTION__);
        if(!is_file($v_n) || ($is_update==true))
        {
            //静态页面
            $Tem=Com::getInstance("Tem");
            $this->cur_pos_v=$this->cur_pos(__CLASS__,__FUNCTION__);
            $Tem->write_html("group_add",$this->cur_pos_v,true);
        }
        echo '<script>location.href="/View/group_add.html"</script>';*/
        // 访问动态页面
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
            $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","添加".$this->c_name);
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
                $all_data["g"][$k][$this->g_n_field]=$data[$v-1];
                $all_data["g"][$k][$this->g_id_field]=$v;  // 传过去的g_id 是已经加了 【1】 的
            }
        }else
        {
            $all_data["g"][0][$this->g_n_field]=$data[$id-1];
            $all_data["g"][0][$this->g_id_field]=$id;
        }
        $requer_file=strtolower(__CLASS__).DE_LIMITER.__FUNCTION__;
        require $_SERVER['DOCUMENT_ROOT']."/Tem/".$requer_file.".php";
    }

    /** 执行修改
     * */
    public function edit_exct()
    {
        $post=$_POST[$this->g_n_field];
        $g_id=array_keys($post);
        $data=$this->read_data(strtolower(__CLASS__));

        foreach($g_id as $k=>$v)
        {

            if(isset($data[$v-1]) && !empty($data[$v-1]))  // ----------------------判断有修改的值是否存在
            {
                $data[$v-1]=$post[$v];
            }else
            {
                $this->skip("error",null,"修改 $this->c_name 数据出错 操作");
            }
        }
        $f_len=$this->write_date($data,strtolower(__CLASS__),true);
        if($f_len>1)
        {
            $this->skip(SUCCESS,PHP_CON.__CLASS__.".php","修改".$this->c_name);
        }else
        {
            $this->skip("error",null,"修改".$this->c_name);
        }

    }

    /** 执行删除
     * */
    public function del($id)
    {
        $data=$this->read_data(strtolower(__CLASS__)); //------------组数据
        $old_data_len=count($data);
        $del_id_c=count($id);
        // ----------------------------------传过来的id 是加了1 ，需减一
        if(is_array($id))
        {
            if($old_data_len==1)
            {
                $this->del_one($id[0],$data);
                return false;
            }
            $u_d_arr=array(); // 存放用户组 用户文件数据 文件名id 数组
            foreach($id as $vv)
            {
           //     $v=$v-1;
                $v=$vv-1;  // 与数据表一致
                $user_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child.'_'.$v.self::$conf_data["DATA_EXT"];
                // 如果该用户组下有用户 不可删除
                if(is_file($user_file))
                {
                    $user_data=$this->read_data($this->table_child.'_'.$v);
                    if(!empty($user_data))
                    {
                        array_push($u_d_arr,$v+1); // 加 1 与列表页面展现的 一致编号，
                    }else
                    {
                        @unlink($user_file);
                    }
                }
            }

            if(!empty($u_d_arr))  // 如果该用户组下有用户 不可删除
            {
                $u_d_arr=implode("|",$u_d_arr);
   $this->skip("error",PHP_CON.__CLASS__.".php","<p>此".$this->c_name."下有".$this->c_name_c."请先删除</p><p>[ $u_d_arr ] ".$this->c_name."下的".$this->c_name_c."再删除".$this->c_name."</p>删除");
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
        $user_file=$_SERVER['DOCUMENT_ROOT'].DATA_TXT.$this->table_child.'_'.$id.self::$conf_data["DATA_EXT"];

        if(is_file($user_file))
        {
            $user_data=$this->read_data($this->table_child.'_'.$id);
            if(!empty($user_data))
            {
                $this->skip("error",PHP_CON.__CLASS__.".php","<p>此".$this->c_name."下有".$this->c_name_c."请先删除</p><p> ".$this->c_name."下的".$this->c_name_c."再删除".$this->c_name."</p>删除");
            }else
            {
                @unlink($user_file); // 删除用户组下的用户表文件
            }
        }

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
            $this->skip(SUCCESS,null,"删除".$this->c_name);
        }else
        {
            $this->skip("error",null,"删除".$this->c_name);
        }
    }
}