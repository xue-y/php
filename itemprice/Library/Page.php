<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18-1-6
 * Time: 上午9:13
 * 调用示例
 * $showrow = 10; //一页显示的行数
 * $curpage = empty($_GET['page']) ? 1 : $_GET['page']; //当前的页,还应该处理非数字的情况
 * $url = "?page={page}"; //分页地址，如果有检索条件 ="?page={page}&q=".$_GET['q']

 * if ($total > $showrow) {//总记录数大于每页显示数，显示分页
 *  $page = new page($total, $showrow, $curpage, $url, 2);
 *   echo $page->myde_write();
 *  }
 */

class Page{
    private $myde_total;          //总记录数
    private $myde_size;           //一页显示的记录数
    private $myde_page;           //当前页
    private $myde_page_count;     //总页数
    private $myde_i;              //起头页数
    private $myde_en;             //结尾页数
    private $myde_url;            //获取当前的url

    /*
     * $show_pages--  ----适用于只有一个参数
     * 页面显示的格式，显示链接的页数为2*$show_pages+1。
     * 如$show_pages=2那么页面上显示就是[首页] [上页] 1 2 3 4 5 [下页] [尾页]
     */
    private $show_pages;

    public function show($myde_total = 1, $myde_size = 1, $myde_page = 1, $myde_url, $show_pages = 2) {
        $show_pages=max($show_pages,2);
        $this->myde_total = $this->numeric($myde_total);
        $this->myde_size = $this->numeric($myde_size);
        $this->myde_page = $this->numeric($myde_page);
        $this->myde_page_count = ceil($this->myde_total / $this->myde_size);
        $this->myde_url = $myde_url;
        if ($this->myde_total < 0)
            $this->myde_total = 0;
        if ($this->myde_page < 1)
            $this->myde_page = 1;
        if ($this->myde_page_count < 1)
            $this->myde_page_count = 1;
        if ($this->myde_page > $this->myde_page_count)
            $this->myde_page = $this->myde_page_count;
        $this->limit = ($this->myde_page - 1) * $this->myde_size;
        $this->myde_i = $this->myde_page - $show_pages;
        $this->myde_en = $this->myde_page + $show_pages;
        if ($this->myde_i < 1) {
            $this->myde_en = $this->myde_en + (1 - $this->myde_i);
            $this->myde_i = 1;
        }
        if ($this->myde_en > $this->myde_page_count) {
            $this->myde_i = $this->myde_i - ($this->myde_en - $this->myde_page_count);
            $this->myde_en = $this->myde_page_count;
        }
        if ($this->myde_i < 1)
            $this->myde_i = 1;
    }

    //检测是否为数字
    private function numeric($num) {
        if (strlen($num)) {
            if (!preg_match("/^[0-9]+$/", $num)) {
                $num = 1;
            } else {
                $num = substr($num, 0, 11);
            }
        } else {
            $num = 1;
        }
        return $num;
    }

    //地址替换
    public  function page_replace($page) {
      return str_replace("{page}", $page, $this->myde_url);
    }

    //首页
    private function myde_home() {
        if ($this->myde_page != 1) {
            return "<a href='" . $this->page_replace(1) . "' title='首页'>首页</a>";
        } else {
            return "<a class='gray'>首页</a>";
        }
    }

    //上一页
    private function myde_prev() {
        if ($this->myde_page != 1) {
            return "<a href='" . $this->page_replace($this->myde_page - 1) . "' title='上一页'>上一页</a>";
        } else {
            return "<a class='gray'>上一页</a>";
        }
    }

    //下一页
    private function myde_next() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<a href='" . $this->page_replace($this->myde_page + 1) . "' title='下一页'>下一页</a>";
        } else {
            return"<a class='gray'>下一页</a>";
        }
    }

    //尾页
    private function myde_last() {
        if ($this->myde_page != $this->myde_page_count) {
            return "<a href='" . $this->page_replace($this->myde_page_count) . "' title='尾页'>尾页</a>";
        } else {
            return "<a class='gray'>尾页</a>";
        }
    }
    //输出
    public function myde_write() {
        $str='';
        if($this->myde_total > $this->myde_size)
        {
            $str.=$this->myde_home();
            $str.=$this->myde_prev();
            if ($this->myde_i > 1) {
                $str.="<a>...</a>";
            }
            for ($i = $this->myde_i; $i <= $this->myde_en; $i++) {
                if ($i == $this->myde_page) {
                    $str.="<a href='" . $this->page_replace($i) . "' title='第" . $i . "页' class='cur'>$i</a>";
                } else {
                    $str.="<a href='" . $this->page_replace($i) . "' title='第" . $i . "页'>$i</a>";
                }
            }
            if ($this->myde_en < $this->myde_page_count) {
                $str.="<a>...</a>";
            }
            $str.=$this->myde_next();
            $str.=$this->myde_last();

        }
        $str.="<a>共<b>" . $this->myde_page_count .
            "</b>页<b>" . $this->myde_total . "</b>条数据</a>";
       echo $str;
    }
} 