<?php
include "include/header.php";  

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');
$filename = "process/customerreturn.csv";
$file = fopen($filename, 'r');
$c = 0;
$lineid = '';
$prevlineid = '';
$last_id = '';
$returnid = '';
$tot = 0;

while (($line = fgetcsv($file)) !== FALSE) {
    $c++;

    $date = $line[0];
    $productcode = $line[3];
    $qty = $line[5];
    $customer = $line[6];

    $asm = 8;
    $returntype = 1;
    $remarks = "Inserted from xsml file";
    if($c == 1){
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
        $query="INSERT INTO `tbl_return`(`tbl_employee_idtbl_employee`, `returntype`, `returndate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `acceptance_status`, `total`) VALUES ('$asm', '$returntype','$date','1','$updatedatetime','$userID','$customer', '0', '0')";
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