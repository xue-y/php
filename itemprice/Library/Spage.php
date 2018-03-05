<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-8
 * Time: 下午12:25
 * $page=new Spage();
 * $page->show($total,$page_num,$page_size);
 */
class Spage
{
    public $page_first,$page_size,$page,$count;
    //起始页   一页显示几条 当前页  总的页数
    //$nav_count 页码个数
    function show($total,$size,$nav_count=5)
    {
        $nav_count=max($nav_count,2); // 最小必须是2
        $left=ceil($nav_count/2);	 //3  当前起始页  总页数大于5时
        $left_count=floor($nav_count/2);   //2 半侧栏页数

        $this->page_size=$size;
        $this->count=ceil($total/$this->page_size); //总的页数
        if($this->count <=1)
        {
            $this->count=1;
        }
        $this->page=empty($_GET['p'])?1:intval($_GET['p']); //当前页
        $this->page_first=($this->page-1)*$this->page_size; //起始位置

        $page_upper=$this->page-1<=1?1:$this->page-1;
        $page_lower=$this->page+1>=$this->count?$this->count:$this->page+1;

        $url=$_SERVER['REQUEST_URI'];
        $query=$_SERVER['QUERY_STRING'];
        if(!empty($query))
        {
            $url=str_replace('?p='.$this->page,'',$url);
            $url=str_replace('&p='.$this->page,'',$url);
        }
        $query=parse_url($url);
        //array
        /*  'path' => string '/read/php/per_read_class.php' (length=28)
            'query' => string 'type=%E7%BE%8E%E6%96%87' (length=23)*/
        $url.=isset($query['query'])?'&p=':'?p=';
        if($this->page <= 1)
        {
            echo '<a href="javascript:;" class="gray">首页</a>
			       <a href="javascript:;"  class="gray">上一页</a> ';
        }
        else
        {
            echo '<a href="'.$url.'1">首页</a>
			     <a href="'.$url.$page_upper.'">上一页</a> ';
        }

        if($this->count<=$nav_count)
        {
            for($i=1;$i<=$this->count;$i++)
            {
                if($i==$this->page)
                {
                    echo '<a class="cur">'.$this->page.'</a>';
                }
                else
                {

                    echo ' <a href="'.$url.$i.'">'.$i.'</a> ';
                }
            }
        }//1---5页
        if($this->count > $nav_count)
        {
            if($this->page<=$left)
            {
                for($i=1;$i<=$nav_count;$i++)
                {
                    if($i==$this->page)
                    {
                        echo '<a class="cur">'.$this->page.'</a>';
                    }
                    else
                    {

                        echo ' <a href="'.$url.$i.'">'.$i.'</a> ';
                    }
                }
            }//1--5页
            if($this->page > $left && $this->page<=($this->count-$left_count) )
            {
                for($i=($this->page-$left_count);$i<=($this->page+$left_count);$i++)
                {
                    if($i==$this->page)
                    {
                        echo '<a class="cur">'.$this->page.'</a>';
                    }
                    else
                    {

                        echo ' <a href="'.$url.$i.'">'.$i.'</a> ';
                    }
                }
            }//大于5页小于 总页数-左边侧栏
            if($this->page >($this->count-$left_count) || ($this->page==$this->count))
            {
                for($i=intval($this->page-$left_count);$i<=$this->count;$i++)
                {
                    if($i==$this->page)
                    {
                        echo '<a class="cur">'.$this->page.'</a>';
                    }
                    else
                    {

                        echo ' <a href="'.$url.$i.'">'.$i.'</a> ';
                    }
                }
            }//总页数-左边侧栏 到最后一页
        }//总的页数大于5页时
        if($this->page >= $this->count)
        {
            echo ' <a href="javascript:;"  class="gray">下一页</a>
			       <a href="javascript:;"  class="gray">尾页</a> ';
        }
        else
        {
            echo ' <a href="'.$url.$page_lower.'">下一页</a>
			       <a href="'.$url.$this->count.'">尾页</a> ';
        }
        echo '<a>共<b>'.$total.'</b>条数据
		  共<b>'.$this->count.'</b>页 </a><!--分页-->';
    }

}