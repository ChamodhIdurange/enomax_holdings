<?php 
require_once('../connection/db.php');

$productID=$_POST['productID'];
$customerID=$_POST['customerID'];
$vehicleloadID=$_POST['vehicleloadID'];

$sqlprice="SELECT `unitprice`, `refillprice` FROM `tbl_product` WHERE `idtbl_product`='$productID' AND `status`=1";
$resultprice=$conn->query($sqlprice);
$rowprice=$resultprice->fetch_assoc();

$sqlsaleprice="SELECT `newsaleprice`, `refillsaleprice` FROM `tbl_customer_product` WHERE `tbl_product_idtbl_product`='$productID' AND `tbl_customer_idtbl_customer`='$customerID' AND `status`=1";
$resultsaleprice=$conn->query($sqlsaleprice);
$rowsaleprice=$resultsaleprice->fetch_assoc();

$sqlavaqty="SELECT `qty` FROM `tbl_vehicle_load_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
$resultavaqty=$conn->query($sqlavaqty);
$rowavaqty=$resultavaqty->fetch_assoc();

$sqlbufferqty="SELECT `fullqty` FROM `tbl_customer_stock` WHERE `tbl_customer_idtbl_customer`='$customerID' AND `tbl_product_idtbl_product`='$productID' AND `status`=1";
$resultbufferqty=$conn->query($sqlbufferqty);
$rowbufferqty=$resultbufferqty->fetch_assoc();

$obj=new stdClass();
$obj->unitprice=$rowprice['unitprice'];
$obj->refillprice=$rowprice['refillprice'];
$obj->newsaleprice=$rowsaleprice['newsaleprice'];
$obj->refillsaleprice=$rowsaleprice['refillsaleprice'];
$obj->avaqty=$rowavaqty['qty'];
$obj->bufferqty=$rowbufferqty['fullqty'];

echo json_encode($obj);