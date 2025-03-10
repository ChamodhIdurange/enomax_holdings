<?php
include "include/header.php";  

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');
$filename = "process/maintodamage.csv";
$file = fopen($filename, 'r');
$c = 0;
$lineid = '';
$prevlineid = '';
$last_id = '';
$returnid = '';
$tot = 0;

while (($line = fgetcsv($file)) !== FALSE) {
    $c++;

    $asmid = 8;
    $date = $line[0];
    $productcode = $line[2];
    $qty = $line[4];
    $asm = 8;
    $returntype = 3;
    $customer = 330;
    $remarks = "Inserted from xsml file";
    if($c == 1 || $c == 2){
        continue;
    }
    
    if($lineid != $line[1]){
        $prevlineid = $last_id;
        $lineid = $line[1];

        $updatequery = "UPDATE `tbl_return` SET `total` = '$tot' WHERE `idtbl_return` = '$prevlineid'";
        $conn->query($updatequery);

        if($line[1] == null){
            break;
        }
        $query="INSERT INTO `tbl_return`(`tbl_employee_idtbl_employee`, `returntype`, `returndate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `acceptance_status`, `total`, `damaged_reason`) VALUES ('$asm', '$returntype','$date','1','$updatedatetime','$userID','$customer', '0', '0', '$remarks')";
        $conn->query($query);
        $last_id = mysqli_insert_id($conn);

        $tot = 0;

    }

    

    $sqlproduct = "SELECT `idtbl_product`, `saleprice` FROM `tbl_product` WHERE `product_code` = '$productcode'";
    $resultproduct = $conn->query($sqlproduct);
    $row = $resultproduct->fetch_assoc();

    $productID = $row['idtbl_product'];
    $saleprice = $row['saleprice'];
    $subtotal = $qty * $saleprice;
    $tot = $tot + $subtotal;

    $insertreturndetails="INSERT INTO `tbl_return_details`(`unitprice`, `qty`, `discount`, `tbl_product_idtbl_product`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_return_idtbl_return`,`total`) VALUES ('$saleprice','$qty','0','$productID','$updatedatetime','$userID','$last_id','$subtotal')";
    $conn->query($insertreturndetails);


   

}

print_r($c);

?>