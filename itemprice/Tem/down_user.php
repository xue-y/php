<!DOCTYPE html>
<html lang="zh-cn">
<head><!-- 用户导出html页面-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title><?php echo $all_data["web_name"];?></title>
    <style>
        *{box-sizing: border-box;}
        body {
            font-size: 14px;
            color: #333;
            background: #fff;
            font-family: "Microsoft YaHei","simsun","Helvetica Neue", Arial, Helvetica, sans-serif;
        }
         tbody, tfoot{
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 14px;
            font: inherit;
            vertical-align: baseline;
        }
        .table {
            width: calc(100% - 20px);
            width: -webkit-calc(100% - 20px);
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #cbcbcb;
            margin: 0px 10px;
        }
        .text-center {
            text-align: center;
        }
        .table th {
            vertical-align: bottom;
            padding: 8px;
            background-color: #F0F5F7;
            border-bottom: 1px solid #ddd;
        }
        .table-hover > tbody > tr:hover {
            background-color: #f9f9f9;
        }
        .table tbody tr {
            height: 40px;
            text-align: center;
        }
        .padding {
            padding: 10px;
        }
        .table td {
            vertical-align: middle;  border-right: dotted 1px #c7c7c7;
        }
        .table tbody tr:nth-child(even) {
            background: #F4F4F4;
        }
        .table tbody tr {
            height: 40px;
            text-align: center;
        }
        h1, .h1 {
            font-size: 24px;
        }
    </style>
</head>
<body>

<div class="panel padding">
    <h1 class="text-center padding"><?php echo $all_data["web_name"];?></h1>
    <table class="table table-hover text-center">
        <tr>
            <?php
            foreach($all_data["tit"] as $v)
            {
                echo "<th>$v</th>";
            }
            ?>
        </tr>
        <?php
        if(empty($all_data["con"]))
        {
            if(empty($all_data["price"]))
            {
                echo "<tr><td colspan='".count($all_data["tit"])."'>暂无数据</td></tr>";
            }else
            {
                echo "<tr><td colspan='".count($all_data["tit"])."'>暂无数据</td></tr>";
            }
        }else
        {
            foreach($all_data["con"] as $v)
            {
                echo '<tr>
                    <td>'.$v["n"].'</td>';
                if(empty($all_data["price"])) // 管理员
                {
                    foreach($all_data["key"] as $price_v)
                    {
                        echo '<td>'.$v[$price_v].'</td>';
                    }
                }else                           // 普通组员
                {
                    echo  '<td>'.$v[$all_data["price"]].'</td>';
                }
                echo'<td>'.$v["t_n"].'</td>
                    <td>'.$v["t"].'</td>
                    <td>'.$v["bz"].'</td>
                   </tr>' ;
            }
        }
        ?>
    </table>
</div>
</body>
</html>