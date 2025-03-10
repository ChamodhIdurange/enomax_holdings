<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_company` WHERE `idtbl_company`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_company'];
$obj->name=$row['name'];
$obj->code=$row['code'];
$obj->address=$row['address1'];
$obj->phone=$row['phone'];
echo json_encode($obj);
?>