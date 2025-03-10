<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_account_type` WHERE `idtbl_account_type`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_account_type'];
$obj->accountType=$row['accounttype'];
echo json_encode($obj);
?>