<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_damage_return` WHERE `idtbl_damage_return`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_damage_return'];
$obj->returntype=$row['returntype'];
$obj->customer=$row['tbl_customer_idtbl_customer'];
$obj->product=$row['tbl_product_idtbl_product'];
$obj->qty=$row['qty'];

echo json_encode($obj);
?>