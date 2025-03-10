<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_return` WHERE `idtbl_return`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_return'];
$obj->returntype=$row['returntype'];
$obj->customer=$row['tbl_customer_idtbl_customer'];
$obj->supplier=$row['tbl_supplier_idtbl_supplier'];
$obj->product=$row['tbl_product_idtbl_product'];
$obj->qty=$row['qty'];

echo json_encode($obj);
?>