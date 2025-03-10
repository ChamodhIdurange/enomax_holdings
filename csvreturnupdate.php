<?php
include "include/header.php";  

$filename = "process/returndetailscsv.csv";
$file = fopen($filename, 'r');
$i = 0;
while (($line = fgetcsv($file)) !== FALSE) {
    $i++;
    $customername = $line[2];

    $sqlselect = "SELECT `idtbl_customer` FROM `tbl_customer` WHERE `name` = '$customername'";
    $result =$conn-> query($sqlselect); 
    $customerdata = $result-> fetch_assoc();

    $productid = $customerdata['idtbl_customer'];
    print_r($productid. '-');

    // $saleprice = $line[7];
    // $saleprice = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $saleprice );


    // $sqlproduct = "UPDATE `tbl_product` SET `saleprice` = '$saleprice' WHERE `product_code` = '$productcode'";

    // $resultproduct = $conn->query($sqlproduct);


}
echo $i;


?>