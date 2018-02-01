<?php
include('reader.php'); //引入类库，类的配置文件已经被此文件引入 php 6 以上
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('UTF-8'); //设置输出的编码为utf8
$data->read('data.xls'); //要读取的excel文件地址
echo '<pre>';
print_r($data->sheets[0]); //打印输出sheet数组。

/*$data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column*/

/*
$data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	echo "\n";
}*/