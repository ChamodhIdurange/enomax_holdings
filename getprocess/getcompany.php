<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_company` WHERE `idtbl_company`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_company'];
$obj->code=$row['code'];
$obj->name=$row['name'];
$obj->address1=$row['address1'];
$obj->address2=$row['address2'];
$obj->mobile=$row['mobile'];
$obj->phone=$row['phone'];
$obj->email=$row['email'];

echo json_encode($obj);
?>