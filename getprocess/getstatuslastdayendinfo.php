<?php 
require_once('../connection/db.php');

$yesterday=date('Y-m-d',strtotime(date('Y-m-d') . "-1 days"));
$today=date('Y-m-d');

$sql="SELECT COUNT(*) AS `count` FROM `tbl_stock_closing` WHERE `status`=1 AND `date`='$yesterday'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

if($row['count']>0){
    $sqltodayclose="SELECT COUNT(*) AS `count` FROM `tbl_stock_closing` WHERE `status`=1 AND `date`='$today'";
    $resulttodayclose=$conn->query($sqltodayclose);
    $rowtodayclose=$resulttodayclose->fetch_assoc();

    if($rowtodayclose['count']>0){
        echo '1';
    }
    else{
        echo '2';
    }
}
else{
    echo '0';
}
?>