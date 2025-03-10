<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_return_details` WHERE `idtbl_return_details`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_return_details'];
$obj->unitprice=$row['unitprice'];
$obj->qty=$row['qty'];
$obj->discount=$row['discount'];
$obj->total=$row['total'];
$obj->mainID=$row['tbl_return_idtbl_return'];

echo json_encode($obj);
?>