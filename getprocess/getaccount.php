<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_account` WHERE `idtbl_account`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_account'];
$obj->accountName=$row['account'];
$obj->accountNo=$row['accountno'];
$obj->acountType=$row['tbl_account_type_idtbl_account_type'];
$obj->bank=$row['tbl_bank_idtbl_bank'];
echo json_encode($obj);
?>