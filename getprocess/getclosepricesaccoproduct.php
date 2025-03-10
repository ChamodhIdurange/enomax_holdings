<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];

$sqlproduct="SELECT `newsaleprice`, `emptyprice` FROM `tbl_product` WHERE `idtbl_product`='$productID'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    $obj->newsaleprice=$rowproduct['newsaleprice'];
    $obj->emptyprice=$rowproduct['emptyprice'];
}
else{
    $obj=new stdClass();
    $obj->newsaleprice='0';
    $obj->emptyprice='0';
}
echo json_encode($obj);
?>