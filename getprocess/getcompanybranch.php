<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_company_branch` WHERE `idtbl_company_branch`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_company_branch'];
$obj->code=$row['code'];
$obj->branch=$row['branch'];
$obj->address1=$row['address1'];
$obj->address2=$row['address2'];
$obj->mobile=$row['mobile'];
$obj->phone=$row['phone'];
$obj->email=$row['email'];
$obj->company=$row['tbl_company_idtbl_company'];

echo json_encode($obj);
?>