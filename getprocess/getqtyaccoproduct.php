<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];
$distype=$_POST['distype'];

$sqlproduct="SELECT `newsaleprice`, `refillsaleprice`, `unitprice`, `refillprice` FROM `tbl_product` WHERE `idtbl_product`='$productID'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    $obj->newsaleprice=$rowproduct['newsaleprice'];
    $obj->refillsaleprice=$rowproduct['refillsaleprice'];
    $obj->unitprice=$rowproduct['unitprice'];
    $obj->refillprice=$rowproduct['refillprice'];
}
else{
    $obj=new stdClass();
    $obj->newsaleprice=0;
    $obj->refillsaleprice=0;
    $obj->unitprice=0;
    $obj->refillprice=0;
}
echo json_encode($obj);
?>