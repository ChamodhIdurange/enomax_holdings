<?php 
require_once('../connection/db.php');


$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_supplier` WHERE `idtbl_supplier`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_supplier'];
$obj->name=$row['suppliername'];
$obj->contactone=$row['contactone'];
$obj->contacttwo=$row['contacttwo'];
$obj->email=$row['email'];
$obj->address=$row['address'];

echo json_encode($obj);
?>