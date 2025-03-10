<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_bank_branch` WHERE `idtbl_bank_branch`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_bank_branch'];
$obj->code=$row['code'];
$obj->branchname=$row['branchname'];
$obj->phone=$row['phone'];
$obj->address=$row['address'];
$obj->bank=$row['tbl_bank_idtbl_bank'];
echo json_encode($obj);
?>