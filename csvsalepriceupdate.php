<?php
include "include/header.php";  

$filename = "process/pricereportnew2.csv";
$file = fopen($filename, 'r');
$i = 0;
while (($line = fgetcsv($file)) !== FALSE) {
    $i++;
    $productcode = $line[0];
    $saleprice = $line[2];
    $saleprice = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $saleprice );

    $sqlproduct = "UPDATE `tbl_product` SET `saleprice` = '$saleprice' WHERE `product_code` = '$productcode'";

    $resultproduct = $conn->query($sqlproduct);


}
echo $i;


?>