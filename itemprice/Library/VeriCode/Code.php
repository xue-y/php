<?php
/* 示例使用
 header("content-type:image/gif");
$code=new code();
$code->c_img(70,30,14,4);
 * */
class Code{
    public  function c_img($w,$h,$size,$num)
    {
        if(!isset($_SESSION))
            session_start(); //开启session

        $arr_num=range('0','9');
        $arr_lowercase=range('a','z');
        $arr_uppercase=range('A','Z');
        $arr_mer=array_merge($arr_num,$arr_lowercase,$arr_uppercase);
        $arr_flip=array_flip($arr_mer);
        $arr_rand=array_rand($arr_flip,$num);
        $str=implode('',$arr_rand);
        //echo $str;
        $_SESSION['code']=$str;
        $img=imagecreatetruecolor($w,$h);
        $color=imagecolorallocate($img,rand(200,255),rand(221,240),rand(215,200));
        imagefill($img,0,0,$color);

        //$ttf_style='msyh.ttf'; window
        $ttf_style=$_SERVER['DOCUMENT_ROOT']."/Library/VeriCode/msyh.ttf"; //liunx
        $Y=$h/2+$size/2;
        $X=$w/$num;

        for($i=0;$i<100;$i++)
        {
            $px=imagecolorallocate($img,mt_rand(0,255),mt_rand(0,200),mt_rand(0,200));
            imagesetpixel($img,rand(0,$w-1),rand(0,$h-1),$px);
        }
        for($i=2;$i<5;$i++)
        {
            $line=imagecolorallocate($img,mt_rand(0,155),mt_rand(0,200),mt_rand(0,255));
            imageline($img,rand(0,$w/2),rand(0,$h/2),rand($w/2,$w),rand($h/2,$h),$line);
        }
        for($i=2;$i<5;$i++)
        {
            $arc=imagecolorallocate($img,mt_rand(0,155),mt_rand(0,200),mt_rand(0,255));
    imagefilledarc($img,rand($w/2,$w),rand($h/2,$h),rand($w/4,$w-10),rand($h/3,$h-10),rand($h/5,$h/2),rand($h/2,$w),$arc,IMG_ARC_NOFILL);
        }

        for($i=0;$i<strlen($str);$i++)
        {
            $ttf=imagecolorallocate($img,mt_rand(1,55),mt_rand(10,155),mt_rand(10,100));
            imagettftext($img,$size,rand(-30,30),$i*$X+$size/2,$Y,$ttf,$ttf_style,substr($str,$i,1));
        }

        imagegif($img);
       imagedestroy($img);
    }
}

?>
