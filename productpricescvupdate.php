<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$updatedatetime=date('Y-m-d h:i:s');
$filename = "process/stockupdate.csv";
$file = fopen($filename, 'r');
$c = 0;
while (($line = fgetcsv($file)) !== FALSE) {
    $c++;

    $productid = $line[0];
    $qty = $line[4];
    $unitprice = $line[5];
    $saleprice = $line[6];
    if($c == 1){
        continue;
    }

    $saleprice = str_replace(',', '', $saleprice);

    $sqlupdate = "UPDATE `tbl_product` SET `unitprice` = '$unitprice', `saleprice` = '$saleprice' WHERE `idtbl_product` = '$productid'";
    $conn->query($sqlupdate);
}

print_r($c);

?>