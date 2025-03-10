<?php
include "include/header.php";  

$filename = "process/newstockreport.csv";
$file = fopen($filename, 'r');
$i = 0;
while (($line = fgetcsv($file)) !== FALSE) {
    $i++;
    $productId = $line[0];
    $qty = $line[4];
    $qty = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $qty );
    // echo $productId;
    // echo "----";

    // $sqlproduct = "SELECT * FROM `tbl_product` WHERE `product_code` = '$productcode'";
    // $result =$conn-> query($sqlproduct); 
    // $products = $result-> fetch_assoc();

    // $productid = $products['idtbl_product'];

    // $sqlproduct = "UPDATE `tbl_stock` SET `qty` = '$qty' WHERE `tbl_product_idtbl_product` = '$productid'";

    // $resultproduct = $conn->query($sqlproduct);

    $insertgrnstock = "INSERT INTO `tbl_grn_stock` (`qty`, `status`, `insertdatetime`, `grndate`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('$qty', '1', '$updatedatetime', '$dayenddate', '$userID', '$productId')";
    $result =$conn-> query($insertgrnstock); 


}
echo $i;


?>