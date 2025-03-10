<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_bank_account` WHERE `idtbl_bank_account`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_bank_account'];
$obj->accountno=$row['accountno'];
$obj->bank=$row['tbl_bank_idtbl_bank'];
$obj->bankbranch=$row['tbl_bank_branch_idtbl_bank_branch'];
$obj->paymenttype=$row['tbl_paymenttype_idtbl_paymenttype'];

echo json_encode($obj);
?>