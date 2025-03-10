<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$updatedatetime=date('Y-m-d h:i:s');
$filename = "process/stockupdate2025V2.csv";

$file1 = fopen($filename, 'r');
$file2 = fopen($filename, 'r');
$c = 0;
$x = 0;
$month = date('m');
$year = substr(date('y'), -2);;

$total = 0;
$batchNo = "BTH".$year.$month.sprintf('%04s', '0000');

while (($line = fgetcsv($file2)) !== FALSE) {
    $x++;
    $productId = $line[0];
    $retailPrice = $line[4];
    $wholePrice = $line[5];
    $qty = $line[7];

    if($x == 1){
        continue;
    }
    $sqlproduct="SELECT `unitprice` FROM `tbl_product` WHERE `idtbl_product`='$productId'";
    $result=$conn->query($sqlproduct);
    $row=$result->fetch_assoc();
    $unitPrice = $row['unitprice'];


    $sqlupdate = "UPDATE `tbl_product` SET `saleprice` = '$retailPrice', `retail` = '$wholePrice', `unitprice` = '$unitPrice' WHERE `idtbl_product` = '$productId'";
    $conn->query($sqlupdate);

    $insertstock="INSERT INTO `tbl_stock` (`batchqty`, `qty`, `unitprice`, `saleprice`, `update`, `status`, `batchno`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$qty', '$qty', '$unitPrice', '$retailPrice', '$updatedatetime', '1', '$batchNo', '$updatedatetime', '$userID', '$productId')";
    $conn->query($insertstock);
}



print_r($x);

?>

