<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_paymenttype` WHERE `idtbl_paymenttype`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_paymenttype'];
$obj->paymenttype=$row['paymenttype'];

echo json_encode($obj);
?>